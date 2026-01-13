@extends('layouts.app')

@section('title', 'Mensagens Telegram - WhatsApp Manager')
@section('header', 'Mensagens do Telegram')

@section('content')
<div>
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('telegram.index') }}" class="text-gray-500 hover:text-gray-700">
                    <i class="fab fa-telegram mr-1"></i>
                    Telegram
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                    <span class="text-gray-700 font-medium">Mensagens</span>
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
            <h2 class="text-lg font-medium text-gray-900">Histórico de Mensagens</h2>
            <p class="mt-1 text-sm text-gray-500">Mensagens enviadas via Telegram.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('telegram.criar-mensagem') }}" 
               class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Nova Mensagem
            </a>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        @if(count($mensagens) > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($mensagens as $mensagem)
                    <li class="px-4 py-4 sm:px-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-sm font-medium text-gray-900">{{ $mensagem['canal'] ?? 'Canal' }}</span>
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium 
                                        {{ $mensagem['status'] === 'enviado' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($mensagem['status'] ?? 'enviado') }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 line-clamp-2">{{ $mensagem['texto'] ?? 'Mensagem...' }}</p>
                                <p class="mt-1 text-xs text-gray-400">
                                    <i class="far fa-clock mr-1"></i>
                                    {{ $mensagem['data'] ?? now()->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="px-4 py-12 text-center text-gray-500">
                <i class="fas fa-paper-plane text-4xl mb-3 text-gray-300"></i>
                <h3 class="text-sm font-medium text-gray-900">Nenhuma mensagem enviada</h3>
                <p class="mt-1 text-sm text-gray-500">Envie sua primeira mensagem para um canal.</p>
                <div class="mt-6">
                    <a href="{{ route('telegram.criar-mensagem') }}" 
                       class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition-colors">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Enviar Mensagem
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
