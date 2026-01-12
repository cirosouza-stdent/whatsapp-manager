<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Instancia;
use App\Services\WhatsAppService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/**
 * Controller para gerenciamento de instâncias WhatsApp.
 */
class InstanciaController extends Controller
{
    /**
     * Serviço de integração WhatsApp.
     */
    protected WhatsAppService $whatsAppService;

    /**
     * Construtor do controller.
     */
    public function __construct(WhatsAppService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }

    /**
     * Lista todas as instâncias do usuário.
     */
    public function index(): View
    {
        $instancias = Instancia::where('user_id', auth()->id())
            ->withCount(['logsDeMensagens', 'mensagensAgendadas'])
            ->latest()
            ->paginate(10);

        return view('instancias.index', compact('instancias'));
    }

    /**
     * Exibe o formulário de criação.
     */
    public function create(): View
    {
        return view('instancias.create');
    }

    /**
     * Armazena uma nova instância.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:100',
            'telefone' => 'nullable|string|max:20',
        ]);

        $instancia = Instancia::create([
            'user_id' => auth()->id(),
            'nome' => $validated['nome'],
            'telefone' => $validated['telefone'] ?? null,
            'status' => Instancia::STATUS_OFFLINE,
        ]);

        // Cria a instância na API
        $result = $this->whatsAppService->createInstance($instancia);

        if ($result['success']) {
            return redirect()
                ->route('instancias.show', $instancia)
                ->with('success', 'Instância criada com sucesso! Escaneie o QR Code para conectar.');
        }

        return redirect()
            ->route('instancias.show', $instancia)
            ->with('warning', 'Instância criada, mas houve um erro ao conectar com a API: ' . ($result['error'] ?? 'Erro desconhecido'));
    }

    /**
     * Exibe os detalhes de uma instância.
     */
    public function show(Instancia $instancia): View
    {
        $this->authorize('view', $instancia);

        $instancia->load(['logsDeMensagens' => function ($query) {
            $query->latest()->limit(20);
        }]);

        // Atualiza o status da conexão
        $this->whatsAppService->getConnectionStatus($instancia);

        return view('instancias.show', compact('instancia'));
    }

    /**
     * Exibe o formulário de edição.
     */
    public function edit(Instancia $instancia): View
    {
        $this->authorize('update', $instancia);

        return view('instancias.edit', compact('instancia'));
    }

    /**
     * Atualiza uma instância.
     */
    public function update(Request $request, Instancia $instancia): RedirectResponse
    {
        $this->authorize('update', $instancia);

        $validated = $request->validate([
            'nome' => 'required|string|max:100',
            'telefone' => 'nullable|string|max:20',
            'configuracoes' => 'nullable|array',
        ]);

        $instancia->update($validated);

        return redirect()
            ->route('instancias.show', $instancia)
            ->with('success', 'Instância atualizada com sucesso!');
    }

    /**
     * Remove uma instância.
     */
    public function destroy(Instancia $instancia): RedirectResponse
    {
        $this->authorize('delete', $instancia);

        // Desconecta da API antes de deletar
        $this->whatsAppService->disconnect($instancia);

        $instancia->delete();

        return redirect()
            ->route('instancias.index')
            ->with('success', 'Instância removida com sucesso!');
    }

    /**
     * Obtém o QR Code para conexão.
     */
    public function qrCode(Instancia $instancia): View|JsonResponse
    {
        $this->authorize('view', $instancia);

        $result = $this->whatsAppService->getQrCode($instancia);

        if (request()->wantsJson()) {
            return response()->json($result);
        }

        return view('instancias.qrcode', [
            'instancia' => $instancia,
            'qrCode' => $result['success'] ? $result['qr_code'] : null,
            'error' => $result['success'] ? null : $result['error'],
        ]);
    }

    /**
     * Gera QR Code local a partir de string.
     */
    public function generateQrCode(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'data' => 'required|string',
            'size' => 'nullable|integer|min:100|max:500',
        ]);

        try {
            $qrCode = QrCode::format('svg')
                ->size($validated['size'] ?? 300)
                ->errorCorrection('H')
                ->generate($validated['data']);

            return response()->json([
                'success' => true,
                'qr_code' => base64_encode($qrCode),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Verifica o status da conexão.
     */
    public function status(Instancia $instancia): JsonResponse
    {
        $this->authorize('view', $instancia);

        $result = $this->whatsAppService->getConnectionStatus($instancia);

        return response()->json([
            'success' => $result['success'],
            'status' => $instancia->fresh()->status,
            'status_text' => $instancia->fresh()->status_text,
            'status_color' => $instancia->fresh()->status_badge_color,
        ]);
    }

    /**
     * Conecta a instância (gera novo QR Code).
     */
    public function connect(Instancia $instancia): RedirectResponse
    {
        $this->authorize('update', $instancia);

        $result = $this->whatsAppService->createInstance($instancia);

        if ($result['success']) {
            return redirect()
                ->route('instancias.qrcode', $instancia)
                ->with('success', 'QR Code gerado! Escaneie para conectar.');
        }

        return redirect()
            ->route('instancias.show', $instancia)
            ->with('error', 'Erro ao conectar: ' . ($result['error'] ?? 'Erro desconhecido'));
    }

    /**
     * Desconecta a instância.
     */
    public function disconnect(Instancia $instancia): RedirectResponse
    {
        $this->authorize('update', $instancia);

        $result = $this->whatsAppService->disconnect($instancia);

        return redirect()
            ->route('instancias.show', $instancia)
            ->with($result['success'] ? 'success' : 'error', $result['message'] ?? $result['error']);
    }

    /**
     * Reinicia a instância.
     */
    public function restart(Instancia $instancia): RedirectResponse
    {
        $this->authorize('update', $instancia);

        $result = $this->whatsAppService->restart($instancia);

        return redirect()
            ->route('instancias.show', $instancia)
            ->with($result['success'] ? 'success' : 'error', $result['message'] ?? $result['error']);
    }

    /**
     * Envia uma mensagem de teste.
     */
    public function sendTest(Request $request, Instancia $instancia): JsonResponse
    {
        $this->authorize('update', $instancia);

        $validated = $request->validate([
            'telefone' => 'required|string|max:20',
            'mensagem' => 'required|string|max:4096',
        ]);

        $result = $this->whatsAppService->sendTextMessage(
            $instancia,
            $validated['telefone'],
            $validated['mensagem']
        );

        return response()->json($result);
    }
}
