<x-app-layout>

  <x-modal-como-funciona-aba-estatisticas/>

  <div class="w-5/6 ml-auto lg:px-12">

    <div class="flex justify-between">
      <x-card-pagamentos/>
      <div class="flex text-blue-700 items-center "> 
        <p
          type="button"
          onclick="abreModalAbaEstatistica()"
          class="font-bold text-[#25688B] text-xl font-bold cursor-pointer">
          Como funciona essa aba
        </p>          
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="cursor-pointer w-8 h-8">
          <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
        </svg>       
      </div>
    </div>

    <div class="mt-4">
      <div class="bg-white rounded-lg w-2/3 shadow-lg mt-6">
        <div class="p-4 font-light">
          <div class="flex justify-between text-sm text-gray-800">
            <div class="flex flex-col space-y-4 mt-2">
              <span class="text-xl text-gray-700 font-bold">Boletos</span>
              <span class="">R$ 0,00</span>
              <span class="">R$ 0,00</span>
            </div>
            <div class="flex flex-col space-y-4 mt-2"> 
              <span class="text-xl text-gray-700 font-bold">Transferências</span>
              <span class="">R$ 0,00</span>
              <span class="">R$ 0,00</span>
            </div>
            <div class="flex flex-col space-y-4 mt-2"> 
              <span class="text-xl text-gray-700 font-bold">PayPal</span>
              <span class="">R$ 0,00</span>
              <span class="">R$ 0,00</span>
            </div>
            <div class="flex flex-col space-y-4 mt-2"> 
              <span class="text-xl text-gray-700 font-bold">Diver. Pendentes</span>
              <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-yellow-500">
                  <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                </svg>
                <span class="ml-1">R$ 0,00</span>
              </div>
              <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-yellow-500">
                  <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                </svg>
                <span class="ml-1">R$ 0,00</span>
              </div>
            </div>
          </div>
        </div>
      </div>
  </div>

  <div class="mt-16">

    <div class="flex flex-row-reverse">
      <form action="#" class="mt-1 flex flex-col space-x-1 p-4 items-end border rounded bg-white">
        <div class="flex flex-col">
          <label for="" class="text-sm text-gray-700">Periodo</label>
          <select 
            required
            id="periodo"
            class="px-1 py-1 w-96 border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
            <option value="semana_atual">Semana Atual</option>
            <option value="mes_atual">Mês Atual</option>
            <option value="ano_atual">Ano Atual</option>
            <option value="ano_anterior">Ano Anterior</option>
            <option value="customizado">Customizado</option>
          </select>
        </div>

        <div id="exibeParaCustomizado" class="hidden">
          <div class="flex flex-col mt-1">
            <label for="data_inicial" class="text-sm text-gray-700">Data Inicial</label>
            <input 
              id="data_inicial"
              type="date"
              name="data_inicial"
              required
              class="px-1 py-1 w-96 border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
          </div>
    
          <div class="flex flex-col mt-1">
            <label for="data_final" class="text-sm text-gray-700">Data Final</label>
            <input 
              id="data_final"
              type="date"
              name="data_final"
              required
              class="px-1 py-1 w-96 border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
          </div>

          <button 
            type="submit"
            class="text-white font-bold text-xs
            hover:bg-green-700 rounded mt-2
            bg-green-600 px-2 py-1.5">
            Buscar
          </button>
        </div>
      </form>
  
    </div>

    <div class="flex items-center">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-9 h-9 stroke-gray-500">
        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z" />
      </svg>    
      <h1 class="text-gray-500 font-bold text-4xl">Estátistica</h1>
    </div>

    <table class="mt-4 w-full table-auto rounded shadow-lg
      text-sm text-left text-gray-500 border-collapse overflow-x-auto border-1">
      <thead class="text-xs text-white font-bold uppercase bg-gray-500">
        <tr>
          <th scope="col" class="px-6 py-3">
            Envios
          </th>
          <th scope="col" class="px-6 py-3">
            Valor
          </th>
          <th scope="col" class="px-6 py-3">
            Economia
          </th>
        </tr>
      </thead>
      <tbody>
        <tr 
          class="bg-white hover:bg-gray-100 border-b rounded-full font-light cursor-pointer">
          <td class="px-6 py-4">
           0
          </td>
          <td class="px-6 py-4">
            R$ 0,00
           </td>
           <td class="px-6 py-4">	
            R$ 0,00
           </td>
        </tr>
      </tbody>
    </table>
  </div>
</x-app-layout>
