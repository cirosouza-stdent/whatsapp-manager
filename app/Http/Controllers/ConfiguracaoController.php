<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

/**
 * Controller para gerenciamento de configurações do sistema.
 */
class ConfiguracaoController extends Controller
{
    /**
     * Exibe a página de configurações do WhatsApp.
     */
    public function whatsapp(): View
    {
        $config = [
            'api_url' => env('WHATSAPP_API_URL', ''),
            'api_key' => env('WHATSAPP_API_KEY', ''),
            'webhook_url' => url('/webhook'),
            'timeout' => env('WHATSAPP_TIMEOUT', 30),
        ];

        return view('configuracoes.whatsapp', compact('config'));
    }

    /**
     * Salva as configurações do WhatsApp.
     */
    public function whatsappSalvar(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'api_url' => 'required|url',
            'api_key' => 'required|string|min:10',
            'timeout' => 'required|integer|min:5|max:120',
        ]);

        // Atualiza o arquivo .env
        $this->updateEnvFile([
            'WHATSAPP_API_URL' => $validated['api_url'],
            'WHATSAPP_API_KEY' => $validated['api_key'],
            'WHATSAPP_TIMEOUT' => $validated['timeout'],
        ]);

        return redirect()
            ->route('configuracoes.whatsapp')
            ->with('success', 'Configurações do WhatsApp salvas com sucesso!');
    }

    /**
     * Testa a conexão com a API do WhatsApp.
     */
    public function whatsappTestar(Request $request)
    {
        $validated = $request->validate([
            'api_url' => 'required|url',
            'api_key' => 'required|string',
        ]);

        try {
            $client = new \GuzzleHttp\Client([
                'timeout' => 10,
                'verify' => false,
            ]);

            $response = $client->get($validated['api_url'], [
                'headers' => [
                    'apikey' => $validated['api_key'],
                    'Accept' => 'application/json',
                ],
            ]);

            $statusCode = $response->getStatusCode();

            if ($statusCode >= 200 && $statusCode < 300) {
                return response()->json([
                    'success' => true,
                    'message' => 'Conexão estabelecida com sucesso!',
                    'status_code' => $statusCode,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'A API retornou um status inesperado.',
                'status_code' => $statusCode,
            ]);

        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Não foi possível conectar ao servidor. Verifique a URL.',
            ], 400);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            
            if ($statusCode === 401 || $statusCode === 403) {
                return response()->json([
                    'success' => false,
                    'message' => 'API Key inválida ou sem permissão.',
                ], 401);
            }

            return response()->json([
                'success' => false,
                'message' => 'Erro do cliente: ' . $e->getMessage(),
            ], $statusCode);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao testar conexão: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Atualiza valores no arquivo .env.
     */
    protected function updateEnvFile(array $values): void
    {
        $envPath = base_path('.env');
        $envContent = File::get($envPath);

        foreach ($values as $key => $value) {
            // Escapa valores com espaços
            if (str_contains($value, ' ')) {
                $value = '"' . $value . '"';
            }

            // Verifica se a chave já existe
            if (preg_match("/^{$key}=.*/m", $envContent)) {
                // Atualiza o valor existente
                $envContent = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}={$value}",
                    $envContent
                );
            } else {
                // Adiciona nova chave ao final
                $envContent .= "\n{$key}={$value}";
            }
        }

        File::put($envPath, $envContent);

        // Limpa o cache de configuração
        \Artisan::call('config:clear');
    }
}
