<x-app-layout>
  <div class="w-5/6 ml-auto lg:px-12">
    <div class="flex justify-between">
      <div class="flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-9 h-9 stroke-gray-500">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
        </svg>          
        <h1 class="text-gray-500 font-bold text-4xl">Relatório Etiquetas</h1>
      </div>
      <form action="#" class="mt-1 flex flex-col space-x-1 p-4 items-end border rounded bg-white">
        <div>
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
            bg-green-600 px-2 py-1.5 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
            <p class="ml-1">Buscar</p>
          </button>
        </div>
      </form>
    </div>
    
    <div class="flex flex-row-reverse text-xs mt-12">
      <button
        class="bg-blue-600 hover:bg-blue-800 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">
        Nova busca
      </button>
      <button
        class="bg-green-600 hover:bg-green-700 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15M9 12l3 3m0 0 3-3m-3 3V2.25" />
        </svg>
        <p class="ml-1">Exportar Excel</p>
      </button>
    </div>
    <table class="mt-4 w-full table-auto rounded shadow-lg
      text-sm text-left text-gray-500 border-collapse overflow-x-auto border-1">
      <thead class="text-xs text-white font-bold uppercase bg-gray-500">
        <tr>
          <th scope="col" class="px-6 py-3">
            Data
          </th>
          <th scope="col" class="px-6 py-3">
            Etiqueta
          </th>
          <th scope="col" class="px-6 py-3">
            Destinatário
          </th>
          <th scope="col" class="px-6 py-3">
            Localidade	
          </th>
          <th scope="col" class="px-6 py-3">
            Data de Entrega
          </th>
          <th scope="col" class="px-6 py-3">
            Status
          </th>
          <th scope="col" class="px-6 py-3">
            Valor
          </th>
        </tr>
      </thead>
      <tbody>
        <tr 
          class="bg-white hover:bg-gray-100 border-b rounded-full font-light cursor-pointer">
          <td class="px-6 py-4 flex flex-col">
            <div class="flex">
              <p>04/03/2024</p>
              <p>12:18</p>
            </div>
            <div class="text-green-600 font-semibold">
              #pedido 5106
            </div>
           </td>
          <td class="px-6 py-4">	
            <div class="flex flex-col">
              <p class="font-bold">AA031625386BR</p>
              <p>Envio normal</p>
            </div>
          </td>
          <td class="px-6 py-4">	
            Tatiana Beck
          </td>
          <td class="px-6 py-4">	
            São Caetano do Sul/SP
          </td>
          <td class="px-6 py-4">	
            <p class="text-yellow-600">Aguardando Entrega</p>
          </td>
          <td class="px-6 py-4">	
            <p class="text-yellow-600">Aguardando Postagem</p>
          </td>
          <td class="px-6 py-4">	
            R$ 37,71
          </td>
        </tr>
      </tbody>
    </table>

    <div class="p-2 bg-white shadow rounded">
      <div class="flex justify-between">
        <p class="text-gray-600 text-sm">TOTAL ETIQUETAS</p>
        <span class="text-gray-800 text-sm font-light">R$ 0,00</span>
      </div>
      <div class="flex justify-between">
        <p class="text-gray-600 text-sm">TOTAL CREDITADO POR CANCELAMENTO</p>
        <span class="text-gray-800 text-sm font-light">R$ 0,00</span>
      </div>
      <div class="flex justify-between">
        <p class="text-gray-800 text-sm font-bold">TOTAL GASTO</p>
        <span class="text-gray-800 text-sm font-bold">R$ 0,00</span>
      </div>
    </div>

  </div>
</x-app-layout>

<script>

</script>
