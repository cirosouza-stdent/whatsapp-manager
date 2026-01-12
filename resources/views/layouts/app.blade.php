<!DOCTYPE html>
<html lang="pt-BR" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'WhatsApp Manager')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
    
    @stack('styles')
</head>
<body class="h-full">
    <div x-data="{ sidebarOpen: false }" class="min-h-full">
        <!-- Sidebar para mobile -->
        <div x-show="sidebarOpen" x-cloak class="relative z-50 lg:hidden" role="dialog" aria-modal="true">
            <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-900/80"></div>

            <div class="fixed inset-0 flex">
                <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform"
                     x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                     x-transition:leave="transition ease-in-out duration-300 transform"
                     x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
                     class="relative mr-16 flex w-full max-w-xs flex-1">
                    <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                        <button type="button" @click="sidebarOpen = false" class="-m-2.5 p-2.5">
                            <span class="sr-only">Fechar sidebar</span>
                            <i class="fas fa-times text-white text-xl"></i>
                        </button>
                    </div>

                    @include('layouts.partials.sidebar')
                </div>
            </div>
        </div>

        <!-- Sidebar estática para desktop -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
            @include('layouts.partials.sidebar')
        </div>

        <!-- Conteúdo principal -->
        <div class="lg:pl-72">
            <!-- Header -->
            <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
                <button type="button" @click="sidebarOpen = true" class="-m-2.5 p-2.5 text-gray-700 lg:hidden">
                    <span class="sr-only">Abrir sidebar</span>
                    <i class="fas fa-bars text-xl"></i>
                </button>

                <div class="h-6 w-px bg-gray-200 lg:hidden" aria-hidden="true"></div>

                <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                    <div class="flex flex-1 items-center">
                        <h1 class="text-lg font-semibold text-gray-900">@yield('header', 'Dashboard')</h1>
                    </div>
                    <div class="flex items-center gap-x-4 lg:gap-x-6">
                        <!-- Perfil -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" type="button" class="-m-1.5 flex items-center p-1.5">
                                <span class="sr-only">Abrir menu do usuário</span>
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-green-500">
                                    <span class="text-sm font-medium leading-none text-white">
                                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                                    </span>
                                </span>
                                <span class="hidden lg:flex lg:items-center">
                                    <span class="ml-4 text-sm font-semibold leading-6 text-gray-900">
                                        {{ auth()->user()->name ?? 'Usuário' }}
                                    </span>
                                    <i class="fas fa-chevron-down ml-2 text-gray-400 text-xs"></i>
                                </span>
                            </button>

                            <div x-show="open" @click.away="open = false" x-cloak
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 z-10 mt-2.5 w-32 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="block w-full px-3 py-1 text-left text-sm leading-6 text-gray-900 hover:bg-gray-50">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Sair
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <main class="py-6">
                <div class="px-4 sm:px-6 lg:px-8">
                    <!-- Flash messages -->
                    @if(session('success'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                             class="mb-4 rounded-md bg-green-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-green-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                                </div>
                                <div class="ml-auto pl-3">
                                    <button @click="show = false" class="inline-flex rounded-md bg-green-50 p-1.5 text-green-500 hover:bg-green-100">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div x-data="{ show: true }" x-show="show"
                             class="mb-4 rounded-md bg-red-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-red-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                                </div>
                                <div class="ml-auto pl-3">
                                    <button @click="show = false" class="inline-flex rounded-md bg-red-50 p-1.5 text-red-500 hover:bg-red-100">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div x-data="{ show: true }" x-show="show"
                             class="mb-4 rounded-md bg-yellow-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-yellow-800">{{ session('warning') }}</p>
                                </div>
                                <div class="ml-auto pl-3">
                                    <button @click="show = false" class="inline-flex rounded-md bg-yellow-50 p-1.5 text-yellow-500 hover:bg-yellow-100">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
