@extends('layouts.app')

@section('title', 'Configurações WhatsApp - WhatsApp Manager')
@section('header', 'Configurações do WhatsApp')

@section('content')
<div x-data="whatsappConfig()" class="max-w-4xl mx-auto">
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
                    <span class="text-gray-700 font-medium">WhatsApp</span>
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
                <i class="fas fa-info-circle text-blue-400 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Sobre a Integração com WhatsApp</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <p>Este sistema utiliza uma API de WhatsApp para enviar e receber mensagens. Você precisa configurar os dados de conexão com sua API.</p>
                    <p class="mt-2">APIs compatíveis:</p>
                    <ul class="list-disc list-inside mt-1 space-y-1">
                        <li><strong>Evolution API</strong> - API open source auto-hospedada</li>
                        <li><strong>CodeChat</strong> - Solução de WhatsApp multi-dispositivo</li>
                        <li><strong>WhatsApp Business API</strong> - API oficial do Meta</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulário de configuração -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fab fa-whatsapp text-green-500 mr-2"></i>
                Configurações da API
            </h3>
            <p class="mt-1 text-sm text-gray-500">Configure os dados de conexão com sua API de WhatsApp.</p>
        </div>

        <form action="{{ route('configuracoes.whatsapp.salvar') }}" method="POST" class="px-4 py-5 sm:p-6">
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
                <!-- URL da API -->
                <div>
                    <label for="api_url" class="block text-sm font-medium text-gray-700">
                        URL da API <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-link text-gray-400"></i>
                        </div>
                        <input type="url" name="api_url" id="api_url" required
                               x-model="apiUrl"
                               value="{{ old('api_url', $config['api_url']) }}"
                               placeholder="https://sua-api.exemplo.com"
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm @error('api_url') border-red-300 @enderror">
                    </div>
                    <p class="mt-1 text-sm text-gray-500">URL base da sua API de WhatsApp (sem barra no final)</p>
                    @error('api_url')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- API Key -->
                <div>
                    <label for="api_key" class="block text-sm font-medium text-gray-700">
                        API Key <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-key text-gray-400"></i>
                        </div>
                        <input :type="showApiKey ? 'text' : 'password'" name="api_key" id="api_key" required
                               x-model="apiKey"
                               value="{{ old('api_key', $config['api_key']) }}"
                               placeholder="Sua chave de API"
                               class="block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm @error('api_key') border-red-300 @enderror">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" @click="showApiKey = !showApiKey" class="text-gray-400 hover:text-gray-600">
                                <i :class="showApiKey ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </button>
                        </div>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Chave de autenticação fornecida pela sua API</p>
                    @error('api_key')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Timeout -->
                <div>
                    <label for="timeout" class="block text-sm font-medium text-gray-700">
                        Timeout (segundos) <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-clock text-gray-400"></i>
                        </div>
                        <input type="number" name="timeout" id="timeout" required
                               value="{{ old('timeout', $config['timeout']) }}"
                               min="5" max="120"
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm @error('timeout') border-red-300 @enderror">
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Tempo máximo de espera para resposta da API (5-120 segundos)</p>
                    @error('timeout')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Webhook URL (somente leitura) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        URL do Webhook (para receber mensagens)
                    </label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <input type="text" readonly
                               value="{{ $config['webhook_url'] }}/{token}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-l-md bg-gray-50 text-gray-500 sm:text-sm">
                        <button type="button" 
                                onclick="navigator.clipboard.writeText('{{ $config['webhook_url'] }}/{token}')"
                                class="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 rounded-r-md bg-gray-50 text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Configure esta URL na sua API para receber mensagens. Substitua <code class="bg-gray-100 px-1 rounded">{token}</code> pelo token da instância.</p>
                </div>
            </div>

            <!-- Botões de ação -->
            <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                <button type="button"
                        @click="testarConexao"
                        :disabled="testando || !apiUrl || !apiKey"
                        class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    <i class="fas mr-2" :class="testando ? 'fa-spinner fa-spin' : 'fa-plug'"></i>
                    <span x-text="testando ? 'Testando...' : 'Testar Conexão'">Testar Conexão</span>
                </button>

                <button type="submit"
                        class="inline-flex items-center rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600 transition-colors">
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

    <!-- Documentação adicional -->
    <div class="mt-6 bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-book text-gray-400 mr-2"></i>
                Guia de Configuração
            </h3>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="prose prose-sm max-w-none">
                <h4 class="text-base font-medium text-gray-900 mb-3">Evolution API</h4>
                <ol class="list-decimal list-inside space-y-2 text-gray-600 mb-6">
                    <li>Instale a Evolution API em seu servidor ou use um serviço hospedado</li>
                    <li>Obtenha a URL da API (ex: <code class="bg-gray-100 px-1 rounded">https://api.seudominio.com</code>)</li>
                    <li>Gere uma API Key no painel da Evolution API</li>
                    <li>Cole as informações nos campos acima</li>
                    <li>Clique em "Testar Conexão" para verificar</li>
                </ol>

                <h4 class="text-base font-medium text-gray-900 mb-3">Variáveis de Ambiente</h4>
                <p class="text-gray-600 mb-3">As configurações são salvas no arquivo <code class="bg-gray-100 px-1 rounded">.env</code> do sistema:</p>
                <pre class="bg-gray-800 text-gray-100 p-4 rounded-md text-sm overflow-x-auto">WHATSAPP_API_URL=https://sua-api.exemplo.com
WHATSAPP_API_KEY=sua-chave-secreta
WHATSAPP_TIMEOUT=30</pre>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function whatsappConfig() {
    return {
        apiUrl: '{{ old('api_url', $config['api_url']) }}',
        apiKey: '{{ old('api_key', $config['api_key']) }}',
        showApiKey: false,
        testando: false,
        testeResultado: null,

        async testarConexao() {
            if (!this.apiUrl || !this.apiKey) return;

            this.testando = true;
            this.testeResultado = null;

            try {
                const response = await fetch('{{ route('configuracoes.whatsapp.testar') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        api_url: this.apiUrl,
                        api_key: this.apiKey
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
