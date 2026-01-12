<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Jobs\SendScheduledMessage;
use App\Models\Instancia;
use App\Models\MensagemAgendada;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller para gerenciamento de mensagens agendadas.
 */
class MensagemAgendadaController extends Controller
{
    /**
     * Lista todas as mensagens agendadas.
     */
    public function index(Request $request): View
    {
        $query = MensagemAgendada::whereHas('instancia', function ($q) {
            $q->where('user_id', auth()->id());
        })->with('instancia');

        // Filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('instancia_id')) {
            $query->where('instancia_id', $request->instancia_id);
        }

        $mensagens = $query->orderBy('agendado_para', 'asc')->paginate(15);

        $instancias = Instancia::where('user_id', auth()->id())->get();

        return view('mensagens.index', compact('mensagens', 'instancias'));
    }

    /**
     * Exibe o formulário de criação.
     */
    public function create(): View
    {
        $instancias = Instancia::where('user_id', auth()->id())
            ->where('status', Instancia::STATUS_ONLINE)
            ->get();

        return view('mensagens.create', compact('instancias'));
    }

    /**
     * Armazena uma nova mensagem agendada.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'instancia_id' => 'required|exists:instancias,id',
            'telefone_destino' => 'required|string|max:20',
            'mensagem' => 'required|string|max:4096',
            'agendado_para' => 'required|date|after:now',
            'midia' => 'nullable|array',
            'midia.url' => 'nullable|url',
            'midia.type' => 'nullable|in:image,video,audio,document',
        ]);

        // Verifica se a instância pertence ao usuário
        $instancia = Instancia::findOrFail($validated['instancia_id']);
        $this->authorize('view', $instancia);

        $mensagem = MensagemAgendada::create([
            'instancia_id' => $validated['instancia_id'],
            'telefone_destino' => $validated['telefone_destino'],
            'mensagem' => $validated['mensagem'],
            'midia' => $validated['midia'] ?? null,
            'agendado_para' => $validated['agendado_para'],
            'status' => MensagemAgendada::STATUS_AGENDADA,
        ]);

        // Agenda o job para envio
        SendScheduledMessage::dispatch($mensagem)
            ->delay($mensagem->agendado_para);

        return redirect()
            ->route('mensagens.index')
            ->with('success', 'Mensagem agendada com sucesso!');
    }

    /**
     * Exibe os detalhes de uma mensagem.
     */
    public function show(MensagemAgendada $mensagem): View
    {
        $this->authorize('view', $mensagem->instancia);

        $mensagem->load('instancia');

        return view('mensagens.show', compact('mensagem'));
    }

    /**
     * Exibe o formulário de edição.
     */
    public function edit(MensagemAgendada $mensagem): View
    {
        $this->authorize('update', $mensagem->instancia);

        // Só pode editar se ainda não foi enviada
        if ($mensagem->status !== MensagemAgendada::STATUS_AGENDADA) {
            abort(403, 'Esta mensagem não pode mais ser editada.');
        }

        $instancias = Instancia::where('user_id', auth()->id())->get();

        return view('mensagens.edit', compact('mensagem', 'instancias'));
    }

    /**
     * Atualiza uma mensagem agendada.
     */
    public function update(Request $request, MensagemAgendada $mensagem): RedirectResponse
    {
        $this->authorize('update', $mensagem->instancia);

        if ($mensagem->status !== MensagemAgendada::STATUS_AGENDADA) {
            return redirect()
                ->route('mensagens.show', $mensagem)
                ->with('error', 'Esta mensagem não pode mais ser editada.');
        }

        $validated = $request->validate([
            'telefone_destino' => 'required|string|max:20',
            'mensagem' => 'required|string|max:4096',
            'agendado_para' => 'required|date|after:now',
            'midia' => 'nullable|array',
        ]);

        $mensagem->update($validated);

        return redirect()
            ->route('mensagens.show', $mensagem)
            ->with('success', 'Mensagem atualizada com sucesso!');
    }

    /**
     * Remove uma mensagem agendada.
     */
    public function destroy(MensagemAgendada $mensagem): RedirectResponse
    {
        $this->authorize('delete', $mensagem->instancia);

        if ($mensagem->status === MensagemAgendada::STATUS_PROCESSANDO) {
            return redirect()
                ->route('mensagens.index')
                ->with('error', 'Não é possível remover uma mensagem em processamento.');
        }

        $mensagem->delete();

        return redirect()
            ->route('mensagens.index')
            ->with('success', 'Mensagem removida com sucesso!');
    }

    /**
     * Cancela uma mensagem agendada.
     */
    public function cancel(MensagemAgendada $mensagem): RedirectResponse
    {
        $this->authorize('update', $mensagem->instancia);

        if ($mensagem->status !== MensagemAgendada::STATUS_AGENDADA) {
            return redirect()
                ->route('mensagens.show', $mensagem)
                ->with('error', 'Esta mensagem não pode ser cancelada.');
        }

        $mensagem->cancel();

        return redirect()
            ->route('mensagens.index')
            ->with('success', 'Mensagem cancelada com sucesso!');
    }

    /**
     * Reenvia uma mensagem que falhou.
     */
    public function retry(MensagemAgendada $mensagem): JsonResponse
    {
        $this->authorize('update', $mensagem->instancia);

        if (!$mensagem->canRetry()) {
            return response()->json([
                'success' => false,
                'error' => 'Esta mensagem não pode ser reenviada.',
            ], 400);
        }

        $mensagem->update([
            'status' => MensagemAgendada::STATUS_AGENDADA,
            'agendado_para' => now()->addMinutes(1),
        ]);

        SendScheduledMessage::dispatch($mensagem)->delay(now()->addMinutes(1));

        return response()->json([
            'success' => true,
            'message' => 'Mensagem reagendada para envio.',
        ]);
    }
}
