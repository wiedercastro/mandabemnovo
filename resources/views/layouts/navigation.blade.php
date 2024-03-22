<nav x-data="{ open: false }" class="h-full border-b border-gray-100">
    <!-- Primary Navigation Menu -->
  <div class="max-w-10xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-20">
      <div class="flex">
        <aside
            class="bg-white fixed top-0 left-0 z-40 w-72 h-screen transition-transform -translate-x-full lg:translate-x-0"
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
                  </li>
                  <li class="{{ request()->routeIs('reversa') ? 'rounded-lg bg-gray-100' : '' }} opcion-con-desplegable">
                    <a href="{{ route('reversa') }}" class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-100 group">
                      <div class="flex items-center">
                        <x-reversa-icone />
                        <span>Reversa</span>
                      </div>
                    </a>
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
      <div class="mt-4">
        <button 
          onclick="abreFechaDropDown()"
          id="dropdownAvatarNameButton" 
          data-dropdown-toggle="dropdownAvatarName" 
          class="ml-7 text-gray-500 flex items-center text-sm pe-1 rounded-full hover:text-blue-600 md:me-0 focus:ring-4 focus:ring-gray-100" type="button">
          <span class="sr-only">Open user menu</span>
          <img src="{{asset('images/user.png')}}" alt="" class="h-9 w-9 overflow-hidden rounded-full"/>
          <p class="ml-1">{{ Auth::user()->name }}</p>
          <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
          </svg>
        </button>

        <!-- Dropdown menu -->
        <div id="dropdownAvatarName" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
          <ul class="py-2 text-sm text-gray-700" aria-labelledby="dropdownInformdropdownAvatarNameButtonationButton">
            <li>
              <a href="#" class="block px-4 py-2 hover:bg-gray-100">Meus Dados</a>
            </li>
            <li>
              <a href="#" class="block px-4 py-2 hover:bg-gray-100">Relatório Etiquetas</a>
            </li>
            <li>
              <a href="#" class="block px-4 py-2 hover:bg-gray-100">Cobrança</a>
            </li>
            <li>
              <a href="#" class="block px-4 py-2 hover:bg-gray-100">Integrações</a>
            </li>
            <li>
              <a href="#" class="block px-4 py-2 hover:bg-gray-100">Manifestações</a>
            </li>
            <li>
              <a href="#" class="block px-4 py-2 hover:bg-gray-100">Cupom</a>
            </li>
          </ul>
        </div>
     
         <!-- Hamburger -->
        <div class="fixed top-0 left-0 z-50 p-4">
          <div class="-mr-2 flex items-center lg:hidden">
              <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                  <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                      <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                      <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
              </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden lg:hidden">
        <div class="pt-2 pb-3 space-y-1">
          <x-dropdown-link :href="route('profile.edit')">
              {{ __('Meus Dados') }}
          </x-dropdown-link>

          <x-dropdown-link :href="route('profile.edit')">
              {{ __('Relatório Etiquetas') }}
          </x-dropdown-link>

          <x-dropdown-link :href="route('profile.edit')">
              {{ __('Cobrança') }}
          </x-dropdown-link>

          <x-dropdown-link :href="route('profile.edit')">
              {{ __('Integraçõess') }}
          </x-dropdown-link>

          <x-dropdown-link :href="route('profile.edit')">
              {{ __('Manifestações') }}
          </x-dropdown-link>

          <x-dropdown-link :href="route('profile.edit')">
              {{ __('Cupom') }}
          </x-dropdown-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Sair') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
  </div>
</nav>

<script>
  const abreFechaDropDown = () => {
      const dropdown = document.getElementById('dropdownAvatarName');
      dropdown.classList.toggle('hidden');
  };
  
</script>