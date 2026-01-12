<div class="flex grow flex-col gap-y-5 overflow-y-auto bg-green-600 px-6 pb-4">
    <div class="flex h-16 shrink-0 items-center">
        <i class="fab fa-whatsapp text-white text-3xl mr-3"></i>
        <span class="text-white text-xl font-bold">WhatsApp Manager</span>
    </div>
    <nav class="flex flex-1 flex-col">
        <ul role="list" class="flex flex-1 flex-col gap-y-7">
            <li>
                <ul role="list" class="-mx-2 space-y-1">
                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('dashboard') }}" 
                           class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('dashboard*') ? 'bg-green-700 text-white' : 'text-green-200 hover:text-white hover:bg-green-700' }}">
                            <i class="fas fa-home w-6 text-center {{ request()->routeIs('dashboard*') ? 'text-white' : 'text-green-200 group-hover:text-white' }}"></i>
                            Dashboard
                        </a>
                    </li>

                    <!-- Instâncias WhatsApp -->
                    <li>
                        <a href="{{ route('instancias.index') }}" 
                           class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('instancias*') ? 'bg-green-700 text-white' : 'text-green-200 hover:text-white hover:bg-green-700' }}">
                            <i class="fab fa-whatsapp w-6 text-center {{ request()->routeIs('instancias*') ? 'text-white' : 'text-green-200 group-hover:text-white' }}"></i>
                            Instâncias
                        </a>
                    </li>

                    <!-- Mensagens Agendadas -->
                    <li>
                        <a href="{{ route('mensagens.index') }}" 
                           class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('mensagens*') ? 'bg-green-700 text-white' : 'text-green-200 hover:text-white hover:bg-green-700' }}">
                            <i class="fas fa-clock w-6 text-center {{ request()->routeIs('mensagens*') ? 'text-white' : 'text-green-200 group-hover:text-white' }}"></i>
                            Mensagens Agendadas
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Separador para futuras opções -->
            <li>
                <div class="text-xs font-semibold leading-6 text-green-200">Em breve</div>
                <ul role="list" class="-mx-2 mt-2 space-y-1">
                    <li>
                        <span class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-green-300 opacity-50 cursor-not-allowed">
                            <i class="fas fa-robot w-6 text-center"></i>
                            Automações
                        </span>
                    </li>
                    <li>
                        <span class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-green-300 opacity-50 cursor-not-allowed">
                            <i class="fas fa-users w-6 text-center"></i>
                            Contatos
                        </span>
                    </li>
                    <li>
                        <span class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-green-300 opacity-50 cursor-not-allowed">
                            <i class="fas fa-chart-bar w-6 text-center"></i>
                            Relatórios
                        </span>
                    </li>
                    <li>
                        <span class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-green-300 opacity-50 cursor-not-allowed">
                            <i class="fas fa-cog w-6 text-center"></i>
                            Configurações
                        </span>
                    </li>
                </ul>
            </li>

            <li class="mt-auto">
                <a href="#" class="group -mx-2 flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-green-200 hover:bg-green-700 hover:text-white">
                    <i class="fas fa-question-circle w-6 text-center text-green-200 group-hover:text-white"></i>
                    Ajuda
                </a>
            </li>
        </ul>
    </nav>
</div>
