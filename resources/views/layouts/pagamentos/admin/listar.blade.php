<x-app-layout>
    <div class="w-5/6 ml-auto lg:px-12">
        <div class="w-full">

            <x-card-pagamentos_admin/>
            <x-menu-navigation-pagamentos/>
            <x-modal-cobranca/>

            <div class="flex flex-row-reverse">
                <button
                    class="bg-red-500 hover:bg-red-700 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                    <p class="ml-1">Afiliados</p>
                </button>
                <button
                    class="bg-cyan-500 hover:bg-cyan-700 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
                    </svg>
                    <p class="ml-1">Transferências</p>
                </button>
                <button
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">
                    <i class="fa fa-barcode"></i>
                    <p class="ml-1">Boletos</p>
                </button>
                <button
                    class="bg-orange-500 hover:bg-orange-700 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">
                    <i class="fa fa-barcode"></i>
                    <p class="ml-1">Cŕedito</p>
                </button>
                <button
                    onclick="abreModalCobranca()"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">
                    <i class="fa fa-minus-circle"></i>
                    <p class="ml-1">Cobrança</p>
                </button>
                <button
                    class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                    </svg>   
                    <p class="ml-1">Pagamentos</p>
                </button>
            </div>

            <div class="mt-8 w-3/5">
                <form action="{{ route('estatisticas_admin_index') }}" method="GET"
                    class="mt-1 flex w-full flex-col space-x-1 p-4 items-end border rounded bg-white">
                    <p class="border border-yellow-500 rounded p-2 bg-yellow-50 text-sm text-yellow-700 font-bold">
                        R$ 1234,00
                    </p>
                    <div class="flex items-center w-full">
                        <div class="flex flex-col w-full">
                            <label for="" class="text-sm text-gray-700">Período</label>
                            <select onchange="toggleCustomFields()" id="periodo" name="periodo"
                                class="px-1 py-1 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                                <option @if (request('periodo') == 'semana_atual') selected @endif value="semana_atual">Semana
                                    Atual</option>
                                <option @if (request('periodo') == 'mes_atual') selected @endif value="mes_atual">Mês Atual
                                </option>
                                <option @if (request('periodo') == 'ano_atual') selected @endif value="ano_atual">Ano Atual
                                </option>
                                <option @if (request('periodo') == 'ano_anterior') selected @endif value="ano_anterior">Ano
                                    Anterior</option>
                                <option @if (request('periodo') == 'customizado') selected @endif value="customizado">
                                    Customizado</option>
                            </select>
                        </div>

                        <div class="flex flex-col w-full ml-2">
                            <label for="cliente" class="text-sm text-gray-700">Cliente</label>
                            <input type="text" id="cliente" name="cliente"
                                class="px-1 py-1 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                        </div>

                        <div class="flex flex-col w-full ml-2">
                            <label for="tipo" class="text-sm text-gray-700">Tipo</label>
                            <select id="tipo" name="tipo"
                                class="px-1 py-1 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                                <option @if (request('tipo') == 'cobranca') selected @endif value="cobranca">Cobrança</option>
                                <option @if (request('tipo') == 'boleto') selected @endif value="boleto">Boleto</option>
                                <option @if (request('tipo') == 'ano_atual') selected @endif value="ano_atual">Ano Atual</option>
                                <option @if (request('tipo') == 'transferencia') selected @endif value="transferencia">Transferência</option>
                                <option @if (request('tipo') == 'transferencia_mercado_pago') selected @endif value="transferencia_mercado_pago">Transferência Mercado Pago</option>
                                <option @if (request('tipo') == 'transferencia_e_boleto') selected @endif value="transferencia_e_boleto">Transferência e Boleto</option>
                                <option @if (request('tipo') == 'credito') selected @endif value="credito">Crédito</option>
                                <option @if (request('tipo') == 'outros') selected @endif value="outros">Outros</option>
                            </select>
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
                <h1 class="text-gray-500 font-bold text-3xl text">Cobranças</h1>
                <table
                    class="min-w-full table-auto ml-auto bg-white font-normal rounded shadow-lg
          text-sm text-left text-gray-500 border-collapse overflow-x-auto border-1">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-300">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                DATA
                            </th>
                            <th scope="col" class="px-6 py-3">
                                TIPO
                            </th>
                            <th scope="col" class="px-6 py-3">
                                DESCRIÇÃO
                            </th>
                            <th scope="col" class="px-1 py-3">
                                ECCOMERCE
                            </th>
                            <th scope="col" class="px-1 py-3">
                                ID TRANSAÇÃO
                            </th>
                            <th scope="col" class="px-1 py-3">
                                VALOR
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
