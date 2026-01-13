@extends('layouts.app')

@section('title', 'Canais Telegram - WhatsApp Manager')
@section('header', 'Canais do Telegram')

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
                    <span class="text-gray-700 font-medium">Canais</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h2 class="text-lg font-medium text-gray-900">Seus Canais</h2>
            <p class="mt-1 text-sm text-gray-500">Gerencie os canais do Telegram conectados ao bot.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <button type="button" 
                    class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Adicionar Canal
            </button>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        @if(count($canais) > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($canais as $canal)
                    <li class="px-4 py-4 sm:px-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center min-w-0 flex-1">
                                <div class="flex-shrink-0">
                                    <div class="h-12 w-12 rounded-full bg-cyan-500 flex items-center justify-center">
                                        <i class="fas fa-broadcast-tower text-white text-xl"></i>
                                    </div>
                                </div>
                                <div class="ml-4 min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $canal['nome'] ?? 'Meu Canal' }}</p>
                                    <p class="text-sm text-gray-500">
                                        <i class="fas fa-users mr-1"></i>
                                        {{ number_format($canal['membros'] ?? 0) }} membros
                                    </p>
                                </div>
                            </div>
                            <div class="ml-4 flex items-center gap-3">
                                <a href="{{ route('telegram.criar-mensagem') }}?canal={{ $canal['id'] ?? '' }}" 
                                   class="inline-flex items-center rounded-md bg-blue-100 px-2.5 py-1.5 text-xs font-medium text-blue-700 hover:bg-blue-200">
                                    <i class="fas fa-paper-plane mr-1"></i>
                                    Enviar
                                </a>
                                <button class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="px-4 py-12 text-center text-gray-500">
                <i class="fas fa-broadcast-tower text-4xl mb-3 text-gray-300"></i>
                <h3 class="text-sm font-medium text-gray-900">Nenhum canal encontrado</h3>
                <p class="mt-1 text-sm text-gray-500">Adicione o bot como administrador em um canal.</p>
                <div class="mt-6">
                    <button type="button" 
                            class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Adicionar Canal
                    </button>
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
                <p><strong>Nota:</strong> O bot precisa ser adicionado como administrador do canal com permissão para postar mensagens.</p>
            </div>
        </div>
    </div>
</div>
@endsection
