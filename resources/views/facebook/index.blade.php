@extends('layouts.app')

@section('title', 'Facebook - WhatsApp Manager')
@section('header', 'Facebook')

@section('content')
<div>
    @if(!$configurado)
        <!-- Alerta de configuração -->
        <div class="mb-6 rounded-md bg-yellow-50 border border-yellow-200 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Configuração Necessária</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>O Facebook ainda não está configurado. Configure as credenciais da API para começar a usar.</p>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('configuracoes.facebook') }}" 
                           class="inline-flex items-center rounded-md bg-yellow-100 px-3 py-2 text-sm font-semibold text-yellow-800 hover:bg-yellow-200 transition-colors">
                            <i class="fas fa-cog mr-2"></i>
                            Configurar Facebook
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Cards de estatísticas -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-6">
        <!-- Páginas Conectadas -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-md bg-blue-500 p-3">
                            <i class="fab fa-facebook-f text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Páginas Conectadas</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $stats['paginas'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <a href="{{ route('facebook.paginas') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                    Ver páginas <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Posts Agendados -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-md bg-yellow-500 p-3">
                            <i class="fas fa-clock text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Posts Agendados</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $stats['posts_agendados'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <a href="{{ route('facebook.posts') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                    Ver posts <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Posts Publicados -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-md bg-green-500 p-3">
                            <i class="fas fa-check-circle text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Posts Publicados</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $stats['posts_publicados'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <span class="text-sm text-gray-500">Últimos 30 dias</span>
            </div>
        </div>

        <!-- Engajamento -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-md bg-purple-500 p-3">
                            <i class="fas fa-heart text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Engajamento</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $stats['engajamento'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <span class="text-sm text-gray-500">Curtidas + Comentários</span>
            </div>
        </div>
    </div>

    <!-- Ações rápidas -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Ações Rápidas</h3>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <a href="{{ route('facebook.criar-post') }}" 
                   class="flex items-center justify-center px-4 py-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition-colors group {{ !$configurado ? 'opacity-50 pointer-events-none' : '' }}">
                    <div class="text-center">
                        <i class="fas fa-plus-circle text-3xl text-gray-400 group-hover:text-blue-500 mb-2"></i>
                        <p class="text-sm font-medium text-gray-900">Criar Post</p>
                        <p class="text-xs text-gray-500">Publicar ou agendar</p>
                    </div>
                </a>

                <a href="{{ route('facebook.paginas') }}" 
                   class="flex items-center justify-center px-4 py-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition-colors group {{ !$configurado ? 'opacity-50 pointer-events-none' : '' }}">
                    <div class="text-center">
                        <i class="fas fa-link text-3xl text-gray-400 group-hover:text-blue-500 mb-2"></i>
                        <p class="text-sm font-medium text-gray-900">Conectar Página</p>
                        <p class="text-xs text-gray-500">Adicionar nova página</p>
                    </div>
                </a>

                <a href="{{ route('configuracoes.facebook') }}" 
                   class="flex items-center justify-center px-4 py-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition-colors group">
                    <div class="text-center">
                        <i class="fas fa-cog text-3xl text-gray-400 group-hover:text-blue-500 mb-2"></i>
                        <p class="text-sm font-medium text-gray-900">Configurações</p>
                        <p class="text-xs text-gray-500">API e credenciais</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Posts recentes -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900">Posts Recentes</h3>
            <a href="{{ route('facebook.posts') }}" class="text-sm text-blue-600 hover:text-blue-800">
                Ver todos <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="px-4 py-12 text-center text-gray-500">
            <i class="fab fa-facebook text-4xl mb-3 text-gray-300"></i>
            <h4 class="text-sm font-medium text-gray-900">Nenhum post ainda</h4>
            <p class="mt-1 text-sm text-gray-500">Comece criando seu primeiro post no Facebook.</p>
            @if($configurado)
                <div class="mt-4">
                    <a href="{{ route('facebook.criar-post') }}" 
                       class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Criar Post
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
