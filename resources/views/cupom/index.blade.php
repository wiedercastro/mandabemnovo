<x-app-layout>
  <div class="sm:w-5/6 w-full ml-auto lg:px-12 p-4 sm:0">
    <div class="flex justify-center sm:hidden">
      <img src="{{asset('images/logo_mandabem_az.png')}}" alt="" class="w-32"/>
    </div>
    <div class="bg-white p-10 shadow-md mt-10 sm:mt-0">
      <div class="flex flex-row-reverse">
        <form action="#" class="mt-1 flex space-x-1 p-4 items-end border rounded bg-white">
          <div class="flex flex-col">
            <label for="cupom_desconto" class="text-sm text-gray-700">Incluir cupom de desconto</label>
            <input 
              type="text" 
              id="cupom_desconto"
              name="cupom_desconto"
              class="px-1 py-1 sm:w-72 w-full border outline-none rounded bg-white border-gray-200 text-sm" 
              placeholder="Incluir...">
          </div>

          <button 
            type="submit"
            class="text-white font-bold text-xs
            hover:bg-green-700 rounded border 
            border-green-500 bg-green-600 px-2 py-1.5">
            Validar
          </button>
        </form>
      </div>
      
      <h1 class="text-gray-500 font-bold sm:text-2xl text-xl mt-10 sm:mt-0">Histórico de Cupons Utilizados</h1>
      
      <div class="overflow-x-auto">
        <table class="mt-4 w-full table-auto rounded
          sm:text-sm text-xs text-left text-gray-500 border-collapse overflow-x-auto border-1">
          <thead class="text-xs text-white font-bold uppercase bg-[#008adb]">
            <tr>
              <th scope="col" class="px-6 py-3">
                Data
              </th>
              <th scope="col" class="px-6 py-3">
                Validade
              </th>
              <th scope="col" class="px-6 py-3">
                Nome
              </th>
              <th scope="col" class="px-6 py-3">
                Utilizados	
              </th>
              <th scope="col" class="px-6 py-3">
                Status
              </th>
            </tr>
          </thead>
          <tbody>
            <tr class="bg-white hover:bg-gray-100 border rounded-full cursor-pointer">
              <td class="px-6 py-4 flex flex-col">
                <div class="flex items-center">
                  <p>04/03/2024</p>
                </div>
                <div>
                  12:18
                </div>
              </td>
              <td class="px-6 py-4">	
                <div class="flex flex-col">
                  <p>31/07/2023</p>
                  <p>00:00</p>
                </div>
              </td>
              <td class="px-6 py-4">	
                MANDABEM30E
              </td>
              <td class="px-6 py-4">	
                1
              </td>
              <td class="px-6 py-4">	
                <div class="hidden sm:inline-flex text-white font-bold items-center px-2 py-1 rounded-full gap-x-2 bg-red-500">
                  <div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                  </div>
                  <p class="text-xs">Cupom vencido</p>
                </div>
                <p class="text-xs text-red-500 sm:hidden flex">Cupom vencido</p>
              </td>
            </tr>
            <tr class="bg-white hover:bg-gray-100 border rounded-full cursor-pointer">
              <td class="px-6 py-4 flex flex-col">
                <div class="flex items-center">
                  <p>04/03/2024</p>
                </div>
                <div>
                  12:18
                </div>
              </td>
              <td class="px-6 py-4">	
                <div class="flex flex-col">
                  <p>31/07/2023</p>
                  <p>00:00</p>
                </div>
              </td>
              <td class="px-6 py-4">	
                MANDABEM30E
              </td>
              <td class="px-6 py-4">	
                1
              </td>
              <td class="px-6 py-4">	
                <div class="hidden sm:inline-flex text-white font-bold inline-flex items-center px-2 py-1 rounded-full gap-x-2 bg-yellow-500">
                  <div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>                
                  </div>
                  <p class="text-xs">Já utilizado</p>
                </div>
                <p class="text-xs text-yellow-500 sm:hidden flex">Já utilizado</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</x-app-layout>

<script>

</script>