@extends('layouts.app')

@section('title', $instancia->nome . ' - WhatsApp Manager')
@section('header', 'Detalhes da Instância')

@section('content')
<div x-data="instanciaShow()" x-init="startStatusPolling()">
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

    @if (session('warning'))
        <div class="mb-4 rounded-md bg-yellow-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-yellow-800">{{ session('warning') }}</p>
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

    <!-- Cabeçalho com informações principais -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="fab fa-whatsapp text-green-600 text-2xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h2 class="text-xl font-bold text-gray-900">{{ $instancia->nome }}</h2>
                    <div class="flex items-center mt-1">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                              :class="{
                                  'bg-green-100 text-green-800': status === 'online',
                                  'bg-yellow-100 text-yellow-800': status === 'connecting',
                                  'bg-blue-100 text-blue-800': status === 'qr_pending',
                                  'bg-red-100 text-red-800': status === 'offline'
                              }">
                            <span class="h-1.5 w-1.5 rounded-full mr-1.5"
                                  :class="{
                                      'bg-green-400': status === 'online',
                                      'bg-yellow-400': status === 'connecting',
                                      'bg-blue-400': status === 'qr_pending',
                                      'bg-red-400': status === 'offline'
                                  }"></span>
                            <span x-text="statusText">{{ $instancia->status_text }}</span>
                        </span>
                        @if($instancia->telefone)
                            <span class="ml-3 text-sm text-gray-500">
                                <i class="fas fa-phone mr-1"></i>
                                {{ $instancia->telefone }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="mt-4 sm:mt-0 flex flex-wrap gap-2">
                @if($instancia->status !== 'online')
                    <form action="{{ route('instancias.connect', $instancia) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 transition-colors">
                            <i class="fas fa-qrcode mr-2"></i>
                            Conectar
                        </button>
                    </form>
                @else
                    <form action="{{ route('instancias.disconnect', $instancia) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 transition-colors">
                            <i class="fas fa-power-off mr-2"></i>
                            Desconectar
                        </button>
                    </form>
                    <form action="{{ route('instancias.restart', $instancia) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center rounded-md bg-yellow-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-yellow-500 transition-colors">
                            <i class="fas fa-sync-alt mr-2"></i>
                            Reiniciar
                        </button>
                    </form>
                @endif
                <a href="{{ route('instancias.edit', $instancia) }}" class="inline-flex items-center rounded-md bg-gray-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500 transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Editar
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Informações da instância -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informações</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nome</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $instancia->nome }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Telefone</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $instancia->telefone ?? 'Não definido' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Última conexão</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $instancia->last_connected_at ? $instancia->last_connected_at->format('d/m/Y H:i') : 'Nunca conectado' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Criado em</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $instancia->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Token da Instância</dt>
                        <dd class="mt-1 flex items-center">
                            <code class="text-xs bg-gray-100 px-2 py-1 rounded break-all">{{ $instancia->token }}</code>
                            <button type="button" onclick="navigator.clipboard.writeText('{{ $instancia->token }}')" class="ml-2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-copy"></i>
                            </button>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Enviar mensagem de teste -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Enviar Mensagem de Teste</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <form @submit.prevent="sendTestMessage">
                    <div class="space-y-4">
                        <div>
                            <label for="telefone" class="block text-sm font-medium text-gray-700">Telefone</label>
                            <input type="text" id="telefone" x-model="testPhone" 
                                   placeholder="5511999999999"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm px-3 py-2 border">
                        </div>
                        <div>
                            <label for="mensagem" class="block text-sm font-medium text-gray-700">Mensagem</label>
                            <textarea id="mensagem" x-model="testMessage" rows="3" 
                                      placeholder="Digite sua mensagem de teste..."
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm px-3 py-2 border"></textarea>
                        </div>
                        <div class="flex items-center justify-between">
                            <button type="submit" 
                                    :disabled="sending || status !== 'online'"
                                    class="inline-flex items-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                <i class="fas fa-paper-plane mr-2"></i>
                                <span x-text="sending ? 'Enviando...' : 'Enviar'">Enviar</span>
                            </button>
                            <span x-show="testResult" 
                                  :class="testResult?.success ? 'text-green-600' : 'text-red-600'"
                                  class="text-sm">
                                <span x-text="testResult?.message"></span>
                            </span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Logs de mensagens -->
    <div class="mt-6 bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900">Últimas Mensagens</h3>
            <span class="text-sm text-gray-500">Últimas 20 mensagens</span>
        </div>
        <div class="overflow-hidden">
            @if($instancia->logsDeMensagens->count() > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach($instancia->logsDeMensagens as $log)
                        <li class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center min-w-0 flex-1">
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full {{ $log->direcao === 'enviada' ? 'bg-green-100' : 'bg-blue-100' }}">
                                            <i class="fas {{ $log->direcao === 'enviada' ? 'fa-arrow-right text-green-600' : 'fa-arrow-left text-blue-600' }} text-sm"></i>
                                        </span>
                                    </div>
                                    <div class="ml-4 min-w-0 flex-1">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $log->telefone_destino }}
                                        </p>
                                        <p class="text-sm text-gray-500 truncate">
                                            {{ Str::limit($log->conteudo, 100) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="ml-4 flex flex-col items-end">
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                        {{ $log->status === 'enviada' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $log->status === 'recebida' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $log->status === 'falha' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $log->status === 'pendente' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                        {{ ucfirst($log->status) }}
                                    </span>
                                    <span class="mt-1 text-xs text-gray-500">
                                        {{ $log->created_at->format('d/m H:i') }}
                                    </span>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="px-4 py-12 text-center text-gray-500">
                    <i class="fas fa-comments text-4xl mb-2"></i>
                    <p>Nenhuma mensagem registrada</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal de confirmação para deletar -->
    <div x-data="{ showDeleteModal: false }">
        <button @click="showDeleteModal = true" class="mt-6 text-sm text-red-600 hover:text-red-800">
            <i class="fas fa-trash-alt mr-1"></i>
            Excluir esta instância
        </button>

        <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="showDeleteModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                
                <div x-show="showDeleteModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Excluir instância</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Tem certeza que deseja excluir a instância <strong>{{ $instancia->nome }}</strong>? 
                                    Esta ação não pode ser desfeita e todos os logs de mensagens serão perdidos.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <form action="{{ route('instancias.destroy', $instancia) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
                                Excluir
                            </button>
                        </form>
                        <button type="button" @click="showDeleteModal = false" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function instanciaShow() {
    return {
        status: '{{ $instancia->status }}',
        statusText: '{{ $instancia->status_text }}',
        testPhone: '',
        testMessage: '',
        sending: false,
        testResult: null,
        pollingInterval: null,

        startStatusPolling() {
            this.pollingInterval = setInterval(() => {
                this.checkStatus();
            }, 5000);
        },

        async checkStatus() {
            try {
                const response = await fetch('{{ route('instancias.status', $instancia) }}');
                const data = await response.json();
                if (data.success) {
                    this.status = data.status;
                    this.statusText = data.status_text;
                }
            } catch (error) {
                console.error('Erro ao verificar status:', error);
            }
        },

        async sendTestMessage() {
            if (!this.testPhone || !this.testMessage) {
                this.testResult = { success: false, message: 'Preencha todos os campos' };
                return;
            }

            this.sending = true;
            this.testResult = null;

            try {
                const response = await fetch('{{ route('instancias.send-test', $instancia) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        telefone: this.testPhone,
                        mensagem: this.testMessage
                    })
                });

                const data = await response.json();
                this.testResult = {
                    success: data.success,
                    message: data.success ? 'Mensagem enviada!' : (data.error || 'Erro ao enviar')
                };

                if (data.success) {
                    this.testPhone = '';
                    this.testMessage = '';
                }
            } catch (error) {
                this.testResult = { success: false, message: 'Erro de conexão' };
            } finally {
                this.sending = false;
            }
        }
    };
}
</script>
@endpush
