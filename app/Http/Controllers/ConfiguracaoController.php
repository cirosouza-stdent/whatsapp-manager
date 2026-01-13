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
     * Exibe a página de configurações do Facebook.
     */
    public function facebook(): View
    {
        $config = [
            'app_id' => env('FACEBOOK_APP_ID', ''),
            'app_secret' => env('FACEBOOK_APP_SECRET', ''),
            'access_token' => env('FACEBOOK_ACCESS_TOKEN', ''),
            'page_access_token' => env('FACEBOOK_PAGE_ACCESS_TOKEN', ''),
            'graph_version' => env('FACEBOOK_GRAPH_VERSION', 'v18.0'),
            'callback_url' => url('/auth/facebook/callback'),
        ];

        return view('configuracoes.facebook', compact('config'));
    }

    /**
     * Salva as configurações do Facebook.
     */
    public function facebookSalvar(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'app_id' => 'required|string|min:10',
            'app_secret' => 'required|string|min:10',
            'access_token' => 'nullable|string',
            'page_access_token' => 'nullable|string',
            'graph_version' => 'required|string|in:v16.0,v17.0,v18.0',
        ]);

        $this->updateEnvFile([
            'FACEBOOK_APP_ID' => $validated['app_id'],
            'FACEBOOK_APP_SECRET' => $validated['app_secret'],
            'FACEBOOK_ACCESS_TOKEN' => $validated['access_token'] ?? '',
            'FACEBOOK_PAGE_ACCESS_TOKEN' => $validated['page_access_token'] ?? '',
            'FACEBOOK_GRAPH_VERSION' => $validated['graph_version'],
        ]);

        return redirect()
            ->route('configuracoes.facebook')
            ->with('success', 'Configurações do Facebook salvas com sucesso!');
    }

    /**
     * Testa a conexão com a API do Facebook.
     */
    public function facebookTestar(Request $request)
    {
        $validated = $request->validate([
            'app_id' => 'required|string',
            'app_secret' => 'required|string',
            'access_token' => 'nullable|string',
        ]);

        try {
            $client = new \GuzzleHttp\Client([
                'timeout' => 10,
                'verify' => false,
            ]);

            // Testa obtendo um app access token
            $response = $client->get('https://graph.facebook.com/oauth/access_token', [
                'query' => [
                    'client_id' => $validated['app_id'],
                    'client_secret' => $validated['app_secret'],
                    'grant_type' => 'client_credentials',
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if (isset($data['access_token'])) {
                return response()->json([
                    'success' => true,
                    'message' => 'Conexão estabelecida com sucesso! App verificado.',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Resposta inesperada da API do Facebook.',
            ]);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents(), true);
            $errorMessage = $response['error']['message'] ?? 'Erro desconhecido';
            
            return response()->json([
                'success' => false,
                'message' => 'Erro do Facebook: ' . $errorMessage,
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao testar conexão: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Exibe a página de configurações do Instagram.
     */
    public function instagram(): View
    {
        $config = [
            'app_id' => env('INSTAGRAM_APP_ID', ''),
            'app_secret' => env('INSTAGRAM_APP_SECRET', ''),
            'access_token' => env('INSTAGRAM_ACCESS_TOKEN', ''),
            'business_id' => env('INSTAGRAM_BUSINESS_ID', ''),
        ];

        return view('configuracoes.instagram', compact('config'));
    }

    /**
     * Salva as configurações do Instagram.
     */
    public function instagramSalvar(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'instagram_app_id' => 'nullable|string|min:10',
            'instagram_app_secret' => 'nullable|string|min:10',
            'instagram_access_token' => 'nullable|string',
            'instagram_business_id' => 'nullable|string',
        ]);

        $this->updateEnvFile([
            'INSTAGRAM_APP_ID' => $validated['instagram_app_id'] ?? '',
            'INSTAGRAM_APP_SECRET' => $validated['instagram_app_secret'] ?? '',
            'INSTAGRAM_ACCESS_TOKEN' => $validated['instagram_access_token'] ?? '',
            'INSTAGRAM_BUSINESS_ID' => $validated['instagram_business_id'] ?? '',
        ]);

        return redirect()
            ->route('configuracoes.instagram')
            ->with('success', 'Configurações do Instagram salvas com sucesso!');
    }

    /**
     * Testa a conexão com a API do Instagram.
     */
    public function instagramTestar(Request $request)
    {
        $accessToken = env('INSTAGRAM_ACCESS_TOKEN');
        $businessId = env('INSTAGRAM_BUSINESS_ID');

        if (empty($accessToken) || empty($businessId)) {
            return response()->json([
                'success' => false,
                'message' => 'Access Token e Business ID são obrigatórios.',
            ], 400);
        }

        try {
            $client = new \GuzzleHttp\Client([
                'timeout' => 10,
                'verify' => false,
            ]);

            $response = $client->get("https://graph.facebook.com/v18.0/{$businessId}", [
                'query' => [
                    'fields' => 'id,username,name,profile_picture_url',
                    'access_token' => $accessToken,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            return response()->json([
                'success' => true,
                'message' => 'Conexão estabelecida com sucesso!',
                'username' => $data['username'] ?? null,
                'name' => $data['name'] ?? null,
            ]);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents(), true);
            $errorMessage = $response['error']['message'] ?? 'Erro desconhecido';
            
            return response()->json([
                'success' => false,
                'message' => 'Erro do Instagram: ' . $errorMessage,
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao testar conexão: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Exibe a página de configurações do Telegram.
     */
    public function telegram(): View
    {
        $config = [
            'bot_token' => env('TELEGRAM_BOT_TOKEN', ''),
            'bot_username' => env('TELEGRAM_BOT_USERNAME', ''),
            'webhook_url' => env('TELEGRAM_WEBHOOK_URL', url('/api/telegram/webhook')),
            'webhook_enabled' => env('TELEGRAM_WEBHOOK_ENABLED', false),
        ];

        return view('configuracoes.telegram', compact('config'));
    }

    /**
     * Salva as configurações do Telegram.
     */
    public function telegramSalvar(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'telegram_bot_token' => 'nullable|string|min:40',
            'telegram_bot_username' => 'nullable|string|max:50',
            'telegram_webhook_url' => 'nullable|url',
            'telegram_webhook_enabled' => 'nullable',
        ]);

        $this->updateEnvFile([
            'TELEGRAM_BOT_TOKEN' => $validated['telegram_bot_token'] ?? '',
            'TELEGRAM_BOT_USERNAME' => $validated['telegram_bot_username'] ?? '',
            'TELEGRAM_WEBHOOK_URL' => $validated['telegram_webhook_url'] ?? '',
            'TELEGRAM_WEBHOOK_ENABLED' => $request->has('telegram_webhook_enabled') ? 'true' : 'false',
        ]);

        return redirect()
            ->route('configuracoes.telegram')
            ->with('success', 'Configurações do Telegram salvas com sucesso!');
    }

    /**
     * Testa a conexão com a API do Telegram.
     */
    public function telegramTestar(Request $request)
    {
        $botToken = env('TELEGRAM_BOT_TOKEN');

        if (empty($botToken)) {
            return response()->json([
                'success' => false,
                'message' => 'Bot Token é obrigatório.',
            ], 400);
        }

        try {
            $client = new \GuzzleHttp\Client([
                'timeout' => 10,
                'verify' => false,
            ]);

            $response = $client->get("https://api.telegram.org/bot{$botToken}/getMe");

            $data = json_decode($response->getBody()->getContents(), true);

            if ($data['ok'] ?? false) {
                return response()->json([
                    'success' => true,
                    'message' => 'Conexão estabelecida com sucesso!',
                    'username' => $data['result']['username'] ?? null,
                    'first_name' => $data['result']['first_name'] ?? null,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Resposta inesperada da API do Telegram.',
            ]);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents(), true);
            $errorMessage = $response['description'] ?? 'Erro desconhecido';
            
            return response()->json([
                'success' => false,
                'message' => 'Erro do Telegram: ' . $errorMessage,
            ], 400);

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
