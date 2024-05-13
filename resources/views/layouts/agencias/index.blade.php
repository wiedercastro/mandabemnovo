<x-app-layout>
    <div class="sm:w-5/6 w-full ml-auto lg:px-12 p-4 flex justify-center items-center flex-col">

        <div class="w-full p-4">
            <h5 class="text-xl sm:text-4xl ml-1 text-gray-500 font-bold">Ranking das 1000 Agências com mais envios a partir de 01/01/2021</h5>
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
                                <div class="flex cursor-pointer">
        
                                    <button>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor"
                                            class="w-4 h-4 sm:w-5 sm:h-6 stroke-red-600">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
