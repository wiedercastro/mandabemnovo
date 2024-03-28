<x-app-layout>
  <x-modal-acompanhamento/>
  <x-modal-detalhes/>
  <div class="sm:w-5/6 w-full ml-auto lg:px-12 p-4 sm:p-0">
    <div class="flex justify-center sm:hidden">
      <img src="{{asset('images/logo_mandabem_az.png')}}" alt="" class="w-32"/>
    </div>
    <div class="flex items-center mt-8 sm:mt-0">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 stroke-gray-500">
        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
      </svg>      
      <h1 class="text-gray-500 font-bold text-4xl">Acompanhamentos</h1>
    </div>

    <x-menu-navigation/>

      {{--     <div class="flex justify-between">
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
    </div> --}}
    <div class="flex flex-row-reverse text-blue-700 items-center mt-6">
      <p
        type="button"
        onclick="exibiModalComInformacoes()" 
        class="font-bold text-[#25688B] sm:text-xl text-sm font-bold cursor-pointer">
        Como funciona essa aba
      </p>
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="cursor-pointer sm:w-8 sm:h-8 w-5 h-5">
        <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
      </svg>                  
    </div>
    <div class="mt-16">
      <div class="flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="sm:w-10 sm:h-10 w-6 h-6 stroke-gray-500">
          <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
        </svg>      
        <h1 class="text-gray-500 font-bold sm:text-3xl text-xl">Gerenciamento de Emails</h1>
      </div>
      <table
        class="mt-2 w-full table-auto font-normal rounded shadow-lg
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
              <td class="px-6 py-4 sm:text-sm text-xs">
                {{$acompanhamento->name}}
              </td>
              <td class="px-4 py-4 flex items-center ">
                <button 
                  onclick="abreModalAcompanhamento({{$acompanhamento->id}})"
                  class="bg-blue-400 hover:bg-blue-500 border border-blue-400 rounded sm:text-sm text-xs px-2 py-1 text-white font-bold">
                  <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                    <p class="ml-1">Editar</p>
                  </div>
                </button>
                <button 
                  class="bg-red-600 hover:bg-red-500 border border-red-400 rounded sm:text-sm text-xs px-2 py-1 text-white font-bold ml-1">
                  <div class="flex items-center">
                    <i class="fa fa-toggle-on"></i>
                    <p class="ml-1">Desativar</p>
                  </div>
                </button>
              </td>
            </tr>
          </tbody>
        @endforeach
      </table>
    </div>
  </div>
</x-app-layout>
