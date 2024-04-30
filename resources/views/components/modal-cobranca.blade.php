<div class="justify-center items-center hidden" id="modal_cobranca">
    <div class="fixed inset-0 px-2 z-10 flex items-start justify-center animate__animated animate__fadeIn pb-16">
        <div class="absolute inset-0 bg-gray-800 bg-opacity-75 transition-opacity"></div>

        <!-- Modal Content -->
        <div
            class="sm:ml-56 ml-0 inline-block align-bottom bg-white
            rounded-lg text-left shadow-xl overflow-y-auto
            transform transition-all sm:my-8 sm:align-middle sm:w-2/5 w-full">
            <!-- Modal Header -->
            <div class="text-gray-600 px-4 py-4 flex justify-between bg-gray-200">
                <div class="flex items-center">
                    <h2 class="text-2xl font-bold">
                        Dados da Cobrança
                    </h2>
                </div>
                <svg
                    onclick="fechaModalCobranca()"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 cursor-pointer">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </div>

            <div class="mt-2 p-4">
                <form action="#" method="POST" class="mt-8 flex flex-col w-full">
        
                    <div class="flex flex-col w-full">
                        <label for="cliente" class="text-sm text-gray-700">Cliente</label>
                        <input onkeyup="buscaPorDestinatario(event)" type="text" id="destinatario_cliente" name="destinatario_cliente" placeholder="Digite o nome do cliente..." class="px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                        <div class="bg-white border rounded mt-1 hidden flex flex-col h-96 overflow-x-auto resultDestinatarios"> 

                        </div>
                    </div>

                    <div class="flex flex-col w-full mt-4">
                        <label for="valor" class="text-sm text-gray-700">Valor *</label>
                        <input type="text" id="valor" name="valor" placeholder="Digite o valor..." class="px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                    </div>

                    <div class="flex flex-col w-full mt-4">
                        <label for="forma_cobranca" class="text-sm text-gray-700">Forma de cobrança *</label>
                        <select id="forma_cobranca" name="forma_cobranca"
                            class="px-1 py-1 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                            <option @if (request('forma_cobranca') == 'paypal') selected @endif value="paypal">PayPal</option>
                            <option @if (request('forma_cobranca') == 'desconto_creditos') selected @endif value="transferencia">Desconto em créditos</option>
                        </select>
                    </div>

                    <div class="flex flex-col w-full mt-4">
                        <label for="descricao" class="text-sm text-gray-700">Descrição</label>
                        <textarea 
                            required 
                            id="descricao" 
                            name="descricao" 
                            rows="6" 
                            class="resize-none outline-none block p-2.5 w-full text-sm text-gray-900 shadow-sm rounded-lg border border-gray-300 shadow bg-white" 
                            placeholder="Escreva uma observação..."></textarea>
                    </div>

                    <div class="flex flex-col w-full mt-4">
                        <label for="observacao" class="text-sm text-gray-700">Observação</label>
                        <textarea 
                            required 
                            id="observacao" 
                            name="observacao" 
                            rows="6" 
                            class="resize-none outline-none block p-2.5 w-full text-sm text-gray-900 shadow-sm rounded-lg border border-gray-300 shadow bg-white" 
                            placeholder="Escreva uma observação..."></textarea>
                    </div>

                    <hr class="mt-6">

                    <div class="mt-4 flex items-center space-x-1">
                        <button
                            class="bg-green-600 hover:bg-green-700 text-white font-bold px-2 py-1 rounded flex items-center text-sm">
                            <i class="fa fa-credit-card"></i>
                            <p class="ml-1">Realizar cobrança</p>
                        </button>
                        <button
                            onclick="fechaModalCobranca()"
                            class="bg-red-600 hover:bg-red-700 text-white font-bold px-2 py-1 rounded flex items-center text-sm">
                            <i class="fa fa-trash"></i>
                            <p class="ml-1">Cancelar</p>
                        </button>
                    </div>
                </form>
            </div>
        
        </div>
    </div>
</div>
