@extends('layouts.app')

@section('title', 'Nova Instância - WhatsApp Manager')
@section('header', 'Nova Instância')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="mb-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Criar Nova Instância</h3>
                <p class="mt-1 text-sm text-gray-500">Preencha os dados para criar uma nova conexão do WhatsApp.</p>
            </div>

            <form action="{{ route('instancias.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Nome -->
                <div>
                    <label for="nome" class="block text-sm font-medium text-gray-700">Nome da Instância *</label>
                    <div class="mt-1">
                        <input type="text" name="nome" id="nome" value="{{ old('nome') }}" required
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm
                                      @error('nome') border-red-300 @enderror"
                               placeholder="Ex: Suporte, Vendas, Marketing...">
                    </div>
                    @error('nome')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Telefone (opcional) -->
                <div>
                    <label for="telefone" class="block text-sm font-medium text-gray-700">Telefone (opcional)</label>
                    <div class="mt-1">
                        <input type="text" name="telefone" id="telefone" value="{{ old('telefone') }}"
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm"
                               placeholder="Ex: 5511999999999">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">O telefone será detectado automaticamente após a conexão.</p>
                </div>

                <!-- Informações -->
                <div class="rounded-md bg-blue-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Como funciona?</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ol class="list-decimal ml-5 space-y-1">
                                    <li>Após criar a instância, um QR Code será gerado</li>
                                    <li>Abra o WhatsApp no seu celular</li>
                                    <li>Vá em Configurações > Dispositivos conectados > Conectar dispositivo</li>
                                    <li>Escaneie o QR Code exibido na tela</li>
                                    <li>Aguarde a conexão ser estabelecida</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botões -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('instancias.index') }}" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit" class="inline-flex items-center rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                        <i class="fas fa-plus mr-2"></i>
                        Criar Instância
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
