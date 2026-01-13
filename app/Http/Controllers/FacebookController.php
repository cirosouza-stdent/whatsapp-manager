<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller para gerenciamento do Facebook.
 */
class FacebookController extends Controller
{
    /**
     * Dashboard principal do Facebook.
     */
    public function index(): View
    {
        // Verifica se as configurações estão definidas
        $configurado = !empty(env('FACEBOOK_APP_ID')) && !empty(env('FACEBOOK_APP_SECRET'));

        $stats = [
            'paginas' => 0,
            'posts_agendados' => 0,
            'posts_publicados' => 0,
            'engajamento' => 0,
        ];

        return view('facebook.index', compact('configurado', 'stats'));
    }

    /**
     * Lista as páginas conectadas.
     */
    public function paginas(): View
    {
        $paginas = [];

        return view('facebook.paginas', compact('paginas'));
    }

    /**
     * Lista os posts agendados.
     */
    public function posts(): View
    {
        $posts = [];

        return view('facebook.posts', compact('posts'));
    }

    /**
     * Formulário para criar novo post.
     */
    public function criarPost(): View
    {
        $paginas = [];

        return view('facebook.criar-post', compact('paginas'));
    }

    /**
     * Armazena um novo post.
     */
    public function salvarPost(Request $request)
    {
        $validated = $request->validate([
            'pagina_id' => 'required|string',
            'mensagem' => 'required|string|max:5000',
            'agendado_para' => 'nullable|date|after:now',
            'imagem' => 'nullable|url',
        ]);

        // TODO: Implementar integração com API do Facebook

        return redirect()
            ->route('facebook.posts')
            ->with('success', 'Post agendado com sucesso!');
    }
}
