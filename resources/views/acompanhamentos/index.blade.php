<x-app-layout>
  <x-modal-acompanhamento/>
  <x-modal-detalhes/>
  <div class="sm:w-5/6 w-full ml-auto lg:px-12 p-4">
    <div class="flex justify-center sm:hidden">
      <img src="{{asset('images/logo_mandabem_az.png')}}" alt="" class="w-32"/>
    </div>
    <div class="flex items-center mt-10 sm:mt-0 sm:justify-start justify-center">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 stroke-gray-500">
        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
      </svg>      
      <h1 class="text-gray-500 font-bold text-4xl">Acompanhamentos</h1>
    </div>

    <x-menu-navigation/>

    <div class="mt-16">
      <div class="flex flex-row-reverse sm:justify-start justify-center">
        <form action="#" class="mt-1 flex flex-col sm:flex-row space-x-1 p-4 items-center sm:items-end border rounded bg-white">
          <div class="flex flex-col">
            <label for="etiqueta" class="text-gray-600 sm:text-sm text-xs">Etiqueta</label>
            <input id="etiqueta" type="text" class="px-1 py-1 w-72 border outline-none rounded bg-white border-gray-200 text-sm" placeholder="Buscar por etiqueta">
          </div>

          <div class="flex flex-col mt-2">
            <label for="situacao_objeto" class="text-gray-600 sm:text-sm text-xs">Situação do Objeto</label>
            <select 
              id="situacao_objeto"
              required
              class="px-1 py-1 w-72 border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
              <option value="todos" disabled selected class="text-sm">Todos</option>
              <option value="atrasado">Atrasado</option>
              <option value="no_prazo">No prazo</option>
            </select>
          </div>

          <button 
            type="submit"
            class="text-white font-bold text-xs w-full
            hover:bg-sky-800 rounded border mt-2 flex items-center justify-center
            border-gray-500 bg-[#25688B]  px-2 py-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
            <p class="ml-1">Buscar</p>            
          </button>
        </form>
      </div>

      <div class="overflow-x-auto">
        <table
          class="mt-2 w-full table-auto font-normal rounded shadow-lg
          text-sm text-left text-gray-500 border-collapse border-1">
          <thead class="text-xs text-gray-700 uppercase bg-gray-300">
            <tr>
              <th scope="col" class="px-6 py-3">
                Objeto
              </th>
              <th scope="col" class="px-6 py-3">
                Destinatário
              </th>
              <th scope="col" class="px-6 py-3">
                Status
              </th>
              <th scope="col" class="px-6 py-3">
                Prazo 
                (dias úteis)
              </th>
              <th scope="col" class="px-6 py-3">
                Postagem
              </th>
              <th scope="col" class="px-6 py-3">
                Prev.
                Entrega
              </th>
              <th scope="col" class="px-6 py-3">
                Manifestação
              </th>
            </tr>
          </thead>
          <tbody>
            <tr class="bg-white hover:bg-gray-100 border-b rounded-full font-light cursor-pointer">
              <td class="px-6 py-4 text-xs sm:text-sm">
                SZ909197154BR
              </td>
              <td class="px-6 py-4 text-xs sm:text-sm">
                Wieder
              </td>
              <td class="px-6 py-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 stroke-blue-500 hover:stroke-blue-800">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <b class="ml-1 text-xs sm:text-sm">Aguardando Postagem</b>
              </td>
              <td class="px-6 py-4 text-xs sm:text-sm">
                2
              </td>
              <td class="px-6 py-4 text-xs sm:text-sm ">
                18/10/2020
              </td>
              <td class="px-6 py-4 flex items-center text-red-600">
                <p class="mr-1 text-xs sm:text-sm">20/09/2023</p>
                <i class="fa fa-exclamation"></i>
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center">
                  <div class="text-red-500">
                    <i class="fa fa-hourglass-half"></i>
                  </div>
                  <p class="ml-1 text-xs sm:text-sm">Em processo de Abertura</p>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</x-app-layout>
