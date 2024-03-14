<x-app-layout>

  <x-modal-como-funciona-aba-declaracoes/>

  <div class="w-5/6 ml-auto lg:px-12">
    <div class="text-4xl">
      <div class="w-full text-gray-900">
        <div class="flex w-full m-auto p-4 bg-white rounded shadow-xl ">
          <div class="w-11/12 flex ml-3.5 flex items-center">
            <div class="">
              <form action="" method="GET" class="flex items-center ">
                <input name="name" id="name" placeholder="BUSQUE POR NOME" class="rounded-l-lg px-1 py-2 w-96 border outline-none shadow bg-white border-gray-200 text-sm text-gray-600">
                <button 
                  class="bg-[#25688B] text-sm text-white font-bold rounded-r-lg px-1 py-2 ">
                  <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <p class="ml-1">Procurar</p>
                  </div>
                </button>
              </form>
            </div>
          </div>
          <div class="w-3/12 flex text-blue-700 items-center">
              <button onclick="exibiModalComInformacoesDetalhes()" class="font-bold text-[#25688B] text-xl font-bold">
                Como funciona essa aba
              </button>
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="cursor-pointer w-8 h-8">
                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
              </svg>                  
          </div>
        </div>
      </div>
    </div>

    <div class="mt-12">
      <div class="flex flex-row-reverse">
        <nav class="mt-2">
          <ul class="inline-flex -space-x-px mt-2 text-xs">
            @if ($declaracoes->currentPage() > 1)
              <li>
                <a href="?page={{ $declaracoes->currentPage() - 1 }}" 
                  class="py-2 px-3 ml-0 leading-tight text-gray-500 bg-white 
                    rounded-l-lg border border-gray-300 hover:bg-gray-100 
                    hover:text-gray-700">
                  Anterior
                </a>
              </li>
            @endif
            @for ($i = 1; $i <= $declaracoes->lastPage(); $i++)
              <li>
                <a href="?page={{ $i }}" 
                    class="py-2 px-3 {{ $declaracoes->currentPage() == $i ? 'text-blue-600 bg-blue-50' : 'text-gray-500 bg-white' }}
                    border border-gray-300 hover:bg-gray-100 
                    hover:text-gray-700">
                    {{ $i }}
                </a>
              </li>
            @endfor
            @if ($declaracoes->currentPage() < $declaracoes->lastPage())
              <li>
                <a href="?page={{ $declaracoes->currentPage() + 1 }}" 
                  class="py-2 px-3 leading-tight text-gray-500 bg-white
                  rounded-r-lg border border-gray-300 hover:bg-gray-100 
                  hover:text-gray-700">
                  Próxima
                </a>
              </li>
            @endif
          </ul>
        </nav>
      </div>
      <table
        class="mt-6 w-full table-auto font-normal rounded shadow-lg
        text-sm text-left text-gray-500 border-collapse overflow-x-auto border-1">
        <thead class="text-xs uppercase bg-blue-400 text-white font-bold">
          <tr>
            <th scope="col" class="px-6 py-3">
              ID
            </th>
            <th scope="col" class="px-6 py-3">
              ORIGEM
            </th>
            <th scope="col" class="px-6 py-3">
              DATA
            </th>
            <th scope="col" class="px-6 py-3">
              VALOR
            </th>
            <th scope="col" class="px-6 py-3">
              DESTINOS
            </th>
          </tr>
        </thead>
        @foreach ($declaracoes as $declaracao)
          <tbody>
            <tr 
              class="bg-white hover:bg-gray-100 border-b rounded-full font-light cursor-pointer">
              <td class="px-6 py-4">
                MB001751767
              </td>
              <td class="px-6 py-4">
                teste
              </td>
              <td class="px-6 py-4">
                {{$declaracao->date_insert}}
              </td>
              <td class="px-6 py-4">
                R$ {{$declaracao->valor}}
              </td>
              <td class="px-4 py-4">
                <button 
                  class="bg-[#25688B] rounded text-sm px-2 py-1 text-white font-bold">
                  <div class="flex items-center">
                    <div class="flex items-center">
                      <p class="text-xs">(1)</p>       
                    </div>          
                    <p class="ml-1">Destinatários</p>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg> 
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
