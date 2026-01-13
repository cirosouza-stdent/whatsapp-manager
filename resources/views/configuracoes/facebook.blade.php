@extends('layouts.app')

@section('title', 'Configurações Facebook - WhatsApp Manager')
@section('header', 'Configurações do Facebook')

@section('content')
<div x-data="facebookConfig()" class="max-w-4xl mx-auto">
    <!-- Breadcrumb -->
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
                    <span class="text-gray-700 font-medium">Facebook</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Mensagens de feedback -->
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

    <!-- Card de informações -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fab fa-facebook text-blue-600 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Sobre a Integração com Facebook</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <p>Para conectar o sistema ao Facebook, você precisa criar um aplicativo no <strong>Facebook Developers</strong> e obter as credenciais de API.</p>
                    <p class="mt-2">Com a integração você poderá:</p>
                    <ul class="list-disc list-inside mt-1 space-y-1">
                        <li>Publicar posts em suas páginas</li>
                        <li>Agendar publicações</li>
                        <li>Monitorar engajamento</li>
                        <li>Gerenciar múltiplas páginas</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulário de configuração -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fab fa-facebook text-blue-600 mr-2"></i>
                Configurações da API do Facebook
            </h3>
            <p class="mt-1 text-sm text-gray-500">Configure as credenciais do seu aplicativo Facebook.</p>
        </div>

        <form action="{{ route('configuracoes.facebook.salvar') }}" method="POST" class="px-4 py-5 sm:p-6">
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

            <div class="space-y-6">
                <!-- App ID -->
                <div>
                    <label for="app_id" class="block text-sm font-medium text-gray-700">
                        App ID <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-id-card text-gray-400"></i>
                        </div>
                        <input type="text" name="app_id" id="app_id" required
                               x-model="appId"
                               value="{{ old('app_id', $config['app_id']) }}"
                               placeholder="123456789012345"
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('app_id') border-red-300 @enderror">
                    </div>
                    <p class="mt-1 text-sm text-gray-500">ID do seu aplicativo no Facebook Developers</p>
                    @error('app_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- App Secret -->
                <div>
                    <label for="app_secret" class="block text-sm font-medium text-gray-700">
                        App Secret <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-key text-gray-400"></i>
                        </div>
                        <input :type="showSecret ? 'text' : 'password'" name="app_secret" id="app_secret" required
                               x-model="appSecret"
                               value="{{ old('app_secret', $config['app_secret']) }}"
                               placeholder="Chave secreta do aplicativo"
                               class="block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('app_secret') border-red-300 @enderror">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" @click="showSecret = !showSecret" class="text-gray-400 hover:text-gray-600">
                                <i :class="showSecret ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </button>
                        </div>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Chave secreta do aplicativo (encontrada em Configurações > Básico)</p>
                    @error('app_secret')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Access Token -->
                <div>
                    <label for="access_token" class="block text-sm font-medium text-gray-700">
                        Access Token (Longa Duração)
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-ticket-alt text-gray-400"></i>
                        </div>
                        <input :type="showToken ? 'text' : 'password'" name="access_token" id="access_token"
                               x-model="accessToken"
                               value="{{ old('access_token', $config['access_token']) }}"
                               placeholder="Token de acesso do usuário"
                               class="block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" @click="showToken = !showToken" class="text-gray-400 hover:text-gray-600">
                                <i :class="showToken ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </button>
                        </div>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Token de longa duração para acessar a API. Gere usando o Graph API Explorer.</p>
                </div>

                <!-- Page Access Token -->
                <div>
                    <label for="page_access_token" class="block text-sm font-medium text-gray-700">
                        Page Access Token
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-file-alt text-gray-400"></i>
                        </div>
                        <input :type="showPageToken ? 'text' : 'password'" name="page_access_token" id="page_access_token"
                               value="{{ old('page_access_token', $config['page_access_token']) }}"
                               placeholder="Token de acesso da página"
                               class="block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" @click="showPageToken = !showPageToken" class="text-gray-400 hover:text-gray-600">
                                <i :class="showPageToken ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </button>
                        </div>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Token específico para publicar na página. Obtido após autenticação OAuth.</p>
                </div>

                <!-- Callback URL (somente leitura) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        URL de Callback OAuth
                    </label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <input type="text" readonly
                               value="{{ $config['callback_url'] }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-l-md bg-gray-50 text-gray-500 sm:text-sm">
                        <button type="button" 
                                onclick="navigator.clipboard.writeText('{{ $config['callback_url'] }}')"
                                class="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 rounded-r-md bg-gray-50 text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Adicione esta URL nas configurações do seu app Facebook (Produtos > Login do Facebook > Configurações)</p>
                </div>

                <!-- Graph API Version -->
                <div>
                    <label for="graph_version" class="block text-sm font-medium text-gray-700">
                        Versão da Graph API
                    </label>
                    <select name="graph_version" id="graph_version"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border">
                        <option value="v18.0" {{ ($config['graph_version'] ?? 'v18.0') === 'v18.0' ? 'selected' : '' }}>v18.0 (Recomendado)</option>
                        <option value="v17.0" {{ ($config['graph_version'] ?? '') === 'v17.0' ? 'selected' : '' }}>v17.0</option>
                        <option value="v16.0" {{ ($config['graph_version'] ?? '') === 'v16.0' ? 'selected' : '' }}>v16.0</option>
                    </select>
                </div>
            </div>

            <!-- Botões de ação -->
            <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                <button type="button"
                        @click="testarConexao"
                        :disabled="testando || !appId || !appSecret"
                        class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    <i class="fas mr-2" :class="testando ? 'fa-spinner fa-spin' : 'fa-plug'"></i>
                    <span x-text="testando ? 'Testando...' : 'Testar Conexão'">Testar Conexão</span>
                </button>

                <button type="submit"
                        class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Salvar Configurações
                </button>
            </div>

            <!-- Resultado do teste -->
            <div x-show="testeResultado" x-cloak class="mt-4">
                <div class="rounded-md p-4"
                     :class="testeResultado?.success ? 'bg-green-50' : 'bg-red-50'">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas" :class="testeResultado?.success ? 'fa-check-circle text-green-400' : 'fa-times-circle text-red-400'"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium" :class="testeResultado?.success ? 'text-green-800' : 'text-red-800'" x-text="testeResultado?.message"></p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Guia de configuração -->
    <div class="mt-6 bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-book text-gray-400 mr-2"></i>
                Guia de Configuração
            </h3>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="prose prose-sm max-w-none">
                <h4 class="text-base font-medium text-gray-900 mb-3">Passo 1: Criar Aplicativo no Facebook</h4>
                <ol class="list-decimal list-inside space-y-2 text-gray-600 mb-6">
                    <li>Acesse <a href="https://developers.facebook.com" target="_blank" class="text-blue-600 hover:text-blue-800">developers.facebook.com</a></li>
                    <li>Clique em "Meus Aplicativos" e depois "Criar Aplicativo"</li>
                    <li>Selecione "Empresa" como tipo de aplicativo</li>
                    <li>Preencha o nome do aplicativo e seu email</li>
                    <li>Anote o <strong>App ID</strong> e <strong>App Secret</strong> gerados</li>
                </ol>

                <h4 class="text-base font-medium text-gray-900 mb-3">Passo 2: Configurar Login do Facebook</h4>
                <ol class="list-decimal list-inside space-y-2 text-gray-600 mb-6">
                    <li>No painel do app, clique em "Adicionar Produto"</li>
                    <li>Selecione "Login do Facebook" e clique em "Configurar"</li>
                    <li>Em "URIs de redirecionamento OAuth válidos", adicione a URL de callback acima</li>
                    <li>Salve as alterações</li>
                </ol>

                <h4 class="text-base font-medium text-gray-900 mb-3">Passo 3: Obter Access Token</h4>
                <ol class="list-decimal list-inside space-y-2 text-gray-600 mb-6">
                    <li>Acesse o <a href="https://developers.facebook.com/tools/explorer" target="_blank" class="text-blue-600 hover:text-blue-800">Graph API Explorer</a></li>
                    <li>Selecione seu aplicativo</li>
                    <li>Adicione as permissões: <code class="bg-gray-100 px-1 rounded">pages_manage_posts</code>, <code class="bg-gray-100 px-1 rounded">pages_read_engagement</code></li>
                    <li>Gere o token e converta para token de longa duração</li>
                </ol>

                <h4 class="text-base font-medium text-gray-900 mb-3">Variáveis de Ambiente</h4>
                <pre class="bg-gray-800 text-gray-100 p-4 rounded-md text-sm overflow-x-auto">FACEBOOK_APP_ID=seu_app_id
FACEBOOK_APP_SECRET=seu_app_secret
FACEBOOK_ACCESS_TOKEN=seu_token_longa_duracao
FACEBOOK_PAGE_ACCESS_TOKEN=token_da_pagina
FACEBOOK_GRAPH_VERSION=v18.0</pre>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function facebookConfig() {
    return {
        appId: '{{ old('app_id', $config['app_id']) }}',
        appSecret: '{{ old('app_secret', $config['app_secret']) }}',
        accessToken: '{{ old('access_token', $config['access_token']) }}',
        showSecret: false,
        showToken: false,
        showPageToken: false,
        testando: false,
        testeResultado: null,

        async testarConexao() {
            if (!this.appId || !this.appSecret) return;

            this.testando = true;
            this.testeResultado = null;

            try {
                const response = await fetch('{{ route('configuracoes.facebook.testar') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        app_id: this.appId,
                        app_secret: this.appSecret,
                        access_token: this.accessToken
                    })
                });

                const data = await response.json();
                this.testeResultado = data;

            } catch (error) {
                this.testeResultado = {
                    success: false,
                    message: 'Erro ao testar conexão: ' + error.message
                };
            } finally {
                this.testando = false;
            }
        }
    };
}
</script>
@endpush
