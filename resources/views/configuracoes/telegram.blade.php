@extends('layouts.app')

@section('title', 'Configurações do Telegram - WhatsApp Manager')
@section('header', 'Configurações do Telegram')

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
                    <span class="text-gray-700 font-medium">Telegram</span>
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
                <div class="rounded-md bg-blue-500 p-2">
                    <i class="fab fa-telegram text-white text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-gray-900">Bot do Telegram</h3>
                    <p class="text-sm text-gray-500">Configure o token do bot para integração com o Telegram.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('configuracoes.telegram.salvar') }}" method="POST" class="px-4 py-5 sm:p-6">
            @csrf

            <div class="space-y-6">
                <div>
                    <label for="telegram_bot_token" class="block text-sm font-medium text-gray-700">
                        Bot Token <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-key text-gray-400"></i>
                        </div>
                        <input type="password" name="telegram_bot_token" id="telegram_bot_token"
                               value="{{ old('telegram_bot_token', env('TELEGRAM_BOT_TOKEN')) }}"
                               placeholder="123456789:ABCdefGHIjklMNOpqrsTUVwxyz"
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Token fornecido pelo @BotFather</p>
                </div>

                <div>
                    <label for="telegram_bot_username" class="block text-sm font-medium text-gray-700">
                        Username do Bot
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-400">@</span>
                        </div>
                        <input type="text" name="telegram_bot_username" id="telegram_bot_username"
                               value="{{ old('telegram_bot_username', env('TELEGRAM_BOT_USERNAME')) }}"
                               placeholder="meubot"
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Username do bot (opcional)</p>
                </div>

                <div>
                    <label for="telegram_webhook_url" class="block text-sm font-medium text-gray-700">
                        Webhook URL
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-link text-gray-400"></i>
                        </div>
                        <input type="url" name="telegram_webhook_url" id="telegram_webhook_url"
                               value="{{ old('telegram_webhook_url', env('TELEGRAM_WEBHOOK_URL', url('/api/telegram/webhook'))) }}"
                               placeholder="https://seusite.com/api/telegram/webhook"
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <p class="mt-1 text-sm text-gray-500">URL para receber atualizações do Telegram (requer HTTPS)</p>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="telegram_webhook_enabled" name="telegram_webhook_enabled"
                           {{ env('TELEGRAM_WEBHOOK_ENABLED') ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="telegram_webhook_enabled" class="ml-2 block text-sm text-gray-900">
                        Habilitar Webhook
                    </label>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <button type="button" onclick="testarConexao()"
                        class="inline-flex items-center rounded-md bg-gray-100 px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-200 transition-colors">
                    <i class="fas fa-plug mr-2"></i>
                    Testar Conexão
                </button>
                <button type="submit"
                        class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Salvar Configurações
                </button>
            </div>
        </form>
    </div>

    <div class="mt-6 bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Como criar um bot no Telegram</h3>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <ol class="list-decimal list-inside space-y-3 text-sm text-gray-600">
                <li>Abra o Telegram e pesquise por <a href="https://t.me/BotFather" target="_blank" class="text-blue-600 hover:text-blue-800 font-medium">@BotFather</a></li>
                <li>Envie o comando <code class="bg-gray-100 px-1 rounded">/newbot</code></li>
                <li>Siga as instruções para escolher um nome e username para o bot</li>
                <li>Copie o token gerado e cole no campo acima</li>
                <li>Para enviar mensagens em canais, adicione o bot como administrador do canal</li>
            </ol>
        </div>
    </div>

    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-lightbulb text-blue-400"></i>
            </div>
            <div class="ml-3 text-sm text-blue-700">
                <p class="font-medium mb-2">Comandos úteis do BotFather:</p>
                <ul class="space-y-1 text-xs">
                    <li><code>/setdescription</code> - Define a descrição do bot</li>
                    <li><code>/setabouttext</code> - Define o texto "Sobre"</li>
                    <li><code>/setcommands</code> - Define os comandos do bot</li>
                    <li><code>/setuserpic</code> - Define a foto do perfil</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function testarConexao() {
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Testando...';
    btn.disabled = true;

    fetch('{{ route('configuracoes.telegram.testar') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✓ Conexão realizada com sucesso!\n\nBot: @' + (data.username || 'N/A') + '\nNome: ' + (data.first_name || 'N/A'));
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
