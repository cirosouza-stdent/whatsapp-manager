@extends('layouts.app')

@section('title', 'Bots Telegram - WhatsApp Manager')
@section('header', 'Bots do Telegram')

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
                    <span class="text-gray-700 font-medium">Bots</span>
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
            <h2 class="text-lg font-medium text-gray-900">Seus Bots</h2>
            <p class="mt-1 text-sm text-gray-500">Gerencie os bots do Telegram conectados ao sistema.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('telegram.criar-bot') }}" 
               class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Adicionar Bot
            </a>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        @if(count($bots) > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($bots as $bot)
                    <li class="px-4 py-4 sm:px-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center min-w-0 flex-1">
                                <div class="flex-shrink-0">
                                    <div class="h-12 w-12 rounded-full bg-blue-500 flex items-center justify-center">
                                        <i class="fas fa-robot text-white text-xl"></i>
                                    </div>
                                </div>
                                <div class="ml-4 min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $bot->nome }}</p>
                                    <p class="text-sm text-gray-500">@{{ $bot->username }}</p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        <i class="fas fa-key mr-1"></i>
                                        {{ $bot->token_mascarado }}
                                    </p>
                                </div>
                            </div>
                            <div class="ml-4 flex items-center gap-3">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $bot->ativo ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    <i class="fas fa-circle text-xs mr-1"></i>
                                    {{ $bot->ativo ? 'Ativo' : 'Inativo' }}
                                </span>
                                <div class="flex items-center gap-2">
                                    <form action="{{ route('telegram.toggle-bot', $bot) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-gray-400 hover:text-blue-600 p-1" title="{{ $bot->ativo ? 'Desativar' : 'Ativar' }}">
                                            <i class="fas {{ $bot->ativo ? 'fa-pause' : 'fa-play' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('telegram.excluir-bot', $bot) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja remover este bot?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600 p-1" title="Remover">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @if($bot->ultimo_uso)
                            <div class="mt-2 ml-16 text-xs text-gray-400">
                                <i class="far fa-clock mr-1"></i>
                                Último uso: {{ $bot->ultimo_uso->diffForHumans() }}
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        @else
            <div class="px-4 py-12 text-center text-gray-500">
                <i class="fas fa-robot text-4xl mb-3 text-gray-300"></i>
                <h3 class="text-sm font-medium text-gray-900">Nenhum bot configurado</h3>
                <p class="mt-1 text-sm text-gray-500">Adicione um bot para começar a enviar mensagens.</p>
                <div class="mt-6">
                    <a href="{{ route('telegram.criar-bot') }}" 
                       class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Adicionar Bot
                    </a>
                </div>
            </div>
        @endif
    </div>

    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3 text-sm text-blue-700">
                <p><strong>Como criar um bot:</strong> Use o <a href="https://t.me/BotFather" target="_blank" class="font-medium underline">@BotFather</a> no Telegram para criar um novo bot e obter o token de API.</p>
            </div>
        </div>
    </div>
</div>
@endsection
