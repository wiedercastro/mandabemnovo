<div class="justify-center items-center hidden" id="modal_transferencias">
    <div class="fixed inset-0 px-2 z-10 flex items-start justify-center animate__animated animate__fadeIn">
        <div class="absolute inset-0 bg-gray-800 bg-opacity-75 transition-opacity"></div>
        <!-- Modal Content -->
        <div
            class="sm:ml-56 ml-0 inline-block align-bottom bg-white
            rounded-lg text-left shadow-xl overflow-y-auto
            transform transition-all sm:my-8 sm:align-middle sm:w-2/5 w-full sm:h-96 h-auto">
            <!-- Modal Header -->
            <div class="text-gray-600 px-4 py-4 flex justify-between bg-gray-200">
                <div class="flex items-center text-gray-500 font-bold text-3xl">
                    <h1 class="ml-1">Transferências Pendentes</h1>
                </div>
                <svg
                    onclick="fechaModalTransferencias()"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 cursor-pointer">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </div>

            <div class="mt-4 p-4 form-container">
                <form action="#" method="GET" class="mt-1 flex w-full flex-col space-x-1 p-4 items-end border rounded bg-white">
                    <div class="flex items-center w-full">
                        <div class="flex flex-col w-full ml-2">
                            <label for="cliente" class="text-sm text-gray-700">Cliente</label>
                            <input onkeyup="buscaClientes(event)" type="text" id="cliente" name="cliente" placeholder="Busque pelo cliente..."
                                class="cliente px-1 py-1 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                            <div class="bg-white border rounded mt-1 hidden flex flex-col h-96 overflow-x-auto resultDestinatarios"> 

                            </div>
                            <input type="hidden" name="id_cliente" class="id_cliente" value="" id="id_cliente_transferencia">
                        </div>
                    </div>

                    <div class="mt-1 flex items-center space-x-1">
                        <button
                            id="buttonTransferencia"
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

            <div class="mt-6 p-4 overflow-y-auto h-96">
                <table class="min-w-full table-auto ml-auto bg-white font-normal rounded text-xs text-left text-gray-600 border-collapse border-2">
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
                    <tbody id="responseBody">

                    </tbody>
                </table>
            </div>
        
        </div>
    </div>
</div>
