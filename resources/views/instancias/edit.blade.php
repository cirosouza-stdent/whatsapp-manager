@extends('layouts.app')

@section('title', 'Editar ' . $instancia->nome . ' - WhatsApp Manager')
@section('header', 'Editar Instância')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('instancias.index') }}" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-list mr-1"></i>
                    Instâncias
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                    <a href="{{ route('instancias.show', $instancia) }}" class="text-gray-500 hover:text-gray-700">
                        {{ $instancia->nome }}
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
            <h3 class="text-lg font-medium text-gray-900">Editar Instância</h3>
            <p class="mt-1 text-sm text-gray-500">Atualize as informações da sua instância do WhatsApp.</p>
        </div>

        <form action="{{ route('instancias.update', $instancia) }}" method="POST" class="px-4 py-5 sm:p-6">
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
                <div>
                    <label for="nome" class="block text-sm font-medium text-gray-700">
                        Nome da Instância <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nome" id="nome" required
                           value="{{ old('nome', $instancia->nome) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm px-3 py-2 border @error('nome') border-red-300 @enderror"
                           placeholder="Ex: Atendimento, Vendas, Suporte...">
                    @error('nome')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="telefone" class="block text-sm font-medium text-gray-700">
                        Telefone
                    </label>
                    <input type="text" name="telefone" id="telefone"
                           value="{{ old('telefone', $instancia->telefone) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm px-3 py-2 border @error('telefone') border-red-300 @enderror"
                           placeholder="5511999999999">
                    <p class="mt-1 text-sm text-gray-500">Número associado a esta instância (formato: código país + DDD + número)</p>
                    @error('telefone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Informações adicionais -->
                <div class="rounded-md bg-gray-50 p-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Informações da Instância</h4>
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-{{ $instancia->status_badge_color }}-100 text-{{ $instancia->status_badge_color }}-800">
                                    {{ $instancia->status_text }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Última conexão</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $instancia->last_connected_at ? $instancia->last_connected_at->format('d/m/Y H:i') : 'Nunca' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Criado em</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $instancia->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Atualizado em</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $instancia->updated_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-4">
                <a href="{{ route('instancias.show', $instancia) }}" 
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
