@extends('layouts.app')

@section('title', 'Adicionar Bot Telegram - WhatsApp Manager')
@section('header', 'Adicionar Bot do Telegram')

@section('content')
<div class="max-w-2xl mx-auto">
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('telegram.index') }}" class="text-gray-500 hover:text-gray-700">
                    <i class="fab fa-telegram mr-1"></i>
                    Telegram
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                    <a href="{{ route('telegram.bots') }}" class="text-gray-500 hover:text-gray-700">Bots</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                    <span class="text-gray-700 font-medium">Adicionar</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <div class="flex items-center">
                <div class="rounded-md bg-blue-500 p-2">
                    <i class="fas fa-robot text-white text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-gray-900">Novo Bot</h3>
                    <p class="text-sm text-gray-500">Adicione um bot do Telegram usando o token do BotFather.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('telegram.salvar-bot') }}" method="POST" class="px-4 py-5 sm:p-6">
            @csrf

            @if ($errors->any())
                <div class="mb-4 rounded-md bg-red-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <ul class="list-disc space-y-1 pl-5 text-sm text-red-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="space-y-6">
                <div>
                    <label for="token" class="block text-sm font-medium text-gray-700">
                        Token do Bot <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-key text-gray-400"></i>
                        </div>
                        <input type="text" name="token" id="token" required
                               value="{{ old('token') }}"
                               placeholder="123456789:ABCdefGHIjklMNOpqrsTUVwxyz"
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Token fornecido pelo @BotFather ao criar o bot</p>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-2">
                        <i class="fas fa-lightbulb text-yellow-500 mr-1"></i>
                        Como obter o token
                    </h4>
                    <ol class="list-decimal list-inside space-y-2 text-sm text-gray-600">
                        <li>Abra o Telegram e pesquise por <a href="https://t.me/BotFather" target="_blank" class="text-blue-600 hover:underline">@BotFather</a></li>
                        <li>Envie o comando <code class="bg-gray-200 px-1 rounded">/newbot</code></li>
                        <li>Siga as instruções para escolher um nome e username</li>
                        <li>Copie o token gerado e cole no campo acima</li>
                    </ol>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-4">
                <a href="{{ route('telegram.bots') }}" class="text-sm font-semibold text-gray-700 hover:text-gray-900">
                    Cancelar
                </a>
                <button type="submit"
                        class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition-colors">
                    <i class="fas fa-check mr-2"></i>
                    Adicionar Bot
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
