@extends('layouts.app')

@section('title', 'Nova Mensagem Agendada - WhatsApp Manager')
@section('header', 'Agendar Mensagem')

@section('content')
<div class="max-w-2xl mx-auto">
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
                    <span class="text-gray-700 font-medium">Nova Mensagem</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Agendar Nova Mensagem</h3>
            <p class="mt-1 text-sm text-gray-500">Programe uma mensagem para ser enviada automaticamente.</p>
        </div>

        <form action="{{ route('mensagens.store') }}" method="POST" class="px-4 py-5 sm:p-6">
            @csrf

            @if ($errors->any())
                <div class="mb-4 rounded-md bg-red-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Ocorreram erros na validação</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc space-y-1 pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($instancias->count() === 0)
                <div class="mb-6 rounded-md bg-yellow-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Nenhuma instância conectada</h3>
                            <p class="mt-1 text-sm text-yellow-700">
                                Você precisa ter pelo menos uma instância do WhatsApp conectada para agendar mensagens.
                            </p>
                            <div class="mt-3">
                                <a href="{{ route('instancias.create') }}" class="text-sm font-medium text-yellow-800 hover:text-yellow-700">
                                    Criar instância <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="space-y-6">
                <div>
                    <label for="instancia_id" class="block text-sm font-medium text-gray-700">
                        Instância do WhatsApp <span class="text-red-500">*</span>
                    </label>
                    <select name="instancia_id" id="instancia_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2 px-3 border @error('instancia_id') border-red-300 @enderror">
                        <option value="">Selecione uma instância</option>
                        @foreach($instancias as $instancia)
                            <option value="{{ $instancia->id }}" {{ old('instancia_id') == $instancia->id ? 'selected' : '' }}>
                                {{ $instancia->nome }} 
                                @if($instancia->telefone) ({{ $instancia->telefone }}) @endif
                            </option>
                        @endforeach
                    </select>
                    @error('instancia_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="telefone_destino" class="block text-sm font-medium text-gray-700">
                        Telefone de Destino <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="telefone_destino" id="telefone_destino" required
                           value="{{ old('telefone_destino') }}"
                           placeholder="5511999999999"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm px-3 py-2 border @error('telefone_destino') border-red-300 @enderror">
                    <p class="mt-1 text-sm text-gray-500">Formato: código do país + DDD + número (sem espaços ou caracteres especiais)</p>
                    @error('telefone_destino')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="mensagem" class="block text-sm font-medium text-gray-700">
                        Mensagem <span class="text-red-500">*</span>
                    </label>
                    <textarea name="mensagem" id="mensagem" rows="5" required
                              placeholder="Digite a mensagem que será enviada..."
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm px-3 py-2 border @error('mensagem') border-red-300 @enderror">{{ old('mensagem') }}</textarea>
                    <div class="mt-1 flex justify-between text-sm text-gray-500">
                        <span>Máximo de 4096 caracteres</span>
                        <span x-data="{ count: 0 }" x-init="count = $el.previousElementSibling.previousElementSibling.value.length; $el.previousElementSibling.previousElementSibling.addEventListener('input', (e) => count = e.target.value.length)">
                            <span x-text="count">0</span>/4096
                        </span>
                    </div>
                    @error('mensagem')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="agendado_para" class="block text-sm font-medium text-gray-700">
                        Data e Hora do Envio <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" name="agendado_para" id="agendado_para" required
                           value="{{ old('agendado_para') }}"
                           min="{{ now()->format('Y-m-d\TH:i') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm px-3 py-2 border @error('agendado_para') border-red-300 @enderror">
                    <p class="mt-1 text-sm text-gray-500">A data deve ser no futuro</p>
                    @error('agendado_para')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Seção de mídia (opcional) -->
                <div x-data="{ showMedia: false }">
                    <button type="button" @click="showMedia = !showMedia" 
                            class="flex items-center text-sm text-gray-600 hover:text-gray-900">
                        <i class="fas fa-paperclip mr-2"></i>
                        Adicionar mídia (opcional)
                        <i class="fas fa-chevron-down ml-2 text-xs transition-transform" :class="{ 'rotate-180': showMedia }"></i>
                    </button>

                    <div x-show="showMedia" x-collapse class="mt-4 space-y-4 p-4 bg-gray-50 rounded-md">
                        <div>
                            <label for="midia_url" class="block text-sm font-medium text-gray-700">
                                URL da Mídia
                            </label>
                            <input type="url" name="midia[url]" id="midia_url"
                                   value="{{ old('midia.url') }}"
                                   placeholder="https://exemplo.com/imagem.jpg"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm px-3 py-2 border">
                        </div>
                        <div>
                            <label for="midia_type" class="block text-sm font-medium text-gray-700">
                                Tipo de Mídia
                            </label>
                            <select name="midia[type]" id="midia_type"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2 px-3 border">
                                <option value="">Selecione o tipo</option>
                                <option value="image" {{ old('midia.type') === 'image' ? 'selected' : '' }}>Imagem</option>
                                <option value="video" {{ old('midia.type') === 'video' ? 'selected' : '' }}>Vídeo</option>
                                <option value="audio" {{ old('midia.type') === 'audio' ? 'selected' : '' }}>Áudio</option>
                                <option value="document" {{ old('midia.type') === 'document' ? 'selected' : '' }}>Documento</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-4">
                <a href="{{ route('mensagens.index') }}" 
                   class="text-sm font-semibold text-gray-700 hover:text-gray-900">
                    Cancelar
                </a>
                <button type="submit"
                        {{ $instancias->count() === 0 ? 'disabled' : '' }}
                        class="inline-flex items-center rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-calendar-check mr-2"></i>
                    Agendar Mensagem
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
