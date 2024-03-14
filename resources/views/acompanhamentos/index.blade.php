<x-app-layout>

 <x-modal-como-funciona-aba-acompanhamentos/>
 <x-modal-detalhes-pagamentos/>

  <div class="w-5/6 ml-auto lg:px-12">
    <div class="flex justify-between">
      <div class="bg-white rounded-lg w-1/2 shadow">
        <div class="p-4 font-light">
          <h1 class="text-2xl text-gray-800 font-bold">Total</h1>
          <div class="flex justify-between text-sm text-gray-800">
            <div class="flex flex-col space-y-4 mt-2">
              <span class="">Fevereiro 2024</span>
              <span class="">Clientes c/ Postagens: 0</span>
            </div>
            <div class="flex flex-col space-y-4 mt-2"> 
              <span class="">Totais: 0 | Nuvem: 0 | LI : 0</span>
              <span class="">Hoje: 0 | Nuvem: 0 | LI: 0</span>
            </div>
          </div>
        </div>
      </div>
      <div class="flex text-blue-700 items-center">
        <p
          type="button"
          onclick="exibiModalComInformacoes()" 
          class="font-bold text-[#25688B] text-xl font-bold cursor-pointer">
          Como funciona essa aba
        </p>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="cursor-pointer w-8 h-8">
          <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
        </svg>                  
      </div>
  </div>
  <div class="mt-16">
    <h1 class="text-gray-500 font-bold text-3xl">Gerenciamento de Emails</h1>
    <table
      class="mt-2 w-2/3 table-auto font-normal rounded shadow-lg
      text-sm text-left text-gray-500 border-collapse overflow-x-auto border-1">
      <thead class="text-xs text-gray-700 uppercase bg-gray-300">
        <tr>
          <th scope="col" class="px-6 py-3">
             NOME
          </th>
          <th scope="col" class="px-6 py-3">
            OPÇÕES
          </th>
        </tr>
      </thead>
      @foreach ($acompanhamento_email as $acompanhamento)
        <tbody>
          <tr 
            class="bg-white hover:bg-gray-100 border-b rounded-full font-light cursor-pointer">
            <td class="px-6 py-4">
              {{$acompanhamento->name}}
            </td>
            <td class="px-4 py-4">
              <button 
                onclick="abreModal({{$acompanhamento->id}})"
                class="bg-blue-400 hover:bg-blue-500 border border-blue-400 rounded text-sm px-2 py-1 text-white font-bold">
                <div class="flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                  </svg>
                  <p class="ml-1">Editar</p>
                </div>
              </button>
            </td>
          </tr>
        </tbody>
      @endforeach
    </table>
  </div>
</x-app-layout>
