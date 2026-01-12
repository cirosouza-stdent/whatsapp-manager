@extends('layouts.app')

@section('title', 'Mensagens Agendadas - WhatsApp Manager')
@section('header', 'Mensagens Agendadas')

@section('content')
<div>
    <!-- Mensagens de feedback -->
    @if (session('success'))
        <div class="mb-4 rounded-md bg-green-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 rounded-md bg-red-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-times-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Cabeçalho com ações -->
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h2 class="text-lg font-medium text-gray-900">Suas Mensagens Agendadas</h2>
            <p class="mt-1 text-sm text-gray-500">Gerencie mensagens para envio automático.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('mensagens.create') }}" 
               class="inline-flex items-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Nova Mensagem
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-4 sm:px-6">
            <form action="{{ route('mensagens.index') }}" method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="status" class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                    <select name="status" id="status" 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2 px-3 border">
                        <option value="">Todos</option>
                        <option value="agendada" {{ request('status') === 'agendada' ? 'selected' : '' }}>Agendada</option>
                        <option value="processando" {{ request('status') === 'processando' ? 'selected' : '' }}>Processando</option>
                        <option value="enviada" {{ request('status') === 'enviada' ? 'selected' : '' }}>Enviada</option>
                        <option value="erro" {{ request('status') === 'erro' ? 'selected' : '' }}>Erro</option>
                        <option value="cancelada" {{ request('status') === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="instancia_id" class="block text-xs font-medium text-gray-500 mb-1">Instância</label>
                    <select name="instancia_id" id="instancia_id" 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2 px-3 border">
                        <option value="">Todas</option>
                        @foreach($instancias as $instancia)
                            <option value="{{ $instancia->id }}" {{ request('instancia_id') == $instancia->id ? 'selected' : '' }}>
                                {{ $instancia->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="inline-flex items-center rounded-md bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 transition-colors">
                        <i class="fas fa-filter mr-2"></i>
                        Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de mensagens -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        @if($mensagens->count() > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($mensagens as $mensagem)
                    <li class="px-4 py-4 sm:px-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center min-w-0 flex-1">
                                <div class="flex-shrink-0">
                                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full 
                                        {{ $mensagem->status === 'enviada' ? 'bg-green-100' : '' }}
                                        {{ $mensagem->status === 'agendada' ? 'bg-blue-100' : '' }}
                                        {{ $mensagem->status === 'processando' ? 'bg-yellow-100' : '' }}
                                        {{ $mensagem->status === 'erro' ? 'bg-red-100' : '' }}
                                        {{ $mensagem->status === 'cancelada' ? 'bg-gray-100' : '' }}">
                                        <i class="fas 
                                            {{ $mensagem->status === 'enviada' ? 'fa-check text-green-600' : '' }}
                                            {{ $mensagem->status === 'agendada' ? 'fa-clock text-blue-600' : '' }}
                                            {{ $mensagem->status === 'processando' ? 'fa-spinner fa-spin text-yellow-600' : '' }}
                                            {{ $mensagem->status === 'erro' ? 'fa-times text-red-600' : '' }}
                                            {{ $mensagem->status === 'cancelada' ? 'fa-ban text-gray-600' : '' }}"></i>
                                    </span>
                                </div>
                                <div class="ml-4 min-w-0 flex-1">
                                    <div class="flex items-center gap-2">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $mensagem->telefone_destino }}
                                        </p>
                                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                            {{ $mensagem->status === 'enviada' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $mensagem->status === 'agendada' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $mensagem->status === 'processando' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $mensagem->status === 'erro' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ $mensagem->status === 'cancelada' ? 'bg-gray-100 text-gray-800' : '' }}">
                                            {{ ucfirst($mensagem->status) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-500 truncate mt-1">
                                        {{ Str::limit($mensagem->mensagem, 80) }}
                                    </p>
                                    <div class="flex items-center gap-4 mt-1">
                                        <span class="text-xs text-gray-400">
                                            <i class="fab fa-whatsapp mr-1"></i>
                                            {{ $mensagem->instancia->nome }}
                                        </span>
                                        <span class="text-xs text-gray-400">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ $mensagem->agendado_para->format('d/m/Y H:i') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="ml-4 flex items-center gap-2">
                                <a href="{{ route('mensagens.show', $mensagem) }}" 
                                   class="text-gray-400 hover:text-gray-600" title="Ver detalhes">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($mensagem->status === 'agendada')
                                    <a href="{{ route('mensagens.edit', $mensagem) }}" 
                                       class="text-gray-400 hover:text-gray-600" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('mensagens.cancel', $mensagem) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Tem certeza que deseja cancelar esta mensagem?')">
                                        @csrf
                                        <button type="submit" class="text-gray-400 hover:text-red-600" title="Cancelar">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </form>
                                @endif
                                @if($mensagem->status === 'erro')
                                    <form action="{{ route('mensagens.retry', $mensagem) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-gray-400 hover:text-green-600" title="Retentar">
                                            <i class="fas fa-redo"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>

            <!-- Paginação -->
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $mensagens->appends(request()->query())->links() }}
            </div>
        @else
            <div class="px-4 py-12 text-center text-gray-500">
                <i class="fas fa-calendar-alt text-4xl mb-3"></i>
                <h3 class="text-sm font-medium text-gray-900">Nenhuma mensagem agendada</h3>
                <p class="mt-1 text-sm text-gray-500">Comece agendando sua primeira mensagem.</p>
                <div class="mt-6">
                    <a href="{{ route('mensagens.create') }}" 
                       class="inline-flex items-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Agendar Mensagem
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
