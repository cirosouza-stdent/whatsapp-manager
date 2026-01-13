@extends('layouts.app')

@section('title', 'Posts Instagram - WhatsApp Manager')
@section('header', 'Posts do Instagram')

@section('content')
<div>
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('instagram.index') }}" class="text-gray-500 hover:text-gray-700">
                    <i class="fab fa-instagram mr-1"></i>
                    Instagram
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

    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h2 class="text-lg font-medium text-gray-900">Seus Posts</h2>
            <p class="mt-1 text-sm text-gray-500">Gerencie posts agendados e publicados.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('instagram.criar-post') }}" 
               class="inline-flex items-center rounded-md bg-gradient-to-r from-purple-500 to-pink-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:from-purple-600 hover:to-pink-600 transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Novo Post
            </a>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        @if(count($posts) > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 p-4">
                @foreach($posts as $post)
                    <div class="relative group">
                        <img src="{{ $post['imagem'] ?? 'https://via.placeholder.com/300' }}" 
                             alt="Post" 
                             class="w-full aspect-square object-cover rounded-lg">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all rounded-lg flex items-center justify-center">
                            <div class="opacity-0 group-hover:opacity-100 transition-opacity text-white text-center">
                                <p class="text-sm"><i class="fas fa-heart mr-1"></i>{{ $post['curtidas'] ?? 0 }}</p>
                                <p class="text-sm"><i class="fas fa-comment mr-1"></i>{{ $post['comentarios'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="px-4 py-12 text-center text-gray-500">
                <i class="fas fa-images text-4xl mb-3 text-gray-300"></i>
                <h3 class="text-sm font-medium text-gray-900">Nenhum post encontrado</h3>
                <p class="mt-1 text-sm text-gray-500">Comece criando seu primeiro post.</p>
                <div class="mt-6">
                    <a href="{{ route('instagram.criar-post') }}" 
                       class="inline-flex items-center rounded-md bg-gradient-to-r from-purple-500 to-pink-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:from-purple-600 hover:to-pink-600 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Criar Post
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
