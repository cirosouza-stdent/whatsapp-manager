@extends('layouts.app')

@section('title', 'Detalhes da Mensagem - WhatsApp Manager')
@section('header', 'Detalhes da Mensagem')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('mensagens.index') }}" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-calendar-alt mr-1"></i>
                    Mensagens
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                    <span class="text-gray-700 font-medium">Detalhes</span>
                </div>
            </li>
        </ol>
    </nav>

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

    <!-- Card principal -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-full 
                        {{ $mensagem->status === 'enviada' ? 'bg-green-100' : '' }}
                        {{ $mensagem->status === 'agendada' ? 'bg-blue-100' : '' }}
                        {{ $mensagem->status === 'processando' ? 'bg-yellow-100' : '' }}
                        {{ $mensagem->status === 'erro' ? 'bg-red-100' : '' }}
                        {{ $mensagem->status === 'cancelada' ? 'bg-gray-100' : '' }}">
                        <i class="fas text-xl
                            {{ $mensagem->status === 'enviada' ? 'fa-check text-green-600' : '' }}
                            {{ $mensagem->status === 'agendada' ? 'fa-clock text-blue-600' : '' }}
                            {{ $mensagem->status === 'processando' ? 'fa-spinner fa-spin text-yellow-600' : '' }}
                            {{ $mensagem->status === 'erro' ? 'fa-times text-red-600' : '' }}
                            {{ $mensagem->status === 'cancelada' ? 'fa-ban text-gray-600' : '' }}"></i>
                    </span>
                </div>
                <div class="ml-4">
                    <h2 class="text-xl font-bold text-gray-900">{{ $mensagem->telefone_destino }}</h2>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                        {{ $mensagem->status === 'enviada' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $mensagem->status === 'agendada' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $mensagem->status === 'processando' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $mensagem->status === 'erro' ? 'bg-red-100 text-red-800' : '' }}
                        {{ $mensagem->status === 'cancelada' ? 'bg-gray-100 text-gray-800' : '' }}">
                        {{ ucfirst($mensagem->status) }}
                    </span>
                </div>
            </div>
            <div class="mt-4 sm:mt-0 flex flex-wrap gap-2">
                @if($mensagem->status === 'agendada')
                    <a href="{{ route('mensagens.edit', $mensagem) }}" 
                       class="inline-flex items-center rounded-md bg-gray-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500 transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        Editar
                    </a>
                    <form action="{{ route('mensagens.cancel', $mensagem) }}" method="POST" class="inline"
                          onsubmit="return confirm('Tem certeza que deseja cancelar esta mensagem?')">
                        @csrf
                        <button type="submit" class="inline-flex items-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 transition-colors">
                            <i class="fas fa-ban mr-2"></i>
                            Cancelar
                        </button>
                    </form>
                @endif
                @if($mensagem->status === 'erro')
                    <form action="{{ route('mensagens.retry', $mensagem) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 transition-colors">
                            <i class="fas fa-redo mr-2"></i>
                            Retentar Envio
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="px-4 py-5 sm:p-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Instância</dt>
                    <dd class="mt-1 text-sm text-gray-900 flex items-center">
                        <i class="fab fa-whatsapp text-green-500 mr-2"></i>
                        <a href="{{ route('instancias.show', $mensagem->instancia) }}" class="hover:text-green-600">
                            {{ $mensagem->instancia->nome }}
                        </a>
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Telefone de Destino</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <i class="fas fa-phone mr-2 text-gray-400"></i>
                        {{ $mensagem->telefone_destino }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Agendado para</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <i class="fas fa-calendar mr-2 text-gray-400"></i>
                        {{ $mensagem->agendado_para->format('d/m/Y \à\s H:i') }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Tentativas</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $mensagem->tentativas }} / 3
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Criado em</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $mensagem->created_at->format('d/m/Y H:i') }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Atualizado em</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $mensagem->updated_at->format('d/m/Y H:i') }}
                    </dd>
                </div>

                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Mensagem</dt>
                    <dd class="mt-1 text-sm text-gray-900 bg-gray-50 p-4 rounded-md whitespace-pre-wrap">{{ $mensagem->mensagem }}</dd>
                </div>

                @if($mensagem->midia)
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Mídia Anexada</dt>
                        <dd class="mt-1 text-sm text-gray-900 bg-gray-50 p-4 rounded-md">
                            <div class="flex items-center">
                                <i class="fas 
                                    {{ $mensagem->midia['type'] === 'image' ? 'fa-image' : '' }}
                                    {{ $mensagem->midia['type'] === 'video' ? 'fa-video' : '' }}
                                    {{ $mensagem->midia['type'] === 'audio' ? 'fa-music' : '' }}
                                    {{ $mensagem->midia['type'] === 'document' ? 'fa-file' : '' }}
                                    text-gray-400 mr-2"></i>
                                <span class="font-medium mr-2">{{ ucfirst($mensagem->midia['type'] ?? 'Mídia') }}:</span>
                                <a href="{{ $mensagem->midia['url'] ?? '#' }}" target="_blank" class="text-green-600 hover:text-green-800 truncate">
                                    {{ $mensagem->midia['url'] ?? 'URL não disponível' }}
                                </a>
                            </div>
                        </dd>
                    </div>
                @endif

                @if($mensagem->erro_detalhes)
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-red-500">Detalhes do Erro</dt>
                        <dd class="mt-1 text-sm text-red-700 bg-red-50 p-4 rounded-md">
                            {{ $mensagem->erro_detalhes }}
                        </dd>
                    </div>
                @endif
            </dl>
        </div>
    </div>

    <!-- Botão voltar -->
    <div class="mt-4">
        <a href="{{ route('mensagens.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-1"></i>
            Voltar para lista de mensagens
        </a>
    </div>
</div>
@endsection
