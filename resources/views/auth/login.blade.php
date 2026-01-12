<!DOCTYPE html>
<html lang="pt-BR" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - WhatsApp Manager</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="h-full">
    <div class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-center">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-green-500">
                    <i class="fab fa-whatsapp text-3xl text-white"></i>
                </div>
            </div>
            <h2 class="mt-6 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">
                WhatsApp Manager
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Faça login para acessar sua conta
            </p>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-[480px]">
            <div class="bg-white px-6 py-12 shadow sm:rounded-lg sm:px-12">
                @if ($errors->any())
                    <div class="mb-4 rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">
                                    Ocorreu um erro
                                </h3>
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

                <form class="space-y-6" action="{{ route('login.submit') }}" method="POST">
                    @csrf
                    
                    <div>
                        <label for="email" class="block text-sm font-medium leading-6 text-gray-900">
                            E-mail
                        </label>
                        <div class="mt-2">
                            <input id="email" name="email" type="email" autocomplete="email" required
                                   value="{{ old('email') }}"
                                   class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium leading-6 text-gray-900">
                            Senha
                        </label>
                        <div class="mt-2">
                            <input id="password" name="password" type="password" autocomplete="current-password" required
                                   class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember" name="remember" type="checkbox"
                                   class="h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-600">
                            <label for="remember" class="ml-3 block text-sm leading-6 text-gray-900">
                                Lembrar-me
                            </label>
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                                class="flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600 transition-colors">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Entrar
                        </button>
                    </div>
                </form>
            </div>

            <p class="mt-10 text-center text-sm text-gray-500">
                Não tem uma conta?
                <a href="{{ route('register') }}" class="font-semibold leading-6 text-green-600 hover:text-green-500">
                    Cadastre-se agora
                </a>
            </p>
        </div>
    </div>
</body>
</html>
