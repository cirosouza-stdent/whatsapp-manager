<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Instancia;
use App\Models\LogDeMensagem;
use App\Models\MensagemAgendada;
use Illuminate\View\View;

/**
 * Controller para o Dashboard principal.
 */
class DashboardController extends Controller
{
    /**
     * Exibe o dashboard principal.
     */
    public function index(): View
    {
        $userId = auth()->id();

        // Estatísticas gerais
        $stats = [
            'total_instancias' => Instancia::where('user_id', $userId)->count(),
            'instancias_online' => Instancia::where('user_id', $userId)
                ->where('status', Instancia::STATUS_ONLINE)
                ->count(),
            'instancias_offline' => Instancia::where('user_id', $userId)
                ->where('status', Instancia::STATUS_OFFLINE)
                ->count(),
            'mensagens_enviadas' => LogDeMensagem::whereHas('instancia', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->where('tipo', LogDeMensagem::TIPO_ENVIADA)->count(),
            'mensagens_recebidas' => LogDeMensagem::whereHas('instancia', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->where('tipo', LogDeMensagem::TIPO_RECEBIDA)->count(),
            'mensagens_agendadas' => MensagemAgendada::whereHas('instancia', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->where('status', MensagemAgendada::STATUS_AGENDADA)->count(),
        ];

        // Últimas instâncias
        $instancias = Instancia::where('user_id', $userId)
            ->latest()
            ->limit(5)
            ->get();

        // Últimos logs
        $ultimosLogs = LogDeMensagem::whereHas('instancia', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->with('instancia')
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard', compact('stats', 'instancias', 'ultimosLogs'));
    }
}
