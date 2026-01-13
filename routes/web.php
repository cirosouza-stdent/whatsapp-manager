<?php

declare(strict_types=1);

use App\Http\Controllers\ConfiguracaoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FacebookController;
use App\Http\Controllers\InstagramController;
use App\Http\Controllers\TelegramController;
use App\Http\Controllers\InstanciaController;
use App\Http\Controllers\MensagemAgendadaController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rota publica para webhooks (sem autenticacao)
Route::post('/webhook/{token}', [WebhookController::class, 'handle'])
    ->name('webhook.handle');

// Rotas que requerem autenticacao
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Instancias WhatsApp
    Route::prefix('instancias')->name('instancias.')->group(function () {
        Route::get('/', [InstanciaController::class, 'index'])->name('index');
        Route::get('/create', [InstanciaController::class, 'create'])->name('create');
        Route::post('/', [InstanciaController::class, 'store'])->name('store');
        Route::get('/{instancia}', [InstanciaController::class, 'show'])->name('show');
        Route::get('/{instancia}/edit', [InstanciaController::class, 'edit'])->name('edit');
        Route::put('/{instancia}', [InstanciaController::class, 'update'])->name('update');
        Route::delete('/{instancia}', [InstanciaController::class, 'destroy'])->name('destroy');

        // Acoes especificas da instancia
        Route::get('/{instancia}/qrcode', [InstanciaController::class, 'qrCode'])->name('qrcode');
        Route::get('/{instancia}/status', [InstanciaController::class, 'status'])->name('status');
        Route::post('/{instancia}/connect', [InstanciaController::class, 'connect'])->name('connect');
        Route::post('/{instancia}/disconnect', [InstanciaController::class, 'disconnect'])->name('disconnect');
        Route::post('/{instancia}/restart', [InstanciaController::class, 'restart'])->name('restart');
        Route::post('/{instancia}/send-test', [InstanciaController::class, 'sendTest'])->name('send-test');
    });

    // Utilitario para gerar QR Code
    Route::post('/qrcode/generate', [InstanciaController::class, 'generateQrCode'])
        ->name('qrcode.generate');

    // Mensagens Agendadas
    Route::prefix('mensagens')->name('mensagens.')->group(function () {
        Route::get('/', [MensagemAgendadaController::class, 'index'])->name('index');
        Route::get('/create', [MensagemAgendadaController::class, 'create'])->name('create');
        Route::post('/', [MensagemAgendadaController::class, 'store'])->name('store');
        Route::get('/{mensagem}', [MensagemAgendadaController::class, 'show'])->name('show');
        Route::get('/{mensagem}/edit', [MensagemAgendadaController::class, 'edit'])->name('edit');
        Route::put('/{mensagem}', [MensagemAgendadaController::class, 'update'])->name('update');
        Route::delete('/{mensagem}', [MensagemAgendadaController::class, 'destroy'])->name('destroy');
        Route::post('/{mensagem}/cancel', [MensagemAgendadaController::class, 'cancel'])->name('cancel');
        Route::post('/{mensagem}/retry', [MensagemAgendadaController::class, 'retry'])->name('retry');
    });

    // Configurações
    Route::prefix('configuracoes')->name('configuracoes.')->group(function () {
        Route::get('/whatsapp', [ConfiguracaoController::class, 'whatsapp'])->name('whatsapp');
        Route::post('/whatsapp', [ConfiguracaoController::class, 'whatsappSalvar'])->name('whatsapp.salvar');
        Route::post('/whatsapp/testar', [ConfiguracaoController::class, 'whatsappTestar'])->name('whatsapp.testar');
        
        Route::get('/facebook', [ConfiguracaoController::class, 'facebook'])->name('facebook');
        Route::post('/facebook', [ConfiguracaoController::class, 'facebookSalvar'])->name('facebook.salvar');
        Route::post('/facebook/testar', [ConfiguracaoController::class, 'facebookTestar'])->name('facebook.testar');

        Route::get('/instagram', [ConfiguracaoController::class, 'instagram'])->name('instagram');
        Route::post('/instagram', [ConfiguracaoController::class, 'instagramSalvar'])->name('instagram.salvar');
        Route::post('/instagram/testar', [ConfiguracaoController::class, 'instagramTestar'])->name('instagram.testar');

        Route::get('/telegram', [ConfiguracaoController::class, 'telegram'])->name('telegram');
        Route::post('/telegram', [ConfiguracaoController::class, 'telegramSalvar'])->name('telegram.salvar');
        Route::post('/telegram/testar', [ConfiguracaoController::class, 'telegramTestar'])->name('telegram.testar');
    });

    // Facebook
    Route::prefix('facebook')->name('facebook.')->group(function () {
        Route::get('/', [FacebookController::class, 'index'])->name('index');
        Route::get('/paginas', [FacebookController::class, 'paginas'])->name('paginas');
        Route::get('/posts', [FacebookController::class, 'posts'])->name('posts');
        Route::get('/criar-post', [FacebookController::class, 'criarPost'])->name('criar-post');
        Route::post('/posts', [FacebookController::class, 'salvarPost'])->name('salvar-post');
    });

    // Instagram
    Route::prefix('instagram')->name('instagram.')->group(function () {
        Route::get('/', [InstagramController::class, 'index'])->name('index');
        Route::get('/contas', [InstagramController::class, 'contas'])->name('contas');
        Route::get('/posts', [InstagramController::class, 'posts'])->name('posts');
        Route::get('/criar-post', [InstagramController::class, 'criarPost'])->name('criar-post');
        Route::post('/posts', [InstagramController::class, 'salvarPost'])->name('salvar-post');
    });

    // Telegram
    Route::prefix('telegram')->name('telegram.')->group(function () {
        Route::get('/', [TelegramController::class, 'index'])->name('index');
        Route::get('/bots', [TelegramController::class, 'bots'])->name('bots');
        Route::get('/bots/criar', [TelegramController::class, 'criarBot'])->name('criar-bot');
        Route::post('/bots', [TelegramController::class, 'salvarBot'])->name('salvar-bot');
        Route::patch('/bots/{bot}/toggle', [TelegramController::class, 'toggleBot'])->name('toggle-bot');
        Route::delete('/bots/{bot}', [TelegramController::class, 'excluirBot'])->name('excluir-bot');
        Route::get('/canais', [TelegramController::class, 'canais'])->name('canais');
        Route::get('/mensagens', [TelegramController::class, 'mensagens'])->name('mensagens');
        Route::get('/criar-mensagem', [TelegramController::class, 'criarMensagem'])->name('criar-mensagem');
        Route::post('/mensagens', [TelegramController::class, 'enviarMensagem'])->name('enviar-mensagem');
    });
});

// Redireciona para login se nao autenticado
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function () {
    $credentials = request()->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (auth()->attempt($credentials, request()->boolean('remember'))) {
        request()->session()->regenerate();
        return redirect()->intended('dashboard');
    }

    return back()->withErrors([
        'email' => 'As credenciais informadas nao correspondem aos nossos registros.',
    ])->onlyInput('email');
})->name('login.submit');

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// Registro simples
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', function () {
    $validated = request()->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8|confirmed',
    ]);

    $user = \App\Models\User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => bcrypt($validated['password']),
    ]);

    auth()->login($user);

    return redirect()->route('dashboard');
})->name('register.submit');
