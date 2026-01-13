<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\TelegramBot;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use GuzzleHttp\Client;

/**
 * Controller para gerenciamento do Telegram.
 */
class TelegramController extends Controller
{
    /**
     * Dashboard principal do Telegram.
     */
    public function index(): View
    {
        $configurado = !empty(env('TELEGRAM_BOT_TOKEN')) || TelegramBot::where('user_id', auth()->id())->exists();

        $stats = [
            'bots' => TelegramBot::where('user_id', auth()->id())->count(),
            'canais' => 0,
            'mensagens_enviadas' => 0,
            'membros' => 0,
        ];

        return view('telegram.index', compact('configurado', 'stats'));
    }

    /**
     * Lista os bots configurados.
     */
    public function bots(): View
    {
        $bots = TelegramBot::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('telegram.bots', compact('bots'));
    }

    /**
     * Formulário para adicionar bot.
     */
    public function criarBot(): View
    {
        return view('telegram.criar-bot');
    }

    /**
     * Salva um novo bot.
     */
    public function salvarBot(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'token' => 'required|string|min:40|unique:telegram_bots,token',
        ]);

        // Validar token com a API do Telegram
        try {
            $client = new Client([
                'timeout' => 10,
                'verify' => false,
            ]);

            $response = $client->get("https://api.telegram.org/bot{$validated['token']}/getMe");
            $data = json_decode($response->getBody()->getContents(), true);

            if (!($data['ok'] ?? false)) {
                return back()
                    ->withInput()
                    ->withErrors(['token' => 'Token inválido. Verifique e tente novamente.']);
            }

            $botInfo = $data['result'];

            TelegramBot::create([
                'user_id' => auth()->id(),
                'nome' => $botInfo['first_name'] ?? 'Bot',
                'username' => $botInfo['username'] ?? '',
                'token' => $validated['token'],
                'bot_id' => (string) ($botInfo['id'] ?? ''),
                'descricao' => null,
                'ativo' => true,
            ]);

            return redirect()
                ->route('telegram.bots')
                ->with('success', 'Bot adicionado com sucesso!');

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return back()
                ->withInput()
                ->withErrors(['token' => 'Token inválido ou expirado.']);

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['token' => 'Erro ao validar token: ' . $e->getMessage()]);
        }
    }

    /**
     * Alterna o status ativo/inativo do bot.
     */
    public function toggleBot(TelegramBot $bot): RedirectResponse
    {
        $this->authorize('update', $bot);

        $bot->update(['ativo' => !$bot->ativo]);

        $status = $bot->ativo ? 'ativado' : 'desativado';

        return back()->with('success', "Bot {$status} com sucesso!");
    }

    /**
     * Remove um bot.
     */
    public function excluirBot(TelegramBot $bot): RedirectResponse
    {
        $this->authorize('delete', $bot);

        $bot->delete();

        return redirect()
            ->route('telegram.bots')
            ->with('success', 'Bot removido com sucesso!');
    }

    /**
     * Lista os canais/grupos.
     */
    public function canais(): View
    {
        $canais = [];

        return view('telegram.canais', compact('canais'));
    }

    /**
     * Lista as mensagens.
     */
    public function mensagens(): View
    {
        $mensagens = [];

        return view('telegram.mensagens', compact('mensagens'));
    }

    /**
     * Formulário para enviar mensagem.
     */
    public function criarMensagem(): View
    {
        $canais = [];
        $bots = TelegramBot::where('user_id', auth()->id())
            ->where('ativo', true)
            ->get();

        return view('telegram.criar-mensagem', compact('canais', 'bots'));
    }

    /**
     * Envia uma mensagem.
     */
    public function enviarMensagem(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'bot_id' => 'required|exists:telegram_bots,id',
            'destino' => 'required|string',
            'mensagem' => 'required|string|max:4096',
            'parse_mode' => 'nullable|in:Markdown,HTML',
        ]);

        $bot = TelegramBot::findOrFail($validated['bot_id']);

        try {
            $client = new Client([
                'timeout' => 10,
                'verify' => false,
            ]);

            $params = [
                'chat_id' => $validated['destino'],
                'text' => $validated['mensagem'],
            ];

            if (!empty($validated['parse_mode'])) {
                $params['parse_mode'] = $validated['parse_mode'];
            }

            if ($request->has('disable_notification')) {
                $params['disable_notification'] = true;
            }

            $response = $client->post("https://api.telegram.org/bot{$bot->token}/sendMessage", [
                'json' => $params,
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if ($data['ok'] ?? false) {
                $bot->update(['ultimo_uso' => now()]);

                return redirect()
                    ->route('telegram.mensagens')
                    ->with('success', 'Mensagem enviada com sucesso!');
            }

            return back()
                ->withInput()
                ->withErrors(['mensagem' => 'Erro ao enviar mensagem.']);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents(), true);
            $errorMessage = $response['description'] ?? 'Erro desconhecido';

            return back()
                ->withInput()
                ->withErrors(['mensagem' => 'Erro do Telegram: ' . $errorMessage]);

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['mensagem' => 'Erro ao enviar: ' . $e->getMessage()]);
        }
    }
}
