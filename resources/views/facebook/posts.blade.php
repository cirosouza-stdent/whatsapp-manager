@extends('layouts.app')

@section('title', 'Posts Facebook - WhatsApp Manager')
@section('header', 'Posts do Facebook')

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
                    <span class="text-gray-700 font-medium">Posts</span>
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

    <!-- Cabeçalho com ações -->
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h2 class="text-lg font-medium text-gray-900">Seus Posts</h2>
            <p class="mt-1 text-sm text-gray-500">Gerencie posts agendados e publicados.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('facebook.criar-post') }}" 
               class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Novo Post
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-4 sm:px-6">
            <div class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="status" class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                    <select name="status" id="status" 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border">
                        <option value="">Todos</option>
                        <option value="agendado">Agendado</option>
                        <option value="publicado">Publicado</option>
                        <option value="erro">Com erro</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="inline-flex items-center rounded-md bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 transition-colors">
                        <i class="fas fa-filter mr-2"></i>
                        Filtrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de posts -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        @if(count($posts) > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($posts as $post)
                    <li class="px-4 py-4 sm:px-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start min-w-0 flex-1">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fab fa-facebook-f text-blue-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4 min-w-0 flex-1">
                                    <p class="text-sm text-gray-900">{{ Str::limit($post['mensagem'] ?? '', 150) }}</p>
                                    <div class="mt-2 flex items-center gap-4 text-xs text-gray-500">
                                        <span><i class="fas fa-calendar mr-1"></i>{{ $post['data'] ?? '' }}</span>
                                        <span><i class="far fa-thumbs-up mr-1"></i>{{ $post['curtidas'] ?? 0 }}</span>
                                        <span><i class="far fa-comment mr-1"></i>{{ $post['comentarios'] ?? 0 }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="ml-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-green-100 text-green-800">
                                    {{ $post['status'] ?? 'Publicado' }}
                                </span>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="px-4 py-12 text-center text-gray-500">
                <i class="fas fa-newspaper text-4xl mb-3 text-gray-300"></i>
                <h3 class="text-sm font-medium text-gray-900">Nenhum post encontrado</h3>
                <p class="mt-1 text-sm text-gray-500">Comece criando seu primeiro post.</p>
                <div class="mt-6">
                    <a href="{{ route('facebook.criar-post') }}" 
                       class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Criar Post
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
