<x-app-layout>
    <div class="sm:w-5/6 w-full ml-auto lg:px-12 p-4 flex justify-center items-center flex-col">
        <div class="sm:w-3/4 w-full bg-white shadow rounded p-4">

            <div class="p-2 rounded">
                <div class="text-gray-500 font-bold">
                    <h5 class="text-3xl ">Manifestações</h5>
                    @can('user_admin_mandabem')
                        <button
                            id="btn-upload-conferencia"
                            class="sm:text-sm text-xs bg-cyan-600 hover:bg-cyan-700 text-white font-bold px-2 py-1 mt-2 rounded flex items-center ml-1"> 
                            <i class="fa fa-upload"></i>                   
                            <p class="ml-1">Enviar Conferência CSV</p>
                        </button>
                    @endcan
                </div>
                <hr class="mt-4">
                @if($userTipo == 'mandabem' || $userTipo == 'auditor')
                    <div class="mt-4 flex flex-col sm:flex-row justify-between sm:space-x-6 space-x-0">
                        <div class="bg-green-200 rounded w-full p-2 border border-green-400">
                            <div class="flex items-center font-semibold">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 stroke-green-800">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                                </svg>
                                <h4 class="text-sm ml-1 text-green-800">Apuração Manifestações</h4>
                            </div>
                            <hr class="border-green-400 mt-4 border-dashed">
                            <div class="flex justify-between p-6">
                                <div class="sm:text-xl text-sm">
                                    <h4 class="font-light">Criadas</h4>
                                    <span class="font-semibold">{{$data->apuracao->criadas}}</span>
                                </div>
                                <div class="sm:text-xl text-sm">
                                    <h4 class="font-light">Aceitas</h4>
                                    <span class="font-semibold">{{$data->apuracao->aceitas}}</span>
                                </div>
                            </div>
                            <hr class="border-green-400 border-dashed">
                            <div class="p-6 sm:text-xl text-sm">
                                <h4 class="font-light">Indenizações Pagas</h4>
                                <span class="font-semibold">R$ {{$data->apuracao->valor_pago ? $data->apuracao->valor_pago : '0,00'}}</span>
                            </div>
                        </div>
                        <div class="bg-red-100 rounded w-full p-2 mt-4 sm:mt-0 border border-red-300">
                            <div class="flex items-center font-semibold">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 stroke-red-800">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                                </svg>
                                <h4 class="text-sm ml-1 text-red-800">Aberturas automáticas a partir de 01/06/2020</h4>
                            </div>
                            <hr class="border-red-300 mt-4 border-dashed">
                            <div class="flex justify-between p-6">
                                <div class="sm:text-xl text-sm">
                                    <h4 class="font-light">Criadas</h4>
                                    <span class="font-semibold">{{$data->abertura_automatica['criadas']}}</span>
                                </div>
                                <div class="sm:text-xl text-sm">
                                    <h4 class="font-light">Respondidas/Encerradas</h4>
                                    <span class="font-semibold">{{$data->abertura_automatica['respondidas']}}</span>
                                </div>
                            </div>
                            <hr class="border-red-300 border-dashed">
                            <div class="p-6 flex">
                            <div class="sm:text-xl text-sm">
                                    <h4 class="font-light">Aceitas</h4>
                                    <span class="font-semibold">{{$data->abertura_automatica['aceitas']}}</span>
                            </div>
                            <div class="ml-28 sm:text-xl text-sm">
                                    <h4 class="font-light">Negadas</h4>
                                    <span class="font-semibold">{{$data->abertura_automatica['negadas']}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <hr class="mt-4">
                    <div class="border rounded mt-4 p-4 mt-8">
                        <form action="#" method="GET">
                            <div class="flex sm:flex-row flex-col sm:space-x-8 space-x-0">
                                <div class="flex flex-col w-full mt-4">
                                    <label for="filter_periodo" class="text-sm text-gray-700">Período</label>
                                    <select onchange="toggleCustomFields()" id="filter_periodo" name="filter_periodo"
                                        class="px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                                        <option @if(request('filter_periodo') == 'semana_atual') selected @endif value="semana_atual">Semana Atual</option>
                                        <option @if(request('filter_periodo') == 'mes_atual') selected @endif value="mes_atual">Mês Atual</option>
                                        <option @if(request('filter_periodo') == 'current_year') selected @endif value="ano_atual">Ano Atual</option>
                                        <option @if(request('filter_periodo') == 'ano_anterior') selected @endif value="ano_anterior">Ano Anterior</option>
                                        <option @if(request('filter_periodo') == 'customizado') selected @endif value="customizado">Customizado</option>
                                    </select>
                                </div>

                                <div class="flex flex-col w-full mt-4">
                                    <label for="filter_etiqueta" class="text-sm text-gray-700">Etiqueta</label>
                                    <input required type="text" id="filter_etiqueta" name="filter_etiqueta" placeholder="Buscar por etiqueta..." class="px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                                </div>

                                <div class="flex flex-col w-full mt-4">
                                    <label for="filter_cliente" class="text-sm text-gray-700">Cliente</label>
                                    <input required type="text" id="filter_cliente" name="filter_cliente" placeholder="Buscar por ciente..." class="cliente px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                                </div>

                                <div class="flex flex-col w-full mt-4">
                                    <label for="filter_status" class="text-sm text-gray-700">Status</label>
                                    <select onchange="toggleCustomFields()" id="filter_status" name="filter_status"
                                        class="px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                                        <option @if(request('filter_status') == 'todos') selected @endif value="todos">Todos</option>
                                        <option @if(request('filter_status') == 'aberto') selected @endif value="aberto">Aberto</option>
                                        <option @if(request('filter_status') == 'fechado') selected @endif value="fechado">Fechado</option>
                                        <option @if(request('filter_status') == 'manifestacao_aceita') selected @endif value="manifestacao_aceita">Manifestação Aceita</option>
                                        <option @if(request('filter_status') == 'manifestacao_paga') selected @endif value="manifestacao_paga">Manifestação Paga</option>
                                        <option @if(request('filter_status') == 'pagar_e_sem_repasse_cliente') selected @endif value="pagar_e_sem_repasse_cliente">Pagas e sem repasse Cliente</option>
                                    </select>
                                </div>
                            </div>
            
                            <div class="flex flex-col sm:w-64 w-full mt-4">
                                <label for="filter_protocolo" class="text-sm text-gray-700">Protocolo</label>
                                <input required type="text" id="filter_protocolo" name="filter_cep" placeholder="Buscar por protocolo..." class="px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                            </div>

                            <div class="flex items-center mt-4">
                                <input 
                                    type="checkbox" 
                                    id="filter_only_auditor"
                                    name="filter_only_auditor"
                                    class="text-blue-500 border-gray-300 rounded w-3 h-3 shadow-sm"
                                >
                                <p class="block text-gray-600 text-sm ml-1">Apenas enviadas para Auditor</p>
                            </div>

                            <button
                                type="submit"
                                id="buttonCreateManifestacao"
                                class="sm:text-sm text-xs bg-blue-600 hover:bg-blue-700 text-white font-bold px-2 py-1 mt-2 rounded flex items-center"> 
                                <i class="fa fa-search"></i>                   
                                <p class="ml-1">Buscar</p>
                            </button>
                        </form>
                    </div>
                @endcan
            </div>
        </div>
        <div class="sm:w-3/4 w-full bg-white shadow rounded p-4 mt-4 overflow-x-auto">
            <table
                class="min-w-full table-auto ml-auto bg-white font-normal rounded
                text-xs sm:text-sm text-left text-gray-500 border-1 mt-2">
                <thead class="text-xs text-white uppercase bg-blue-400 font-bold">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Data Abertura
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Ecommerce	
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Protocolo de Abertura de Manifestação
                        </th>
                        <th scope="col" class="px-1 py-3">
                            Destinatário
                        </th>
                        <th scope="col" class="px-1 py-3">
                            Objeto
                        </th>
                        <th scope="col" class="px-1 py-3">
                            Situação
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-white hover:bg-gray-100 border rounded-full font-light">
                        <td class="px-6 py-4 max-w-sm">
                            03/05/2024 09:42
                        </td>
                        <td class="px-6 py-4">
                            Nácar Pratas
                        </td>
                        <td class="px-6 py-4 max-w-sm flex flex-col">
                            <p>184057406</p>
                            <p>Lote:292053664</p>
                            <b class="font-bold">Remessa/Objeto postal entregue em local divergente</b>
                        </td>
                        <td class="px-6 py-4 flex-col">
                            <p>Lucas Alves</p>
                            <p>
                                CEP: 
                                <a href=" class="text-blue-600">292053664</a>
                                <i class="fa fa-eye hover:text-blue-500 cursor-pointer"></i>
                            </p>
                            <span class="font-bold">Objeto entregue ao destinatário</span>
                        </td>
                        <td class="px-2 py-2">
                            <p>AA056085483BR</p>
                            <span>Postagem: <b class="font-bold">30/04/2024 19:18</b></span>
                        </td>
                        <td class="px-2 py-2">
                            <div class="bg-gray-500 w-20 rounded text-white font-bold">
                                <i class="fa fa-hourglass-half p-1"></i>
                                <small class="text-xs">Aberto</small>
                            </div>
                            <span>
                                Útima consulta: <b class="font-bold">03/05/2024 09:45</b>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">
                            <div class="border-r border-l border-b p-1">
                                <div class="border rounded p-2">
                                    <div class="flex text-xs">
                                        <p class="font-bold text-gray-700">Correios: </p>
                                        <span class="ml-2 text-red-600">R$ 6,82 </span>
                
                                        <p class="font-bold text-gray-700 ml-2">Seguro: </p>
                                        <span class="ml-2 text-red-600">R$ 6,82 </span>
                
                                        <p class="font-bold text-gray-700 ml-2">Valor Pago pelo Cliente: </p>
                                        <span class="ml-2 text-red-600">R$ 6,82 </span>
                                    </div>
                                    <div class="flex flex-row-reverse">
                                        <button
                                            type="submit"
                                            class="ml-2 text-xs bg-blue-600 hover:bg-blue-700 text-white font-bold px-2 py-1 mt-2 rounded flex items-center"> 
                                            <i class="fa fa-comments"></i>                   
                                            <p class="ml-1">Comentários (0)</p>
                                        </button>
                                        <button
                                            type="submit"
                                            class="ml-2 text-xs bg-green-600 hover:bg-green-700 text-white font-bold px-2 py-1 mt-2 rounded flex items-center"> 
                                            <i class="fa fa-check-square"></i>                   
                                            <p class="ml-1">Marcar como resolvido</p>
                                        </button>
                                        <button
                                            type="submit"
                                            class="ml-2 text-xs bg-green-600 hover:bg-green-700 text-white font-bold px-2 py-1 mt-2 rounded flex items-center"> 
                                            <i class="fa fa-check"></i>                   
                                            <p class="ml-1">Enviado auditor</p>
                                        </button>
                                    </div>
                                    <hr class="mt-6">
                                    <div class="bg-yellow-100 w-full mt-4 p-4 rounded text-xs">
                                        <div class="flex items-center">
                                            <i class="fa fa-info-circle text-yellow-600"></i>
                                            <p class="ml-1 text-yellow-600 font-bold">Resumo:</p>
                                        </div>
                                        <div class="mt-4">
                                            <span>Destinatário alega não ter recebido o objeto. Consegue nos ajudar fornecendo uma foto da assinatura do recebedor, local de entrega ou algum tipo de confirmação?</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="border-green-600 border">
                        </td>
                    </tr>
                </tbody>              
            </table> 
        </div>
    </div>
</x-app-layout>
