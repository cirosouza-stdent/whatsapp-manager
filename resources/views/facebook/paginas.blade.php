@extends('layouts.app')

@section('title', 'Páginas Facebook - WhatsApp Manager')
@section('header', 'Páginas do Facebook')

@section('content')
<div>
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('facebook.index') }}" class="text-gray-500 hover:text-gray-700">
                    <i class="fab fa-facebook mr-1"></i>
                    Facebook
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                    <span class="text-gray-700 font-medium">Páginas</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Cabeçalho com ações -->
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h2 class="text-lg font-medium text-gray-900">Suas Páginas</h2>
            <p class="mt-1 text-sm text-gray-500">Gerencie as páginas do Facebook conectadas ao sistema.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <button type="button" 
                    class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Conectar Página
            </button>
        </div>
    </div>

    <!-- Lista de páginas -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        @if(count($paginas) > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($paginas as $pagina)
                    <li class="px-4 py-4 sm:px-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center min-w-0 flex-1">
                                <div class="flex-shrink-0">
                                    <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fab fa-facebook-f text-blue-600 text-xl"></i>
                                    </div>
                                </div>
                                <div class="ml-4 min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $pagina['nome'] ?? 'Página' }}</p>
                                    <p class="text-sm text-gray-500">{{ $pagina['categoria'] ?? 'Categoria' }}</p>
                                </div>
                            </div>
                            <div class="ml-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-green-100 text-green-800">
                                    Conectada
                                </span>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="px-4 py-12 text-center text-gray-500">
                <i class="fab fa-facebook text-4xl mb-3 text-gray-300"></i>
                <h3 class="text-sm font-medium text-gray-900">Nenhuma página conectada</h3>
                <p class="mt-1 text-sm text-gray-500">Conecte uma página do Facebook para começar a publicar.</p>
                <div class="mt-6">
                    <button type="button" 
                            class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Conectar Página
                    </button>
                </div>
            </div>
        @endif
    </div>

    <!-- Informações -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3 text-sm text-blue-700">
                <p>Para conectar uma página, você precisa ser administrador da página no Facebook e ter configurado o aplicativo nas <a href="{{ route('configuracoes.facebook') }}" class="font-medium underline">Configurações do Facebook</a>.</p>
            </div>
        </div>
    </div>
</div>
@endsection
