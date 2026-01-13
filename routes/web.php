<?php

declare(strict_types=1);

use App\Http\Controllers\ConfiguracaoController;
use App\Http\Controllers\DashboardController;
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
