@extends('layouts.app')

@section('title', 'Telegram - WhatsApp Manager')
@section('header', 'Telegram')

@section('content')
<div>
    @if(!$configurado)
        <div class="mb-6 rounded-md bg-yellow-50 border border-yellow-200 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Configuração Necessária</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>O Telegram ainda não está configurado. Configure o Token do Bot para começar a usar.</p>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('configuracoes.telegram') }}" 
                           class="inline-flex items-center rounded-md bg-yellow-100 px-3 py-2 text-sm font-semibold text-yellow-800 hover:bg-yellow-200 transition-colors">
                            <i class="fas fa-cog mr-2"></i>
                            Configurar Telegram
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Cards de estatísticas -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-md bg-blue-500 p-3">
                            <i class="fab fa-telegram text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Bots Ativos</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $stats['bots'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <a href="{{ route('telegram.bots') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                    Ver bots <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-md bg-cyan-500 p-3">
                            <i class="fas fa-broadcast-tower text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Canais</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $stats['canais'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <a href="{{ route('telegram.canais') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                    Ver canais <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-md bg-green-500 p-3">
                            <i class="fas fa-paper-plane text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Mensagens Enviadas</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $stats['mensagens_enviadas'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <span class="text-sm text-gray-500">Últimos 30 dias</span>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-md bg-purple-500 p-3">
                            <i class="fas fa-users text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Membros</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ number_format($stats['membros']) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <span class="text-sm text-gray-500">Total em canais</span>
            </div>
        </div>
    </div>

    <!-- Ações rápidas -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Ações Rápidas</h3>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <a href="{{ route('telegram.criar-mensagem') }}" 
                   class="flex items-center justify-center px-4 py-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition-colors group {{ !$configurado ? 'opacity-50 pointer-events-none' : '' }}">
                    <div class="text-center">
                        <i class="fas fa-paper-plane text-3xl text-gray-400 group-hover:text-blue-500 mb-2"></i>
                        <p class="text-sm font-medium text-gray-900">Enviar Mensagem</p>
                        <p class="text-xs text-gray-500">Para canal ou grupo</p>
                    </div>
                </a>

                <a href="{{ route('telegram.bots') }}" 
                   class="flex items-center justify-center px-4 py-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition-colors group {{ !$configurado ? 'opacity-50 pointer-events-none' : '' }}">
                    <div class="text-center">
                        <i class="fas fa-robot text-3xl text-gray-400 group-hover:text-blue-500 mb-2"></i>
                        <p class="text-sm font-medium text-gray-900">Gerenciar Bots</p>
                        <p class="text-xs text-gray-500">Configurar comandos</p>
                    </div>
                </a>

                <a href="{{ route('telegram.canais') }}" 
                   class="flex items-center justify-center px-4 py-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition-colors group {{ !$configurado ? 'opacity-50 pointer-events-none' : '' }}">
                    <div class="text-center">
                        <i class="fas fa-broadcast-tower text-3xl text-gray-400 group-hover:text-blue-500 mb-2"></i>
                        <p class="text-sm font-medium text-gray-900">Canais</p>
                        <p class="text-xs text-gray-500">Gerenciar canais</p>
                    </div>
                </a>

                <a href="{{ route('configuracoes.telegram') }}" 
                   class="flex items-center justify-center px-4 py-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition-colors group">
                    <div class="text-center">
                        <i class="fas fa-cog text-3xl text-gray-400 group-hover:text-blue-500 mb-2"></i>
                        <p class="text-sm font-medium text-gray-900">Configurações</p>
                        <p class="text-xs text-gray-500">Token do Bot</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Mensagens recentes -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900">Mensagens Recentes</h3>
            <a href="{{ route('telegram.mensagens') }}" class="text-sm text-blue-600 hover:text-blue-800">
                Ver todas <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="px-4 py-12 text-center text-gray-500">
            <i class="fab fa-telegram text-4xl mb-3 text-gray-300"></i>
            <h4 class="text-sm font-medium text-gray-900">Nenhuma mensagem ainda</h4>
            <p class="mt-1 text-sm text-gray-500">Comece enviando uma mensagem para um canal ou grupo.</p>
            @if($configurado)
                <div class="mt-4">
                    <a href="{{ route('telegram.criar-mensagem') }}" 
                       class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition-colors">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Enviar Mensagem
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
