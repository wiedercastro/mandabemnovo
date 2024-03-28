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

    <div class="mt-16 overflow-x-auto">
      <div class="flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="sm:w-10 sm:h-10 w-6 h-6 stroke-gray-500">
          <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
        </svg>      
        <h1 class="text-gray-500 font-bold sm:text-3xl text-xl">Gerenciamento de crise</h1>
      </div>
      <table
        class="mt-2 w-full table-auto font-normal rounded shadow-lg
        text-sm text-left text-gray-500 border-collapse overflow-x-auto border-1">
        <thead class="text-xs text-gray-700 uppercase bg-gray-300">
          <tr>
            <th scope="col" class="px-6 py-3">
              OBJETO
            </th>
            <th scope="col" class="px-6 py-3">
              DESTINATÁRIO
            </th>
            <th scope="col" class="px-6 py-3">
              STATUS
            </th>
            <th scope="col" class="px-6 py-3">
              EMAIL
            </th>
            <th scope="col" class="px-6 py-3">
              AÇÕES
            </th>
          </tr>
        </thead>
        <tbody>
          <tr class="bg-white hover:bg-gray-100 border-b rounded-full font-light cursor-pointer">
            <td class="px-6 py-4">
              teste
            </td>
            <td class="px-6 py-4">
              teste
            </td>
            <td class="px-6 py-4">
              teste
            </td>
            <td class="px-6 py-4">
              teste
            </td>
            <td class="px-4 py-4">
              <button 
                class="bg-blue-400 hover:bg-blue-500 border border-blue-400 rounded sm:text-sm text-xs px-2 py-1 text-white font-bold">
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
      </table>
    </div>
  </div>
</x-app-layout>
