@extends('layouts.app')

@section('title', 'Criar Post Instagram - WhatsApp Manager')
@section('header', 'Criar Post no Instagram')

@section('content')
<div class="max-w-2xl mx-auto">
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('instagram.index') }}" class="text-gray-500 hover:text-gray-700">
                    <i class="fab fa-instagram mr-1"></i>
                    Instagram
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                    <a href="{{ route('instagram.posts') }}" class="text-gray-500 hover:text-gray-700">Posts</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                    <span class="text-gray-700 font-medium">Criar Post</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Novo Post</h3>
            <p class="mt-1 text-sm text-gray-500">Crie um novo post para o Instagram.</p>
        </div>

        <form action="{{ route('instagram.salvar-post') }}" method="POST" class="px-4 py-5 sm:p-6">
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

            @if(count($contas) === 0)
                <div class="mb-6 rounded-md bg-yellow-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Nenhuma conta conectada</h3>
                            <p class="mt-1 text-sm text-yellow-700">
                                Você precisa conectar uma conta do Instagram para criar posts.
                            </p>
                            <div class="mt-3">
                                <a href="{{ route('instagram.contas') }}" class="text-sm font-medium text-yellow-800 hover:text-yellow-700">
                                    Conectar conta <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="space-y-6">
                <div>
                    <label for="conta_id" class="block text-sm font-medium text-gray-700">
                        Conta <span class="text-red-500">*</span>
                    </label>
                    <select name="conta_id" id="conta_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm py-2 px-3 border">
                        <option value="">Selecione uma conta</option>
                        @foreach($contas as $conta)
                            <option value="{{ $conta['id'] }}">{{ $conta['username'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="imagem" class="block text-sm font-medium text-gray-700">
                        URL da Imagem <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-image text-gray-400"></i>
                        </div>
                        <input type="url" name="imagem" id="imagem" required
                               value="{{ old('imagem') }}"
                               placeholder="https://exemplo.com/imagem.jpg"
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500 sm:text-sm">
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Imagem quadrada recomendada (1080x1080px)</p>
                </div>

                <div>
                    <label for="legenda" class="block text-sm font-medium text-gray-700">
                        Legenda <span class="text-red-500">*</span>
                    </label>
                    <textarea name="legenda" id="legenda" rows="5" required
                              placeholder="Escreva uma legenda envolvente..."
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm px-3 py-2 border">{{ old('legenda') }}</textarea>
                    <div class="mt-1 flex justify-between text-sm text-gray-500">
                        <span>Use hashtags para maior alcance</span>
                        <span>Máx. 2200 caracteres</span>
                    </div>
                </div>

                <div x-data="{ agendar: false }">
                    <div class="flex items-center mb-3">
                        <input type="checkbox" id="agendar" x-model="agendar"
                               class="h-4 w-4 text-pink-600 focus:ring-pink-500 border-gray-300 rounded">
                        <label for="agendar" class="ml-2 block text-sm text-gray-900">
                            Agendar publicação
                        </label>
                    </div>
                    
                    <div x-show="agendar" x-collapse>
                        <label for="agendado_para" class="block text-sm font-medium text-gray-700">
                            Data e Hora
                        </label>
                        <input type="datetime-local" name="agendado_para" id="agendado_para"
                               min="{{ now()->format('Y-m-d\TH:i') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm px-3 py-2 border">
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-4">
                <a href="{{ route('instagram.posts') }}" class="text-sm font-semibold text-gray-700 hover:text-gray-900">
                    Cancelar
                </a>
                <button type="submit"
                        {{ count($contas) === 0 ? 'disabled' : '' }}
                        class="inline-flex items-center rounded-md bg-gradient-to-r from-purple-500 to-pink-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:from-purple-600 hover:to-pink-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fab fa-instagram mr-2"></i>
                    Publicar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
