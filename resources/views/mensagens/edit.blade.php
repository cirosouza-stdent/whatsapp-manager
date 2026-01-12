@extends('layouts.app')

@section('title', 'Editar Mensagem - WhatsApp Manager')
@section('header', 'Editar Mensagem Agendada')

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
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                    <a href="{{ route('mensagens.show', $mensagem) }}" class="text-gray-500 hover:text-gray-700">
                        {{ Str::limit($mensagem->telefone_destino, 15) }}
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                    <span class="text-gray-700 font-medium">Editar</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Editar Mensagem Agendada</h3>
            <p class="mt-1 text-sm text-gray-500">Altere os dados da mensagem antes do envio.</p>
        </div>

        <form action="{{ route('mensagens.update', $mensagem) }}" method="POST" class="px-4 py-5 sm:p-6">
            @csrf
            @method('PUT')

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

            <div class="space-y-6">
                <!-- Instância (somente leitura) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Instância do WhatsApp
                    </label>
                    <div class="mt-1 flex items-center px-3 py-2 bg-gray-50 border border-gray-300 rounded-md">
                        <i class="fab fa-whatsapp text-green-500 mr-2"></i>
                        <span class="text-sm text-gray-900">{{ $mensagem->instancia->nome }}</span>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">A instância não pode ser alterada após o agendamento.</p>
                </div>

                <div>
                    <label for="telefone_destino" class="block text-sm font-medium text-gray-700">
                        Telefone de Destino <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="telefone_destino" id="telefone_destino" required
                           value="{{ old('telefone_destino', $mensagem->telefone_destino) }}"
                           placeholder="5511999999999"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm px-3 py-2 border @error('telefone_destino') border-red-300 @enderror">
                    <p class="mt-1 text-sm text-gray-500">Formato: código do país + DDD + número</p>
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
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm px-3 py-2 border @error('mensagem') border-red-300 @enderror">{{ old('mensagem', $mensagem->mensagem) }}</textarea>
                    <div class="mt-1 flex justify-between text-sm text-gray-500">
                        <span>Máximo de 4096 caracteres</span>
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
                           value="{{ old('agendado_para', $mensagem->agendado_para->format('Y-m-d\TH:i')) }}"
                           min="{{ now()->format('Y-m-d\TH:i') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm px-3 py-2 border @error('agendado_para') border-red-300 @enderror">
                    <p class="mt-1 text-sm text-gray-500">A data deve ser no futuro</p>
                    @error('agendado_para')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Seção de mídia (opcional) -->
                <div x-data="{ showMedia: {{ $mensagem->midia ? 'true' : 'false' }} }">
                    <button type="button" @click="showMedia = !showMedia" 
                            class="flex items-center text-sm text-gray-600 hover:text-gray-900">
                        <i class="fas fa-paperclip mr-2"></i>
                        Mídia anexada (opcional)
                        <i class="fas fa-chevron-down ml-2 text-xs transition-transform" :class="{ 'rotate-180': showMedia }"></i>
                    </button>

                    <div x-show="showMedia" x-collapse class="mt-4 space-y-4 p-4 bg-gray-50 rounded-md">
                        <div>
                            <label for="midia_url" class="block text-sm font-medium text-gray-700">
                                URL da Mídia
                            </label>
                            <input type="url" name="midia[url]" id="midia_url"
                                   value="{{ old('midia.url', $mensagem->midia['url'] ?? '') }}"
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
                                <option value="image" {{ (old('midia.type', $mensagem->midia['type'] ?? '') === 'image') ? 'selected' : '' }}>Imagem</option>
                                <option value="video" {{ (old('midia.type', $mensagem->midia['type'] ?? '') === 'video') ? 'selected' : '' }}>Vídeo</option>
                                <option value="audio" {{ (old('midia.type', $mensagem->midia['type'] ?? '') === 'audio') ? 'selected' : '' }}>Áudio</option>
                                <option value="document" {{ (old('midia.type', $mensagem->midia['type'] ?? '') === 'document') ? 'selected' : '' }}>Documento</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Info adicional -->
                <div class="rounded-md bg-gray-50 p-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Informações do Agendamento</h4>
                    <dl class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst($mensagem->status) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Tentativas</dt>
                            <dd class="mt-1 text-gray-900">{{ $mensagem->tentativas }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Criado em</dt>
                            <dd class="mt-1 text-gray-900">{{ $mensagem->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-4">
                <a href="{{ route('mensagens.show', $mensagem) }}" 
                   class="text-sm font-semibold text-gray-700 hover:text-gray-900">
                    Cancelar
                </a>
                <button type="submit"
                        class="inline-flex items-center rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
