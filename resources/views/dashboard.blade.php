@extends('layouts.app')

@section('title', 'Dashboard - WhatsApp Manager')
@section('header', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Estatísticas -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <!-- Total de Instâncias -->
        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="rounded-md bg-green-500 p-3">
                        <i class="fab fa-whatsapp text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="truncate text-sm font-medium text-gray-500">Total de Instâncias</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ $stats['total_instancias'] }}</dd>
                    </dl>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center text-sm">
                    <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                        <i class="fas fa-circle text-green-400 text-xs mr-1"></i>
                        {{ $stats['instancias_online'] }} online
                    </span>
                    <span class="ml-2 inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                        <i class="fas fa-circle text-red-400 text-xs mr-1"></i>
                        {{ $stats['instancias_offline'] }} offline
                    </span>
                </div>
            </div>
        </div>

        <!-- Mensagens Enviadas -->
        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="rounded-md bg-blue-500 p-3">
                        <i class="fas fa-paper-plane text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="truncate text-sm font-medium text-gray-500">Mensagens Enviadas</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ $stats['mensagens_enviadas'] }}</dd>
                    </dl>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-500">
                <i class="fas fa-inbox mr-1"></i>
                {{ $stats['mensagens_recebidas'] }} recebidas
            </div>
        </div>

        <!-- Mensagens Agendadas -->
        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="rounded-md bg-yellow-500 p-3">
                        <i class="fas fa-clock text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="truncate text-sm font-medium text-gray-500">Agendadas</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ $stats['mensagens_agendadas'] }}</dd>
                    </dl>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('mensagens.create') }}" class="text-sm font-medium text-green-600 hover:text-green-500">
                    <i class="fas fa-plus mr-1"></i> Agendar nova mensagem
                </a>
            </div>
        </div>
    </div>

    <!-- Grid principal -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Instâncias -->
        <div class="overflow-hidden rounded-lg bg-white shadow">
            <div class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-semibold leading-6 text-gray-900">
                        <i class="fab fa-whatsapp text-green-500 mr-2"></i>
                        Suas Instâncias
                    </h3>
                    <a href="{{ route('instancias.create') }}" class="inline-flex items-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500">
                        <i class="fas fa-plus mr-1"></i> Nova
                    </a>
                </div>
            </div>
            <ul role="list" class="divide-y divide-gray-200">
                @forelse($instancias as $instancia)
                    <li class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                        <a href="{{ route('instancias.show', $instancia) }}" class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-green-100">
                                        <i class="fab fa-whatsapp text-green-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $instancia->nome }}</p>
                                    <p class="text-sm text-gray-500">{{ $instancia->telefone ?? 'Não conectado' }}</p>
                                </div>
                            </div>
                            <div>
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                    @if($instancia->status === 'online') bg-green-100 text-green-800
                                    @elseif($instancia->status === 'connecting') bg-yellow-100 text-yellow-800
                                    @elseif($instancia->status === 'qr_pending') bg-blue-100 text-blue-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    <i class="fas fa-circle text-xs mr-1
                                        @if($instancia->status === 'online') text-green-400
                                        @elseif($instancia->status === 'connecting') text-yellow-400
                                        @elseif($instancia->status === 'qr_pending') text-blue-400
                                        @else text-red-400
                                        @endif"></i>
                                    {{ $instancia->status_text }}
                                </span>
                            </div>
                        </a>
                    </li>
                @empty
                    <li class="px-4 py-8 text-center">
                        <i class="fab fa-whatsapp text-gray-300 text-4xl mb-3"></i>
                        <p class="text-sm text-gray-500">Nenhuma instância criada</p>
                        <a href="{{ route('instancias.create') }}" class="mt-2 inline-flex items-center text-sm font-medium text-green-600 hover:text-green-500">
                            <i class="fas fa-plus mr-1"></i> Criar primeira instância
                        </a>
                    </li>
                @endforelse
            </ul>
            @if($instancias->count() > 0)
                <div class="border-t border-gray-200 bg-gray-50 px-4 py-3">
                    <a href="{{ route('instancias.index') }}" class="text-sm font-medium text-green-600 hover:text-green-500">
                        Ver todas as instâncias <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            @endif
        </div>

        <!-- Últimas Mensagens -->
        <div class="overflow-hidden rounded-lg bg-white shadow">
            <div class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6">
                <h3 class="text-base font-semibold leading-6 text-gray-900">
                    <i class="fas fa-history text-blue-500 mr-2"></i>
                    Últimas Mensagens
                </h3>
            </div>
            <ul role="list" class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                @forelse($ultimosLogs as $log)
                    <li class="px-4 py-3 sm:px-6">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                @if($log->tipo === 'enviada')
                                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-blue-100">
                                        <i class="fas fa-paper-plane text-blue-600 text-xs"></i>
                                    </span>
                                @elseif($log->tipo === 'recebida')
                                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-green-100">
                                        <i class="fas fa-inbox text-green-600 text-xs"></i>
                                    </span>
                                @else
                                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-red-100">
                                        <i class="fas fa-exclamation-triangle text-red-600 text-xs"></i>
                                    </span>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm text-gray-900 truncate">{{ Str::limit($log->mensagem, 50) }}</p>
                                <div class="mt-1 flex items-center space-x-2 text-xs text-gray-500">
                                    <span>{{ $log->telefone_destino }}</span>
                                    <span>•</span>
                                    <span>{{ $log->instancia->nome ?? 'N/A' }}</span>
                                    <span>•</span>
                                    <span>{{ $log->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div>
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                    @if($log->status === 'lida') bg-blue-100 text-blue-800
                                    @elseif($log->status === 'entregue') bg-green-100 text-green-800
                                    @elseif($log->status === 'enviada') bg-indigo-100 text-indigo-800
                                    @elseif($log->status === 'pendente') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($log->status) }}
                                </span>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="px-4 py-8 text-center">
                        <i class="fas fa-comments text-gray-300 text-4xl mb-3"></i>
                        <p class="text-sm text-gray-500">Nenhuma mensagem registrada</p>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>

    <!-- Dica de configuração -->
    @if($stats['total_instancias'] === 0)
        <div class="rounded-md bg-blue-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Primeiros passos</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>Para começar a usar o sistema, você precisa:</p>
                        <ol class="list-decimal ml-5 mt-1 space-y-1">
                            <li>Criar uma nova instância do WhatsApp</li>
                            <li>Escanear o QR Code com seu celular</li>
                            <li>Aguardar a conexão ser estabelecida</li>
                        </ol>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('instancias.create') }}" class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">
                            <i class="fas fa-plus mr-2"></i>
                            Criar primeira instância
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
