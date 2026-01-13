<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller para gerenciamento do Instagram.
 */
class InstagramController extends Controller
{
    /**
     * Dashboard principal do Instagram.
     */
    public function index(): View
    {
        $configurado = !empty(env('INSTAGRAM_ACCESS_TOKEN'));

        $stats = [
            'contas' => 0,
            'posts_agendados' => 0,
            'posts_publicados' => 0,
            'seguidores' => 0,
        ];

        return view('instagram.index', compact('configurado', 'stats'));
    }

    /**
     * Lista as contas conectadas.
     */
    public function contas(): View
    {
        $contas = [];

        return view('instagram.contas', compact('contas'));
    }

    /**
     * Lista os posts.
     */
    public function posts(): View
    {
        $posts = [];

        return view('instagram.posts', compact('posts'));
    }

    /**
     * Formulário para criar novo post.
     */
    public function criarPost(): View
    {
        $contas = [];

        return view('instagram.criar-post', compact('contas'));
    }

    /**
     * Armazena um novo post.
     */
    public function salvarPost(Request $request)
    {
        $validated = $request->validate([
            'conta_id' => 'required|string',
            'legenda' => 'required|string|max:2200',
            'imagem' => 'required|url',
            'agendado_para' => 'nullable|date|after:now',
        ]);

        // TODO: Implementar integração com API do Instagram

        return redirect()
            ->route('instagram.posts')
            ->with('success', 'Post agendado com sucesso!');
    }
}
