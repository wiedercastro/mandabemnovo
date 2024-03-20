<nav x-data="{ open: false }" class="h-full border-b border-gray-100">
    <!-- Primary Navigation Menu -->
  <div class="max-w-10xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-20">
      <div class="flex">
        <aside
            class="bg-white fixed top-0 left-0 z-40 w-72 h-screen transition-transform -translate-x-full sm:translate-x-0"
            >
            <div class="ml-3.5 mr-3.5">
              <div class="shrink-10 flex items-center ml-3.5">
                <a href="{{ route('etiquetas') }}">
                  <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                </a>
              </div>

              <nav class="mt-12">
                <ul class="space-y-2">
                  <li class="{{ request()->routeIs('gerar') ? 'rounded-lg bg-gray-100' : '' }} opcion-con-desplegable">
                    <a href="{{ route('gerar') }}" class="flex items-center justify-between p-2 rounded-lg group">
                      <div class="flex items-center">
                        <x-gerar-icone />
                        <span>Gerar</span>
                      </div>
                    </a>
                  </li>
                  <li class="{{ request()->routeIs('etiquetas') ? 'rounded-lg bg-gray-100' : '' }} opcion-con-desplegable">
                    <a href="{{ route('etiquetas') }}" class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-100 group">
                      <div class="flex items-center">
                        <x-etiqueta-icone />
                        <span>Etiquetas</span>
                      </div>
                    </a>
                      <!-- <ul class="desplegable ml-4 hidden">
                        <li>
                            <a href="#" class="block p-2 hover:bg-gray-700 flex items-center">
                                <i class="fas fa-chevron-right mr-2 text-xs"></i>
                                Tratamientos
                            </a>
                        </li>
                        <li>
                            <a href="#" class="block p-2 hover:bg-gray-700 flex items-center">
                                <i class="fas fa-chevron-right mr-2 text-xs"></i>
                                Gastos
                            </a>
                        </li>
                        <li>
                            <a href="#" class="block p-2 hover:bg-gray-700 flex items-center">
                                <i class="fas fa-chevron-right mr-2 text-xs"></i>
                                Facturas
                            </a>
                        </li>
                    </ul> -->
                  </li>
                  <li class="{{ request()->routeIs('reversa') ? 'rounded-lg bg-gray-100' : '' }} opcion-con-desplegable">
                    <a href="{{ route('reversa') }}" class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-100 group">
                      <div class="flex items-center">
                        <x-reversa-icone />
                        <span>Reversa</span>
                      </div>
                    </a>
                      <!-- <ul class="desplegable ml-4 hidden">
                          <li>
                              <a href="#" class="block p-2 hover:bg-gray-700 flex items-center">
                                  <i class="fas fa-chevron-right mr-2 text-xs"></i>
                                  Presupuestos
                              </a>
                          </li>
                          <li>
                              <a href="#" class="block p-2 hover:bg-gray-700 flex items-center">
                                  <i class="fas fa-chevron-right mr-2 text-xs"></i>
                                  Informe médico
                              </a>
                          </li>
                      </ul> -->
                  </li>
                  <li class="{{ request()->routeIs('declaracoes') ? 'rounded-lg bg-gray-100' : '' }} opcion-con-desplegable">
                    <a href="{{ route('declaracoes') }}" class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-100 grou">
                      <div class="flex items-center">
                        <x-declaracoes-icone />
                        <span>Declarações</span>
                      </div>
                    </a>
                  </li>
                  <li class="{{ request()->routeIs('pagamentos.index') ? 'rounded-lg bg-gray-100' : '' }} opcion-con-desplegable">
                    <a href="{{route('pagamentos.index')}}" class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-100 grou">
                      <div class="flex items-center">
                        <x-pagamento-icone />
                        <span>Pagamentos</span>
                      </div>
                    </a>
                  </li>
                  <li class="{{ request()->routeIs('acompanhamento') ? 'rounded-lg bg-gray-100' : '' }} opcion-con-desplegable">
                    <a href="{{route('acompanhamento')}}" class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-100 group">
                      <div class="flex items-center">
                        <x-acompanhamento-icone />
                        <span>Acompanhamento</span>
                      </div>
                    </a>
                  </li>
                  <li class="opcion-con-desplegable">
                    <a href="#" class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-100 group">
                      <div class="flex items-center">
                        <x-estatistica-icone />
                        <span>Estatistica</span>
                      </div>
                    </a>
                  </li>
                  <li class="{{ request()->routeIs('cotacao') ? 'rounded-lg bg-gray-100' : '' }} opcion-con-desplegable">
                    <a href="{{route('cotacao')}}" class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-100 group">
                      <div class="flex items-center">
                          <x-cotacao-icone />
                          <span>Cotações</span>
                      </div>
                    </a>
                  </li>
                </ul>
              </nav>
            </div>

            <hr class="w-full mt-4">
            <ul class="p-6 list-disc text-xs ml-8 font-bold text-gray-800">
              <a href="https://site.mandabem.com.br">
                <li>Políticas de Privacidade</li>
              </a>
              <a href="https://site.mandabem.com.br">
                <li class="mt-4">Termos de uso</li>
              </a>
              <a href="https://site.mandabem.com.br">
                <li class="mt-4">Novidades</li>
              </a>
            </ul>
            <hr class="w-full mt-4">
            <div class="flex justify-center mt-6">
              <p class="text-xs font-bold text-gray-800">2021 © Todos os direitos Reservados.</p>
            </div>
        </aside>
      </div>

        <!-- Settings Dropdown -->
      <div class="hidden sm:flex sm:items-center sm:ml-6">
        <x-dropdown align="right" width="48">
          <x-slot name="trigger">
            <button
              class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white focus:outline-none transition ease-in-out duration-150">
              <div>{{ Auth::user()->name }}</div>

              <div class="ml-1">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 20 20">
                  <path fill-rule="evenodd"
                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                      clip-rule="evenodd" />
                </svg>
              </div>
            </button>
          </x-slot>

          <x-slot name="content">
            <x-dropdown-link :href="route('profile.edit')">
                {{ __('Meus Dados') }}
            </x-dropdown-link>

            <x-dropdown-link :href="route('profile.edit')">
                {{ __('Relatório Etiquetas') }}
            </x-dropdown-link>

            <x-dropdown-link :href="route('cobranca')">
                {{ __('Cobrança') }}
            </x-dropdown-link>

            <x-dropdown-link :href="route('profile.edit')">
                {{ __('Integraçõess') }}
            </x-dropdown-link>

            <x-dropdown-link :href="route('manifestacao')">
                {{ __('Manifestações') }}
            </x-dropdown-link>

            <x-dropdown-link :href="route('profile.edit')">
                {{ __('Cupom') }}
            </x-dropdown-link>

            <!-- Authentication -->
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <x-dropdown-link :href="route('logout')"
                  onclick="event.preventDefault();
                                this.closest('form').submit();">
                  {{ __('Sair') }}
              </x-dropdown-link>
            </form>
          </x-slot>
        </x-dropdown>
      </div>

      <!-- Hamburger -->
      <div class="-mr-2 flex items-center sm:hidden">
        <button @click="open = ! open"
          class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
          <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
            <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16" />
            <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>
  </div>

  <!-- Responsive Navigation Menu -->
  <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
      <div class="pt-2 pb-3 space-y-1">
          <x-responsive-nav-link :href="route('etiquetas')" :active="request()->routeIs('etiquetas')">
              {{ __('etiquetas') }}
          </x-responsive-nav-link>
      </div>

      <!-- Responsive Settings Options -->
      <div class="pt-4 pb-1 border-t border-gray-200">
          <div class="px-4">
              <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
              <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
          </div>

          <div class="mt-3 space-y-1">
              <x-responsive-nav-link :href="route('profile.edit')">
                  {{ __('Profile') }}
              </x-responsive-nav-link>

              <!-- Authentication -->
              <form method="POST" action="{{ route('logout') }}">
                  @csrf

                  <x-responsive-nav-link :href="route('logout')"
                      onclick="event.preventDefault();
                                      this.closest('form').submit();">
                      {{ __('Log Out') }}
                  </x-responsive-nav-link>
              </form>
          </div>
      </div>
  </div>
</nav>
