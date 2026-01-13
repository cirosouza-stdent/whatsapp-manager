@extends('layouts.app')

@section('title', 'Contas Instagram - WhatsApp Manager')
@section('header', 'Contas do Instagram')

@section('content')
<div>
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('instagram.index') }}" class="text-gray-500 hover:text-gray-700">
                    <i class="fab fa-instagram mr-1"></i>
                    Instagram
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                    <span class="text-gray-700 font-medium">Contas</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h2 class="text-lg font-medium text-gray-900">Suas Contas</h2>
            <p class="mt-1 text-sm text-gray-500">Gerencie as contas do Instagram conectadas ao sistema.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <button type="button" 
                    class="inline-flex items-center rounded-md bg-gradient-to-r from-purple-500 to-pink-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:from-purple-600 hover:to-pink-600 transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Conectar Conta
            </button>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        @if(count($contas) > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($contas as $conta)
                    <li class="px-4 py-4 sm:px-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center min-w-0 flex-1">
                                <div class="flex-shrink-0">
                                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                                        <i class="fab fa-instagram text-white text-xl"></i>
                                    </div>
                                </div>
                                <div class="ml-4 min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $conta['username'] ?? '@usuario' }}</p>
                                    <p class="text-sm text-gray-500">{{ $conta['nome'] ?? 'Nome da conta' }}</p>
                                </div>
                            </div>
                            <div class="ml-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-green-100 text-green-800">
                                    Conectada
                                </span>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="px-4 py-12 text-center text-gray-500">
                <i class="fab fa-instagram text-4xl mb-3 text-gray-300"></i>
                <h3 class="text-sm font-medium text-gray-900">Nenhuma conta conectada</h3>
                <p class="mt-1 text-sm text-gray-500">Conecte uma conta comercial do Instagram para começar.</p>
                <div class="mt-6">
                    <button type="button" 
                            class="inline-flex items-center rounded-md bg-gradient-to-r from-purple-500 to-pink-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:from-purple-600 hover:to-pink-600 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Conectar Conta
                    </button>
                </div>
            </div>
        @endif
    </div>

    <div class="mt-6 bg-pink-50 border border-pink-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-pink-400"></i>
            </div>
            <div class="ml-3 text-sm text-pink-700">
                <p><strong>Nota:</strong> Apenas contas comerciais ou de criador podem ser conectadas via API. Configure as credenciais em <a href="{{ route('configuracoes.instagram') }}" class="font-medium underline">Configurações do Instagram</a>.</p>
            </div>
        </div>
    </div>
</div>
@endsection
