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

                    <!-- Facebook -->
                    <li>
                        <a href="{{ route('facebook.index') }}" 
                           class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('facebook*') ? 'bg-green-700 text-white' : 'text-green-200 hover:text-white hover:bg-green-700' }}">
                            <i class="fab fa-facebook w-6 text-center {{ request()->routeIs('facebook*') ? 'text-white' : 'text-green-200 group-hover:text-white' }}"></i>
                            Facebook
                        </a>
                    </li>

                    <!-- Instagram -->
                    <li>
                        <a href="{{ route('instagram.index') }}" 
                           class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('instagram*') ? 'bg-green-700 text-white' : 'text-green-200 hover:text-white hover:bg-green-700' }}">
                            <i class="fab fa-instagram w-6 text-center {{ request()->routeIs('instagram*') ? 'text-white' : 'text-green-200 group-hover:text-white' }}"></i>
                            Instagram
                        </a>
                    </li>

                    <!-- Telegram -->
                    <li>
                        <a href="{{ route('telegram.index') }}" 
                           class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('telegram*') ? 'bg-green-700 text-white' : 'text-green-200 hover:text-white hover:bg-green-700' }}">
                            <i class="fab fa-telegram w-6 text-center {{ request()->routeIs('telegram*') ? 'text-white' : 'text-green-200 group-hover:text-white' }}"></i>
                            Telegram
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Separador para futuras opções -->
            <li>
                <div class="text-xs font-semibold leading-6 text-green-200">Configurações</div>
                <ul role="list" class="-mx-2 mt-2 space-y-1">
                    <li x-data="{ open: {{ request()->routeIs('configuracoes.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" type="button"
                                class="group flex w-full items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('configuracoes.*') ? 'bg-green-700 text-white' : 'text-green-200 hover:text-white hover:bg-green-700' }}">
                            <i class="fas fa-cog w-6 text-center {{ request()->routeIs('configuracoes.*') ? 'text-white' : 'text-green-200 group-hover:text-white' }}"></i>
                            Configurações
                            <i class="fas fa-chevron-right ml-auto text-xs transition-transform" :class="{ 'rotate-90': open }"></i>
                        </button>
                        <ul x-show="open" x-collapse class="mt-1 space-y-1 pl-9">
                            <li>
                                <a href="{{ route('configuracoes.whatsapp') }}" 
                                   class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 {{ request()->routeIs('configuracoes.whatsapp*') ? 'bg-green-700 text-white' : 'text-green-200 hover:text-white hover:bg-green-700' }}">
                                    <i class="fab fa-whatsapp w-5 text-center"></i>
                                    WhatsApp
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('configuracoes.facebook') }}" 
                                   class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 {{ request()->routeIs('configuracoes.facebook*') ? 'bg-green-700 text-white' : 'text-green-200 hover:text-white hover:bg-green-700' }}">
                                    <i class="fab fa-facebook w-5 text-center"></i>
                                    Facebook
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('configuracoes.instagram') }}" 
                                   class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 {{ request()->routeIs('configuracoes.instagram*') ? 'bg-green-700 text-white' : 'text-green-200 hover:text-white hover:bg-green-700' }}">
                                    <i class="fab fa-instagram w-5 text-center"></i>
                                    Instagram
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('configuracoes.telegram') }}" 
                                   class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 {{ request()->routeIs('configuracoes.telegram*') ? 'bg-green-700 text-white' : 'text-green-200 hover:text-white hover:bg-green-700' }}">
                                    <i class="fab fa-telegram w-5 text-center"></i>
                                    Telegram
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>

            <!-- Em breve -->
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
