<div class="justify-center items-center hidden" id="modal_boletos">
    <div class="fixed inset-0 px-2 z-10 flex items-start justify-center animate__animated animate__fadeIn pb-16">
        <div class="absolute inset-0 bg-gray-800 bg-opacity-75 transition-opacity"></div>

        <!-- Modal Content -->
        <div
            class="sm:ml-56 ml-0 inline-block align-bottom bg-white
            rounded-lg text-left shadow-xl overflow-y-auto
            transform transition-all sm:my-8 sm:align-middle sm:w-3/5">
            <!-- Modal Header -->
            <div class="text-gray-600 px-4 py-4 flex justify-between bg-gray-200">
                <div class="flex items-center text-gray-500 font-bold text-3xl">
                    <i class="fa fa-hourglass-half"></i>
                    <h1 class="ml-1">Boletos Pendentes</h1>
                </div>
                <svg
                    onclick="fechaModalBoletos()"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 cursor-pointer">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </div>

            <div class="mt-4 p-4">
                <form action="#" method="GET"
                    class="mt-1 flex flex-col w-full p-4 items-end border rounded bg-white shadow">
                    <div class="flex items-center w-full">
                        <div class="flex flex-col w-full ml-2">
                            <label for="filtrar_situacao" class="text-sm text-gray-700">Filtrar situação</label>
                            <select id="filtrar_situacao" name="tipo"
                                class="px-1 py-1 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                                <option @if (request('filtrar_situacao') == 'todos') selected @endif value="todos">Todos</option>
                                <option @if (request('filtrar_situacao') == 'credito_pendente') selected @endif value="credito_pendente">Cŕedito Pendente</option>
                                <option @if (request('filtrar_situacao') == 'liberado_c_pagamento_pendente') selected @endif value="liberado_c_pagamento_pendente">Liberados c/ Pagamento pendente</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center w-full">
                        <div class="flex flex-col w-full ml-2">
                            <label for="filtrar_cliente" class="text-sm text-gray-700">Filtrar cliente</label>
                            <input type="text" id="filtrar_cliente" name="filtrar_cliente" placeholder="Busque pelo cliente..."
                                class="px-1 py-1 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                        </div>
                    </div>

                    <div class="mt-2 flex items-center space-x-1">
                        <button
                            class="bg-green-600 hover:bg-green-700 text-white font-bold px-2 py-1 rounded flex items-center text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                            <p class="ml-1">Filtrar</p>
                        </button>
                        <a href="{{route('boletos.todos')}}" class="bg-cyan-600 hover:bg-cyan-700 text-white font-bold px-2 py-1 rounded flex items-center text-sm">
                            <i class="fa fa-eye"></i>
                            <p class="ml-1">Todos</p>
                        </a>
                    </div>
                </form>
            </div>

            <div class="mt-6 p-4 overflow-y-auto h-96">
                <table class="min-w-full table-auto ml-auto bg-white font-normal rounded text-xs text-left text-gray-500 border-collapse border-2">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-300">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Data
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Valor
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Cliente
                            </th>
                            <th scope="col" class="px-1 py-3">
                                Crédito
                            </th>
                            <th scope="col" class="px-1 py-3">
                                Pagto
                            </th>
                            <th scope="col" class="px-1 py-3">
                                Comprovantes
                            </th>
                            <th scope="col" class="px-1 py-3">
                                Impressão
                            </th>
                            <th scope="col" class="px-1 py-3">
                                Opções
                            </th>
                        </tr>
                    </thead>
                    <tbody id="resultBoletos">
      
                    </tbody>
                </table>
            </div>
        
        </div>
    </div>
</div>
