@extends('layouts.app')

@section('title', 'Enviar Mensagem Telegram - WhatsApp Manager')
@section('header', 'Enviar Mensagem no Telegram')

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
                    <a href="{{ route('telegram.mensagens') }}" class="text-gray-500 hover:text-gray-700">Mensagens</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                    <span class="text-gray-700 font-medium">Enviar</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Nova Mensagem</h3>
            <p class="mt-1 text-sm text-gray-500">Envie uma mensagem para um canal ou grupo.</p>
        </div>

        <form action="{{ route('telegram.enviar-mensagem') }}" method="POST" class="px-4 py-5 sm:p-6">
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

            @if(count($canais) === 0)
                <div class="mb-6 rounded-md bg-yellow-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Nenhum canal encontrado</h3>
                            <p class="mt-1 text-sm text-yellow-700">
                                Adicione o bot como administrador em um canal para enviar mensagens.
                            </p>
                            <div class="mt-3">
                                <a href="{{ route('telegram.canais') }}" class="text-sm font-medium text-yellow-800 hover:text-yellow-700">
                                    Gerenciar canais <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="space-y-6">
                <div>
                    <label for="tipo" class="block text-sm font-medium text-gray-700">
                        Tipo de Destino <span class="text-red-500">*</span>
                    </label>
                    <select name="tipo" id="tipo" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border">
                        <option value="canal">Canal</option>
                        <option value="grupo">Grupo</option>
                        <option value="usuario">Usuário</option>
                    </select>
                </div>

                <div>
                    <label for="destino" class="block text-sm font-medium text-gray-700">
                        Destino <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-at text-gray-400"></i>
                        </div>
                        <input type="text" name="destino" id="destino" required
                               value="{{ old('destino', request('canal')) }}"
                               placeholder="canal_ou_chat_id"
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Use @username ou Chat ID</p>
                </div>

                <div>
                    <label for="mensagem" class="block text-sm font-medium text-gray-700">
                        Mensagem <span class="text-red-500">*</span>
                    </label>
                    <textarea name="mensagem" id="mensagem" rows="6" required
                              placeholder="Digite sua mensagem..."
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-3 py-2 border">{{ old('mensagem') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Suporta formatação Markdown e HTML</p>
                </div>

                <div>
                    <label for="parse_mode" class="block text-sm font-medium text-gray-700">
                        Formatação
                    </label>
                    <select name="parse_mode" id="parse_mode"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border">
                        <option value="">Texto simples</option>
                        <option value="Markdown">Markdown</option>
                        <option value="HTML">HTML</option>
                    </select>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="disable_notification" name="disable_notification"
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="disable_notification" class="ml-2 block text-sm text-gray-900">
                        Enviar sem notificação
                    </label>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-4">
                <a href="{{ route('telegram.mensagens') }}" class="text-sm font-semibold text-gray-700 hover:text-gray-900">
                    Cancelar
                </a>
                <button type="submit"
                        class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition-colors">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Enviar
                </button>
            </div>
        </form>
    </div>

    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3 text-sm text-blue-700">
                <p class="font-medium mb-2">Dicas de Formatação:</p>
                <ul class="space-y-1 text-xs">
                    <li><code>*negrito*</code> → <strong>negrito</strong></li>
                    <li><code>_itálico_</code> → <em>itálico</em></li>
                    <li><code>`código`</code> → <code>código</code></li>
                    <li><code>[link](url)</code> → link clicável</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
