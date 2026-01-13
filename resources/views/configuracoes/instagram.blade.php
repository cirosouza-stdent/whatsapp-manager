@extends('layouts.app')

@section('title', 'Configurações do Instagram - WhatsApp Manager')
@section('header', 'Configurações do Instagram')

@section('content')
<div class="max-w-3xl mx-auto">
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-home mr-1"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                    <span class="text-gray-500">Configurações</span>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                    <span class="text-gray-700 font-medium">Instagram</span>
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

    @if (session('error'))
        <div class="mb-4 rounded-md bg-red-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-times-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <div class="flex items-center">
                <div class="rounded-md bg-gradient-to-br from-purple-500 to-pink-500 p-2">
                    <i class="fab fa-instagram text-white text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-gray-900">API do Instagram</h3>
                    <p class="text-sm text-gray-500">Configure as credenciais para integração com o Instagram.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('configuracoes.instagram.salvar') }}" method="POST" class="px-4 py-5 sm:p-6">
            @csrf

            <div class="space-y-6">
                <div class="bg-pink-50 border border-pink-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-pink-400"></i>
                        </div>
                        <div class="ml-3 text-sm text-pink-700">
                            <p><strong>Nota:</strong> A API do Instagram usa as mesmas credenciais do Facebook (Meta). Você precisa de um App do Facebook com permissões do Instagram Graph API.</p>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="instagram_app_id" class="block text-sm font-medium text-gray-700">
                        App ID
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-hashtag text-gray-400"></i>
                        </div>
                        <input type="text" name="instagram_app_id" id="instagram_app_id"
                               value="{{ old('instagram_app_id', env('INSTAGRAM_APP_ID')) }}"
                               placeholder="123456789012345"
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500 sm:text-sm">
                    </div>
                    <p class="mt-1 text-sm text-gray-500">ID do aplicativo no Meta for Developers</p>
                </div>

                <div>
                    <label for="instagram_app_secret" class="block text-sm font-medium text-gray-700">
                        App Secret
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-key text-gray-400"></i>
                        </div>
                        <input type="password" name="instagram_app_secret" id="instagram_app_secret"
                               value="{{ old('instagram_app_secret', env('INSTAGRAM_APP_SECRET')) }}"
                               placeholder="••••••••••••••••••••••••"
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500 sm:text-sm">
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Chave secreta do aplicativo</p>
                </div>

                <div>
                    <label for="instagram_access_token" class="block text-sm font-medium text-gray-700">
                        Access Token
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" name="instagram_access_token" id="instagram_access_token"
                               value="{{ old('instagram_access_token', env('INSTAGRAM_ACCESS_TOKEN')) }}"
                               placeholder="••••••••••••••••••••••••"
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500 sm:text-sm">
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Token de longa duração para acessar a API</p>
                </div>

                <div>
                    <label for="instagram_business_id" class="block text-sm font-medium text-gray-700">
                        Instagram Business Account ID
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fab fa-instagram text-gray-400"></i>
                        </div>
                        <input type="text" name="instagram_business_id" id="instagram_business_id"
                               value="{{ old('instagram_business_id', env('INSTAGRAM_BUSINESS_ID')) }}"
                               placeholder="17841400000000000"
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500 sm:text-sm">
                    </div>
                    <p class="mt-1 text-sm text-gray-500">ID da conta comercial do Instagram</p>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <button type="button" onclick="testarConexao()"
                        class="inline-flex items-center rounded-md bg-gray-100 px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-200 transition-colors">
                    <i class="fas fa-plug mr-2"></i>
                    Testar Conexão
                </button>
                <button type="submit"
                        class="inline-flex items-center rounded-md bg-gradient-to-r from-purple-500 to-pink-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:from-purple-600 hover:to-pink-600 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Salvar Configurações
                </button>
            </div>
        </form>
    </div>

    <div class="mt-6 bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Como obter as credenciais</h3>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <ol class="list-decimal list-inside space-y-3 text-sm text-gray-600">
                <li>Acesse <a href="https://developers.facebook.com" target="_blank" class="text-pink-600 hover:text-pink-800 font-medium">Meta for Developers</a></li>
                <li>Crie ou selecione um aplicativo</li>
                <li>Adicione o produto "Instagram Graph API"</li>
                <li>Configure o Login do Facebook para obter permissões</li>
                <li>Vincule uma conta comercial do Instagram a uma Página do Facebook</li>
                <li>Gere um token de acesso de longa duração</li>
                <li>Copie o App ID, App Secret e Access Token para cá</li>
            </ol>
        </div>
    </div>
</div>

<script>
function testarConexao() {
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Testando...';
    btn.disabled = true;

    fetch('{{ route('configuracoes.instagram.testar') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✓ Conexão realizada com sucesso!\n\nConta: ' + (data.username || 'N/A'));
        } else {
            alert('✗ Erro na conexão:\n' + data.message);
        }
    })
    .catch(error => {
        alert('✗ Erro ao testar conexão:\n' + error.message);
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}
</script>
@endsection
