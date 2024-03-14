<div class="justify-center items-center hidden" id="importar_nuvem">
  <div>
    <!-- Open modal button -->
    <!-- Modal Overlay -->
    
    <div class="fixed inset-0 px-2 z-10 overflow-hidden flex items-start justify-center animate__animated animate__fadeIn">
      <div class="absolute inset-0 bg-gray-800 bg-opacity-75 transition-opacity"></div>
      
      <!-- Modal Content -->
      <div
        class="bg-white rounded-md shadow-xl overflow-hidden max-w-xl w-full sm:w-full md:w-2/3 lg:w-3/4 xl:w-2/3 z-50 mt-16">
        <!-- Modal Header -->
        <div class="bg-blue-500 text-white px-4 py-4 flex justify-between">
          <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 stroke-white">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0 3 3m-3-3-3 3M6.75 19.5a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z" />
            </svg>     
            <h2 class="text-2xl ml-2 font-bold">
              Importar Pedidos Nuvemshop
            </h2>
          </div>
          <svg
            onclick="fechaModalImportarNuvem()"
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="w-8 h-8 stroke-white cursor-pointer">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
          </svg>
        </div>

        <hr>
        <div class="p-4">
          <form action="" method="GET">
            @csrf

            <div class="flex justify-between">
              <div class="flex flex-col w-full">
                <label for="data_inicial" class="block text-gray-600 text-sm font-bold">Data inicial</label>
                <input type="text" name="data_inicial" id="data_inicial"
                  class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500"
                  placeholder="dd/mm/aaaa">
              </div>
  
              <div class="flex flex-col w-full ml-4">
                <label for="data_final" class="block text-gray-600 text-sm font-bold">Data final</label>
                <input type="text" name="data_final" id="data_final"
                  class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500"
                  placeholder="dd/mm/aaaa">
              </div>
            </div>
            <div class="flex flex-col w-full mt-4">
              <label for="status_pedido" class="block text-gray-600 text-sm font-bold">Status do pedido</label>
              <select name="status_pedido" id="status_pedido" class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                <option value="todos">Todos</option>
                <option value="pedido_pago">Pedido Pago</option>
                <option value="pendente">Pagamento Pendente</option>
                <option value="pendente_embalagem">Pendente Embalagem</option>
              </select>
            </div>
            <button 
              class="bg-blue-500 hover:bg-blue-600 text-xs text-white font-bold rounded px-2 py-1 mt-4">
              <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <p class="ml-1">Buscar</p>
              </div>
            </button>
            <hr class="mt-4">
          </form>
        </div>

        <div class="flex flex-row-reverse mt-8 p-2">
          <button
            onclick="fechaModalImportarNuvem()"
            class="text-sm bg-red-500 hover:bg-red-600 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>                                       
            <p>Fechar</p>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>