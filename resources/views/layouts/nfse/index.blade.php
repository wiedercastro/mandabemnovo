<x-app-layout>
    <div class="sm:w-5/6 w-full ml-auto lg:px-12 p-4 flex justify-center items-center flex-col">
        <div class="sm:w-3/4 w-full bg-white shadow rounded p-4">

            <x-modal-gerar-nfse />

            <div class="p-2 rounded">
                <h5 class="text-xl sm:text-4xl text-gray-500 font-bold">NFSe (Nota Fiscal Serviço Eletronica)</h5>
                <button
                    type="submit"
                    class="sm:text-sm text-xs bg-yellow-500 hover:bg-yellow-600 hover:text-white text-gray-700 px-2 py-1 mt-2 rounded flex items-center"> 
                    <i class="fa fa-download"></i>                   
                    <p class="ml-1">Exportar ZIP (mensal)</p>
                </button>
                <hr class="mt-6">
                <div class="flex sm:flex-row flex-col items-center space-x-0 sm:space-x-6">
                        <form action="{{route('maps')}}" method="GET" class="border p-4 rounded mt-6 w-full">
                            <div class="mt-4">
                                <p class="text-2xl text-gray-500 font-bold">Filtro</p>
                                <hr>
                            </div>
                            <div class="flex sm:flex-row flex-col space-x-0 sm:space-x-6">
                                <div class="flex flex-col w-full mt-4">
                                    <label for="cliente" class="text-sm text-gray-700">Mês</label>
                                    <input type="text" id="cliente" name="cliente" placeholder="Cliente" class="px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                                </div>

                                <div class="flex flex-col w-full mt-4">
                                    <label for="cliente" class="text-sm text-gray-700">Cliente</label>
                                    <input type="text" id="cliente" name="cliente" placeholder="Cliente" class="px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                                </div>
                            </div>
                            <div class="flex sm:flex-row flex-col space-x-0 sm:space-x-6">      
                                <div class="flex flex-col w-full mt-4">
                                    <label for="date_inicial" class="text-sm text-gray-700">Data postagem inicial</label>
                                    <input type="text" id="date_inicial" name="date_inicial" placeholder="dd/mm/yyyy" class="cliente px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                                </div>
            
                                <div class="flex flex-col w-full mt-4">
                                    <label for="estado" class="text-sm text-gray-700">Data postagem final</label>
                                    <input type="text" id="date_fim" name="date_fim" placeholder="dd/mm/yyyy" class="cliente px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                                </div>
                            </div>
            
                            <div>
                                <button
                                    type="submit"
                                    class="sm:text-sm text-xs bg-blue-600 hover:bg-blue-700 text-white font-bold px-2 py-1 mt-6 rounded flex items-center"> 
                                    <i class="fa fa-search"></i>                   
                                    <p class="ml-1">Buscar</p>
                                </button>
                            </div>
                        </form>
                        <div class="sm:w-2/5 w-full border rounded p-2 sm:mt-0 mt-4">
                            <h1 class="text-xl text-gray-500 font-bold">Total</h1>
                            <hr class="mt-2">
                            <div class="flex items-center mt-2">
                                <p>Qtde:</p>
                                <span class="text-red-600 ml-1">253298</span>
                            </div>
                            <span class="text-xl">R$ 15.758.304,10</span>
                        </div>
                </div>
            </div>
        </div>
        <div class="w-full mt-4">
            <button
                onclick="modalGerarNFSe()"
                type="submit"
                class="sm:text-sm text-xs bg-green-600 hover:bg-green-700 text-white font-bold px-2 py-1 mt-6 rounded flex items-center"> 
                <i class="fa fa-cog"></i>                   
                <p class="ml-1">Gerar NFSe</p>
            </button>
        </div>
        <div class="w-full bg-white shadow rounded p-4 mt-4">

            <div class="p-2 rounded">
                <div class="border rounded p-2 mt-2">
                    <div class="flex flex-col">
                        <div class="-m-1.5 overflow-x-auto">
                            <div class="p-1.5 min-w-full inline-block align-middle">
                                <div class="border overflow-hidden">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-blue-500 text-white font-bold">
                                            <tr>
                                                <th scope="col" class="px-6 py-1 uppercase text-center text-xs">Número</th>
                                                <th scope="col" class="px-6 py-1 uppercase text-center text-xs">Data</th>
                                                <th scope="col" class="px-6 py-1 uppercase text-center text-xs">Cliente</th>
                                                <th scope="col" class="px-6 py-1 uppercase text-center text-xs">Status</th>
                                                <th scope="col" class="px-6 py-1 uppercase text-center text-xs">Valor</th>
                                                <th scope="col" class="px-6 py-1 uppercase text-center text-xs">Ambiente</th>
                                                <th scope="col" class="px-6 py-1 uppercase text-center text-xs">Opções</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            <tr class="text-center text-gray-800">
                                                <td class="px-2 py-4 whitespace-nowrap text-xs font-medium">
                                                    PZ250978554BR
                                                </td>
                                                <td class="px-2 py-4 whitespace-nowrap text-xs">
                                                    18/08/2020
                                                </td>
                                                <td class="px-2 py-4 whitespace-nowrap text-xs">
                                                    R$ 29,55
                                                </td>
                                                <td class="px-2 py-4 whitespace-nowrap text-xs">
                                                    R$ 26,38
                                                </td>
                                                <td class="px-2 py-4 whitespace-nowrap text-xs">
                                                    R$ 3,17
                                                </td>
                                                <td class="px-2 py-4 whitespace-nowrap text-xs">
                                                    R$ 0,00	
                                                </td>
                                                <td class="px-2 py-2">
                                                    <button class="text-xs bg-red-500 hover:bg-red-600 px-2 py-1 rounded text-white font-bold inline-flex items-center">
                                                        <i class="fa fa-ban"></i>
                                                        <p class="ml-1">Cancelar NF</p>
                                                    </button>
                            
                                                    <button class="text-xs bg-blue-500 hover:bg-blue-600 px-2 py-1 rounded text-white font-bold inline-flex items-center">
                                                        <i class="fa fa-file"></i>
                                                        <p class="ml-1">PDF</p>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
