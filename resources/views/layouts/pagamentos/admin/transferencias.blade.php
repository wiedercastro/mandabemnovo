<x-app-layout>
    <div class="w-5/6 ml-auto lg:px-12">
        <div class="w-full">

            <x-card-pagamentos_admin/>
            <x-menu-navigation-pagamentos/>

            <div class="mt-8 w-96">
                <form action="{{ route('estatisticas_admin_index') }}" method="GET"
                    class="mt-1 flex w-full flex-col space-x-1 p-4 items-end border rounded bg-white">
                    <div class="flex items-center w-full">
                        <div class="flex flex-col w-full ml-2">
                            <label for="cliente" class="text-sm text-gray-700">Cliente</label>
                            <input type="text" id="cliente" name="cliente" placeholder="Busque pelo cliente..."
                                class="px-1 py-1 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                        </div>
                    </div>

                    <div class="mt-1 flex items-center space-x-1">
                        <button
                            class="bg-green-600 hover:bg-green-700 text-white font-bold px-2 py-1 rounded flex items-center text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                            <p class="ml-1">Filtrar</p>
                        </button>
                        <button
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-2 py-1 rounded flex items-center text-sm">
                            <i class="fa fa-download"></i>
                            <p class="ml-1">Exportar</p>
                        </button>
                    </div>
                </form>
            </div>

            <div class="mt-6">
                <h1 class="text-gray-500 font-bold text-3xl text">Transferências Pendentes</h1>
                <table
                    class="min-w-full table-auto ml-auto bg-white font-normal rounded shadow-lg
          text-sm text-left text-gray-500 border-collapse overflow-x-auto border-1">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-300">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Data
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Tipo Pagto
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Cliente
                            </th>
                            <th scope="col" class="px-1 py-3">
                                Valor Solicitado
                            </th>
                            <th scope="col" class="px-1 py-3">
                                Comprovante
                            </th>
                            <th scope="col" class="px-1 py-3">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white hover:bg-gray-100 border-b rounded-full font-light">
                            <td class="px-6 py-4">
                                1
                            </td>
                            <td class="px-6 py-4 flex items-center">
                                <i class="fa fa-qrcode"></i>
                                <p class="ml-1 font-bold">PIX</p>
                            </td>
                            <td class="px-6 py-4 max-w-sm">
                                Crédito concedido por transferência (PIX) em 26/04/2024(PIX)
                            </td>
                            <td class="px-6 py-4 max-w-sm">
                                DONA BAMBINA TIARAS E LAÇOS
                            </td>
                            <td class="px-2 py-2 text-blue-400 font-bold">
                                963DBA868A534ABC98E7648669C5EB66	
                            </td>
                            <td class=" py-2 text-[#154864] font-bold">
                                R$ 20,00
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
