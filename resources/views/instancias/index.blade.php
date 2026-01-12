@extends('layouts.app')

@section('title', 'Instâncias - WhatsApp Manager')
@section('header', 'Instâncias WhatsApp')

@section('content')
<div class="space-y-6">
    <!-- Header com botão de criar -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Suas Instâncias</h2>
            <p class="mt-1 text-sm text-gray-500">Gerencie suas conexões do WhatsApp</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('instancias.create') }}" class="inline-flex items-center rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                <i class="fas fa-plus mr-2"></i>
                Nova Instância
            </a>
        </div>
    </div>

    <!-- Grid de instâncias -->
    @if($instancias->count() > 0)
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($instancias as $instancia)
                <div class="overflow-hidden rounded-lg bg-white shadow hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                                        <i class="fab fa-whatsapp text-green-600 text-xl"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $instancia->nome }}</h3>
                                    <p class="text-sm text-gray-500">{{ $instancia->telefone ?? 'Não conectado' }}</p>
                                </div>
                            </div>
                            <div x-data="{ status: '{{ $instancia->status }}', loading: false }"
                                 x-init="setInterval(() => {
                                     if (!loading) {
                                         fetch('{{ route('instancias.status', $instancia) }}')
                                             .then(r => r.json())
                                             .then(data => { status = data.status; });
                                     }
                                 }, 10000)">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium"
                                      :class="{
                                          'bg-green-100 text-green-800': status === 'online',
                                          'bg-yellow-100 text-yellow-800': status === 'connecting',
                                          'bg-blue-100 text-blue-800': status === 'qr_pending',
                                          'bg-red-100 text-red-800': status === 'offline'
                                      }">
                                    <i class="fas fa-circle text-xs mr-1"
                                       :class="{
                                           'text-green-400': status === 'online',
                                           'text-yellow-400': status === 'connecting',
                                           'text-blue-400': status === 'qr_pending',
                                           'text-red-400': status === 'offline'
                                       }"></i>
                                    <span x-text="status === 'online' ? 'Online' : 
                                                  status === 'connecting' ? 'Conectando...' :
                                                  status === 'qr_pending' ? 'Aguardando QR' : 'Offline'"></span>
                                </span>
                            </div>
                        </div>

                        <div class="mt-6 grid grid-cols-2 gap-4 text-center text-sm">
                            <div class="rounded-lg bg-gray-50 p-3">
                                <p class="text-2xl font-semibold text-gray-900">{{ $instancia->logs_de_mensagens_count ?? 0 }}</p>
                                <p class="text-gray-500">Mensagens</p>
                            </div>
                            <div class="rounded-lg bg-gray-50 p-3">
                                <p class="text-2xl font-semibold text-gray-900">{{ $instancia->mensagens_agendadas_count ?? 0 }}</p>
                                <p class="text-gray-500">Agendadas</p>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-between border-t border-gray-200 pt-4">
                            <div class="text-xs text-gray-500">
                                @if($instancia->last_connected_at)
                                    Última conexão: {{ $instancia->last_connected_at->diffForHumans() }}
                                @else
                                    Nunca conectado
                                @endif
                            </div>
                            <div class="flex space-x-2">
                                @if($instancia->status !== 'online')
                                    <form action="{{ route('instancias.connect', $instancia) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="rounded-md bg-green-50 px-3 py-1 text-xs font-medium text-green-600 hover:bg-green-100" title="Conectar">
                                            <i class="fas fa-qrcode"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('instancias.disconnect', $instancia) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="rounded-md bg-red-50 px-3 py-1 text-xs font-medium text-red-600 hover:bg-red-100" title="Desconectar">
                                            <i class="fas fa-power-off"></i>
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('instancias.show', $instancia) }}" class="rounded-md bg-gray-50 px-3 py-1 text-xs font-medium text-gray-600 hover:bg-gray-100" title="Detalhes">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('instancias.edit', $instancia) }}" class="rounded-md bg-blue-50 px-3 py-1 text-xs font-medium text-blue-600 hover:bg-blue-100" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginação -->
        <div class="mt-6">
            {{ $instancias->links() }}
        </div>
    @else
        <!-- Estado vazio -->
        <div class="text-center py-12 bg-white rounded-lg shadow">
            <i class="fab fa-whatsapp text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900">Nenhuma instância criada</h3>
            <p class="mt-2 text-sm text-gray-500">Comece criando sua primeira instância do WhatsApp.</p>
            <div class="mt-6">
                <a href="{{ route('instancias.create') }}" class="inline-flex items-center rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500">
                    <i class="fas fa-plus mr-2"></i>
                    Criar Instância
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
