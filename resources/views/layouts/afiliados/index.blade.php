<x-app-layout>

    <div class="sm:w-5/6 w-full ml-auto lg:px-12 p-4">

        <div class="mt-4">
            <div class="bg-red-800 rounded-lg w-full sm:w-1/2 mt-6 text-white">
                <div class="p-4 font-light text-xs sm:text-sm">
                    <div class="flex items-center">
                        <div class="w-2/5"></div>
                        <div class="flex-1 flex justify-between">
                            <div class="font-bold">NOVO</div>
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 sm:w-5 sm:h-5 text-green-500">
                                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                                </svg>                          
                                <div class="font-bold">ATIVO</div>
                            </div>
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 sm:w-5 sm:h-5 stroke-red-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                              
                                <div class="font-bold">INATIVO</div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center mt-4">
                        <div class="w-2/5 font-bold">Afiliado</div>
                        <div class="flex-1 flex justify-between mr-6">
                            <div>0</div>
                            <div>1</div>
                            <div>4</div>
                        </div>
                    </div>
                    <div class="flex items-center mt-4">
                        <div class="w-2/5 font-bold">Indicado</div>
                        <div class="flex-1 flex justify-between mr-6">
                            <div>0</div>
                            <div>1</div>
                            <div>4</div>
                        </div>
                    </div>
                    <hr class="mt-4 border-gray-100 border-dashed">
                    <div class="flex items-center mt-8">
                        <div class="w-2/5 font-bold">Custo</div>
                        <div class="flex-1 flex justify-between">
                            <div class="font-bold">30D</div>
                            <div class="font-bold">Ano</div>
                            <div class="font-bold">Total</div>
                        </div>
                    </div>
                    <hr class="mt-4 border-gray-100 border-dashed">
                    <div class="flex items-center mt-8">
                        <div class="w-2/5 font-bold">Comissão</div>
                        <div class="flex-1 flex justify-between">
                            <div>R$ 0,00</div>
                            <div>R$ 433.02</div>
                            <div>R$ 433.02</div>
                        </div>
                    </div>
                    <div class="flex items-center mt-4">
                        <div class="w-2/5 font-bold">Faturamento</div>
                        <div class="flex-1 flex justify-between">
                            <div>R$ 3.21</div>
                            <div>R$ 2123.25</div>
                            <div>R$ 2123.25</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        

        <div class="mt-16">

            <div class="block sm:flex flex-row-reverse">
                <form action="#" class="mt-1 flex flex-col space-x-1 p-4 items-end border rounded bg-white shadow">
                    <div class="flex flex-col w-full">
                        <label for="email" class="text-sm text-gray-700">E-mail</label>
                        <input id="email" name="email" placeholder="Busque por um e-mail" class="px-1 py-1 sm:w-96 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                    </div>

                    <div class="flex flex-col w-full mt-2">
                        <label for="cliente" class="text-sm text-gray-700">Cliente</label>
                        <input id="cliente" name="cliente" placeholder="Busque por um cliente" class="px-1 py-1 sm:w-96 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                    </div>
                    
                    <button type="submit"
                        class="text-white font-bold text-xs justify-center
                        hover:bg-blue-700 rounded mt-2 flex items-center
                        bg-blue-600 px-2 py-1.5 w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                          </svg>
                          
                        <p class="ml-1">Procurar</p>
                    </button>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table
                    class="min-w-full mt-4 table-auto ml-auto bg-white font-normal rounded shadow-lg
                    sm:text-sm text-xs text-left text-gray-500 border-collapse overflow-x-auto border-1">
                      <thead class="text-xs text-gray-700 bg-gray-200">
                    <thead class="text-gray-700 bg-gray-200">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                ID	
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Afiliado
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Indicados
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Envios
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Ranking
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Envio/Indicados
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Valor Pago
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Valor MB	
                            </th>
                            <th scope="col" class="px-6 py-3">
                                DUI
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b rounded-full font-light cursor-pointer">
                            <td class="px-6 py-4">
                                0
                            </td>
                            <td class="px-6 py-4">
                                R$ 0,00
                            </td>
                            <td class="px-6 py-4">
                                R$ 0,00
                            </td>
                            <td class="px-6 py-4">
                                R$ 0,00
                            </td>
                            <td class="px-6 py-4">
                                R$ 0,00
                            </td>
                            <td class="px-6 py-4">
                                R$ 0,00
                            </td>
                            <td class="px-6 py-4">
                                R$ 0,00
                            </td>
                            <td class="px-6 py-4">
                                R$ 0,00
                            </td>
                            <td class="px-6 py-4">
                                R$ 0,00
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
</x-app-layout>