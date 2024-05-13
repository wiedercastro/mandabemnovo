<x-app-layout>
    <div class="sm:w-5/6 w-full ml-auto lg:px-12 p-4 flex justify-center items-center flex-col">
        <div class="sm:w-3/4 w-full bg-white shadow rounded p-4">

            <div class="p-2 rounded">
                <div class="flex items-center text-xl sm:text-4xl text-gray-500 font-bold">
                    <h5 class="ml-1">Apuração</h5>
                </div>
                <hr class="mt-6">
                <form action="{{route('maps')}}" method="GET" class="border p-4 rounded mt-6">
                    <div class="flex sm:flex-row flex-col space-x-0 sm:space-x-6">
                        <div class="flex flex-col w-full mt-4">
                            <label for="cliente" class="text-sm text-gray-700">Cliente</label>
                            <input type="text" id="cliente" name="cliente" placeholder="Cliente" class="px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                        </div>
        
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
                <h1 class="text-xl sm:text-4xl text-gray-500 font-bold mt-8">Resultado</h1>
                <div class="border rounded p-2 mt-2">
                    <div class="flex flex-col">
                        <div class="-m-1.5 overflow-x-auto">
                            <div class="p-1.5 min-w-full inline-block align-middle">
                                <div class="border overflow-hidden">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium">Objeto</th>
                                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium">Data Postagem</th>
                                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium">Valor Cobrado</th>
                                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium">Valor Correios</th>
                                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium">Valor Industrial</th>
                                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium">Taxa Aplicada</th>
                                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium">Taxa Corrigida</th>
                                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium">Correção</th>
                                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium">Devolver</th>
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
                                                   
                                                </td>
                                                <td class="px-2 py-4 whitespace-nowrap text-xs">
                                                    R$ 3,17
                                                </td>
                                                <td class="px-2 py-4 whitespace-nowrap text-xs">
                                                    R$ 3,17
                                                </td>
                                                <td class="px-2 py-4 whitespace-nowrap text-xs">
                                                    R$ 0,00	
                                                </td>
                                                <td class="px-2 py-4 whitespace-nowrap text-xs">
                                                    <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="7" class="p-2 text-gray-500 font-bold text-xl">Total a Devolver:</td>
                                                <td class="text-right p-2 text-gray-600 text-sm">teste</td>
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
