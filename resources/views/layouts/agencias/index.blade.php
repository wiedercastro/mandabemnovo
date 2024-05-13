<x-app-layout>
    <div class="sm:w-5/6 w-full ml-auto lg:px-12 p-4 flex justify-center items-center flex-col">
        <x-modal-agencias-atualiza-endereco />

        <div class="w-full p-4">
            <h5 class="text-xl sm:text-4xl ml-1 text-gray-500 font-bold">Ranking das 1000 Agências com mais envios a partir de 01/01/2021</h5>
            <form action="#" method="GET" class="mt-1 flex flex-col sm:flex-row space-x-1 items-center sm:items-end w-full sm:w-96 mt-10">
                <div class="flex flex-col w-full">
                    <label for="order_by" class="text-gray-600 sm:text-sm text-xs">Ordenar Por</label>
                        <select 
                            required
                            id="order_by"
                            name="order_by"
                            class="p-1 w-full sm:w-72 border outline-none rounded border-gray-200 bg-white shadow text-gray-600">
                        <option value="clientes">Clientes</option>
                        <option value="envios">Envios</option>
                      </select>
                </div>

                <button type="submit"
                    class="text-white font-bold text-xs w-full
                    hover:bg-blue-700 rounded border mt-2 flex items-center justify-center
                    bg-blue-600 px-2 py-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <p class="ml-1">Buscar</p>
                </button>
            </form>
            <div class="overflow-x-auto mt-8">
                <table
                    class="min-w-full table-auto ml-auto bg-white font-normal rounded shadow-lg
                    text-xs sm:text-sm text-left text-gray-500 border-collapse border-1 mt-2">
                    <thead class="text-xs text-white uppercase bg-blue-600">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Rank
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Nome
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Localidade
                            </th>
                            <th scope="col" class="px-1 py-3">
                                Cod Correios
                            </th>
                            <th scope="col" class="px-1 py-3">
                                Número Clientes
                            </th>
                            <th scope="col" class="px-1 py-3">
                                Número Envios
                            </th>
                            <th scope="col" class="px-1 py-3">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b rounded-full font-light">
                            <td class="px-6 py-4 max-w-sm">
                                teste
                            </td>
                            <td class="px-6 py-4">
                                0
                            </td>
                            <td class="px-6 py-4 max-w-sm">
                                teste
                            </td>
                            <td class="px-2 py-2">
                                teste
                            </td>
                            <td class="px-6 py-4">
                                teste
                            </td>
                            <td class="px-6 py-4">
                                teste
                            </td>
                            <td class="px-2 py-2">
                                <button 
                                id="buttonAtualizaEnderecoUsuario"
                                onclick="abreModalAtualizaEndereco()"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded cursor-pointer">
                                    <i class="fa fa-address-book"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
