<x-app-layout>
    <div class="sm:w-5/6 w-full ml-auto lg:px-12 p-4 flex justify-center items-center flex-col">
        <div class="w-3/4 bg-white shadow rounded p-4">

            <div class="border p-2 rounded">
                <div class="flex items-center text-gray-500 font-bold">
                    <i class="fa fa-list"></i>
                    <h5 class="text-2xl ml-1"> Incluir nova tabela</h5>
                </div>
                <hr class="mt-4">
                <div class="mt-4 flex justify-between space-x-6">
                    <div class="border rounded w-full p-2">
                        <h1 class="text-gray-500 font-bold">Simulação Normal</h1>

                        <form action="#" method="POST" class="mt-2" id="submitFormSimulacaoNormal">
      
                            @csrf
                            <input type="hidden" name="csrfToken" value="{{ csrf_token() }}" id="csrfToken">
                            <input type="hidden" name="idEtiquetasManifestacao" value="" id="idEtiquetasManifestacao">

                            <div class="flex flex-col w-full mt-4">
                                <label for="cliente" class="text-sm text-gray-700">Cliente</label>
                                <input required type="text" id="cliente_simulacao" name="cliente_simulacao" placeholder="Busque um cliente" class="cliente px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                            </div>

                            <div class="flex flex-col w-full mt-4">
                                <label for="cep_avulso" class="text-sm text-gray-700">ou CEP Avulso</label>
                                <input required type="text" id="cep_avulso" name="cep_avulso" placeholder="ou CEP Avulso" class="cliente px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                            </div>
                
                            <div class="mt-4">
                                <label for="categoria" class="block text-gray-700 text-sm">Tipo</label>
                                <select required name="tipo_remessa" id="tipo_remessa" class="text-gray-700 p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                    <option value="" disabled selected >Selecione</option>
                                    <option value="132">NORMAL</option>
                                    <option value="133">Envio mini</option>
                                </select>
                            </div>
                
                            <div class="flex items-center mt-4">
                                <input 
                                    type="checkbox" 
                                    id="visivel_mandabem"
                                    name="visivel_mandabem"
                                    class="text-blue-500 border-gray-300 rounded w-3 h-3 shadow-sm"
                                >
                                <p class="block text-gray-700 text-sm ml-1">Desconsiderar taxa</p>
                            </div>

                            <button
                                type="submit"
                                id="buttonCreateManifestacao"
                                class="text-sm bg-green-600 hover:bg-green-700 text-white font-bold px-2 py-1 mt-6 rounded flex items-center"> 
                                <i class="fa fa-plus"></i>                   
                                <p class="ml-1">Adicionar</p>
                            </button>
                        </form>
                    </div>
                    <div class="border rounded w-full p-2">
                        <h1 class="text-gray-500 font-bold">Simulação Amazon</h1>
                        <form action="#" method="POST" class="mt-2" id="submitFormSimulacaoAmazon">
      
                      
                            @csrf
                            <input type="hidden" name="csrfToken" value="{{ csrf_token() }}" id="csrfToken">
                            <input type="hidden" name="idEtiquetasManifestacao" value="" id="idEtiquetasManifestacao">

                            <div class="flex flex-col w-full mt-4">
                                <label for="cliente" class="text-sm text-gray-700">Cliente</label>
                                <input required type="text" id="cliente_simulacao" name="cliente_simulacao" placeholder="Busque um cliente" class="cliente px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                            </div>
                
                            <div class="mt-4">
                                <label for="categoria" class="block text-gray-700 text-sm flex items-center">
                                    <span>Peso por Pedido</span>
                                    <i class="fa fa-info-circle text-blue-600 ml-1"></i>
                                </label>
                                <select required name="tipo_remessa" id="tipo_remessa" class="text-gray-700 p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                    <option value="" disabled selected >Selecione</option>
                                    <option value="132">NORMAL</option>
                                    <option value="133">Envio mini</option>
                                </select>
                            </div>

                            <div class="flex flex-col w-full mt-4">
                                <label for="cep_avulso" class="text-sm text-gray-700">ou CEP Avulso</label>
                                <input required type="text" id="cep_avulso" name="cep_avulso" placeholder="ou CEP Avulso" class="cliente px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                            </div>
                
                            <div class="flex items-center mt-4">
                                <input 
                                    type="checkbox" 
                                    id="visivel_mandabem"
                                    name="visivel_mandabem"
                                    class="text-blue-500 border-gray-300 rounded w-3 h-3 shadow-sm"
                                >
                                <p class="block text-gray-700 text-sm ml-1">Desconsiderar taxa</p>
                            </div>

                            <button
                                type="submit"
                                id="buttonCreateManifestacao"
                                class="text-sm bg-green-600 hover:bg-green-700 text-white font-bold px-2 py-1 mt-6 rounded flex items-center"> 
                                <i class="fa fa-cogs"></i>                   
                                <p class="ml-1">Gerar</p>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-3/4 bg-white shadow rounded p-4 mt-4">
            <form action="#" method="GET">

                <div class="flex flex-col w-full mt-4">
                    <label for="filter_cep" class="text-sm text-gray-700">CEP</label>
                    <input required type="text" id="filter_cep" name="filter_cep" placeholder="Buscar por CEP..." class="cliente px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                </div>

                <div class="flex items-center space-x-2">
                    <button
                        type="submit"
                        id="buttonCreateManifestacao"
                        class="text-sm bg-blue-600 hover:bg-blue-700 text-white font-bold px-2 py-1 mt-6 rounded flex items-center"> 
                        <i class="fa fa-search"></i>                   
                        <p class="ml-1">Filtrar</p>
                    </button>
                    <button
                        type="submit"
                        id="buttonCreateManifestacao"
                        class="text-sm bg-red-600 hover:bg-red-700 text-white font-bold px-2 py-1 mt-6 rounded flex items-center"> 
                        <i class="fa fa-trash"></i>                   
                        <p class="ml-1">Limpar filtro</p>
                    </button>
                </div>
            </form>
        </div>

        <div class="w-3/4 p-4 mt-4 overflow-x-auto">
            <div class="flex items-center text-gray-500 font-bold">
                <h5 class="text-3xl ml-1">Tabelas Criadas</h5>
            </div>
            <table
                class="min-w-full table-auto ml-auto bg-white font-normal rounded shadow-lg
                text-xs sm:text-sm text-left text-gray-500 border-collapse border-1 mt-2">
                <thead class="text-xs text-gray-700 uppercase bg-gray-300">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            CEP Origem
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Tipo
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Criação
                        </th>
                        <th scope="col" class="px-1 py-3">
                            Status
                        </th>
                        <th scope="col" class="px-1 py-3">
                            Opções
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-white hover:bg-gray-100 border-b rounded-full font-light">
                        <td class="px-6 py-4 max-w-sm">
                            <p class="font-bold">35521318 (Nova Serrana)</p>
                            (Matheus Fernandes dos Santos Oxente Calçados)
                            (Renata Cristina Pereira de Castro Viana John Renata Calçados)
                            (Amanda Stephane Moura Barbosa Amafê Imports)
                            (MaisLinda_Chinelaria MaisLinda Chinelaria)
                        </td>
                        <td class="px-6 py-4">
                            DEFAULT
                        </td>
                        <td class="px-6 py-4 max-w-sm">
                            02/05/2024
                        </td>
                        <td class="px-2 py-2">
                            <div class="flex items-center">
                                <i class="fa fa-check text-green-600"></i>  
                                <span class="ml-1">Tabela Gerada</span>
                            </div>
                        </td>
                        <td class="px-2 py-2">
                            <div class="flex cursor-pointer">
                                <button>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 sm:w-5 sm:h-6 stroke-yellow-600">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15M9 12l3 3m0 0 3-3m-3 3V2.25" />
                                    </svg>                                      
                                </button>
        
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
</x-app-layout>
