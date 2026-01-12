@extends('layouts.app')

@section('title', 'QR Code - ' . $instancia->nome . ' - WhatsApp Manager')
@section('header', 'Conectar WhatsApp')

@section('content')
<div x-data="qrCodePage()" x-init="startPolling()" class="max-w-2xl mx-auto">
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
                    <span class="text-gray-700 font-medium">QR Code</span>
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

    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 text-center">
            <h3 class="text-lg font-medium text-gray-900">Escaneie o QR Code</h3>
            <p class="mt-1 text-sm text-gray-500">
                Abra o WhatsApp no seu celular e escaneie o código abaixo
            </p>
        </div>

        <div class="px-4 py-8 sm:p-8">
            <!-- Status de conexão -->
            <div x-show="connected" class="text-center py-8">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-green-100 mb-4">
                    <i class="fas fa-check text-green-600 text-2xl"></i>
                </div>
                <h4 class="text-lg font-medium text-gray-900 mb-2">WhatsApp Conectado!</h4>
                <p class="text-sm text-gray-500 mb-4">Sua instância está pronta para uso.</p>
                <a href="{{ route('instancias.show', $instancia) }}" 
                   class="inline-flex items-center rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 transition-colors">
                    <i class="fas fa-arrow-right mr-2"></i>
                    Ir para a Instância
                </a>
            </div>

            <!-- QR Code -->
            <div x-show="!connected" class="text-center">
                @if($qrCode)
                    <div class="inline-block p-4 bg-white rounded-lg shadow-sm border-2 border-gray-200">
                        <img src="data:image/png;base64,{{ $qrCode }}" 
                             alt="QR Code WhatsApp" 
                             class="w-64 h-64 mx-auto"
                             x-show="!loading">
                        <div x-show="loading" class="w-64 h-64 flex items-center justify-center">
                            <i class="fas fa-spinner fa-spin text-4xl text-gray-400"></i>
                        </div>
                    </div>
                @elseif($error)
                    <div class="rounded-md bg-red-50 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-times-circle text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">{{ $error }}</p>
                            </div>
                        </div>
                    </div>
                    <form action="{{ route('instancias.connect', $instancia) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 transition-colors">
                            <i class="fas fa-sync-alt mr-2"></i>
                            Tentar Novamente
                        </button>
                    </form>
                @else
                    <div class="w-64 h-64 mx-auto bg-gray-100 rounded-lg flex items-center justify-center">
                        <div class="text-center">
                            <i class="fas fa-qrcode text-4xl text-gray-400 mb-2"></i>
                            <p class="text-sm text-gray-500">Gerando QR Code...</p>
                        </div>
                    </div>
                @endif

                <!-- Instruções -->
                <div class="mt-8 text-left max-w-md mx-auto">
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Como conectar:</h4>
                    <ol class="space-y-3">
                        <li class="flex items-start">
                            <span class="flex-shrink-0 flex h-6 w-6 items-center justify-center rounded-full bg-green-100 text-green-600 text-xs font-medium mr-3">1</span>
                            <span class="text-sm text-gray-600">Abra o WhatsApp no seu celular</span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 flex h-6 w-6 items-center justify-center rounded-full bg-green-100 text-green-600 text-xs font-medium mr-3">2</span>
                            <span class="text-sm text-gray-600">Toque em <strong>Menu</strong> ou <strong>Configurações</strong></span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 flex h-6 w-6 items-center justify-center rounded-full bg-green-100 text-green-600 text-xs font-medium mr-3">3</span>
                            <span class="text-sm text-gray-600">Selecione <strong>Aparelhos conectados</strong></span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 flex h-6 w-6 items-center justify-center rounded-full bg-green-100 text-green-600 text-xs font-medium mr-3">4</span>
                            <span class="text-sm text-gray-600">Toque em <strong>Conectar um aparelho</strong></span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 flex h-6 w-6 items-center justify-center rounded-full bg-green-100 text-green-600 text-xs font-medium mr-3">5</span>
                            <span class="text-sm text-gray-600">Aponte seu celular para esta tela para escanear o código</span>
                        </li>
                    </ol>
                </div>

                <!-- Timer e refresh -->
                <div class="mt-6 text-center">
                    <p class="text-xs text-gray-500 mb-2">
                        <i class="fas fa-clock mr-1"></i>
                        O QR Code expira em <span x-text="timer" class="font-medium">60</span> segundos
                    </p>
                    <button @click="refreshQrCode()" 
                            :disabled="loading"
                            class="text-sm text-green-600 hover:text-green-800 disabled:opacity-50">
                        <i class="fas fa-sync-alt mr-1" :class="{ 'fa-spin': loading }"></i>
                        Atualizar QR Code
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Botão voltar -->
    <div class="mt-4 text-center">
        <a href="{{ route('instancias.show', $instancia) }}" class="text-sm text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-1"></i>
            Voltar para detalhes da instância
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
function qrCodePage() {
    return {
        connected: false,
        loading: false,
        timer: 60,
        pollingInterval: null,
        timerInterval: null,

        startPolling() {
            // Verifica status a cada 3 segundos
            this.pollingInterval = setInterval(() => {
                this.checkStatus();
            }, 3000);

            // Timer countdown
            this.timerInterval = setInterval(() => {
                if (this.timer > 0) {
                    this.timer--;
                } else {
                    this.refreshQrCode();
                }
            }, 1000);
        },

        async checkStatus() {
            try {
                const response = await fetch('{{ route('instancias.status', $instancia) }}');
                const data = await response.json();
                
                if (data.status === 'online') {
                    this.connected = true;
                    clearInterval(this.pollingInterval);
                    clearInterval(this.timerInterval);
                }
            } catch (error) {
                console.error('Erro ao verificar status:', error);
            }
        },

        async refreshQrCode() {
            this.loading = true;
            this.timer = 60;

            try {
                const response = await fetch('{{ route('instancias.qrcode', $instancia) }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                const data = await response.json();
                
                if (data.success && data.qr_code) {
                    // Recarrega a página para atualizar o QR Code
                    window.location.reload();
                }
            } catch (error) {
                console.error('Erro ao atualizar QR Code:', error);
            } finally {
                this.loading = false;
            }
        }
    };
}
</script>
@endpush
