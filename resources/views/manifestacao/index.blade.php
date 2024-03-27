<x-app-layout>
  <div class="sm:w-5/6 w-full ml-auto lg:px-12 p-4">
    <div class="flex justify-center sm:hidden">
      <img src="{{asset('images/logo_mandabem_az.png')}}" alt="" class="w-32"/>
    </div>
    <h1 class="text-gray-500 font-bold text-4xl mt-10">Manifestações</h1>
    <div class="overflow-x-auto">
      <table class="mt-4 w-full table-auto rounded shadow-lg
        text-sm text-left text-gray-500 border-collapse overflow-x-auto border-1">
        <thead class="text-xs text-white font-bold uppercase bg-gray-500">
          <tr>
            <th scope="col" class="px-6 py-3">
              Data Abertura
            </th>
            <th scope="col" class="px-6 py-3">
              Protocolo
            </th>
            <th scope="col" class="px-6 py-3">
              Destinatário
            </th>
            <th scope="col" class="px-6 py-3">
              Objeto	
            </th>
            <th scope="col" class="px-6 py-3">
              Situação
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
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</x-app-layout>