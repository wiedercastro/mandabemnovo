<x-app-layout>
    <div class="w-5/6 ml-auto lg:px-12">
        <div class="w-full pb-6">

            <x-card-pagamentos_admin/>
            <x-menu-navigation-pagamentos/>
            
            <div class="bg-white mt-8 w-3/5 p-4 rounded shadow">
                <h1 class="text-gray-500 font-bold text-3xl text">Dados da Cobrança</h1>

                <form action="{{ route('estatisticas_admin_index') }}" method="POST" class="mt-8 flex flex-col w-full">
        
                    <div class="flex flex-col w-full">
                        <label for="cliente" class="text-sm text-gray-700">Cliente</label>
                        <input type="text" id="cliente" name="cliente" placeholder="Digite o nome do cliente..." class="px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
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
                            class="bg-red-600 hover:bg-red-700 text-white font-bold px-2 py-1 rounded flex items-center text-sm">
                            <i class="fa fa-trash"></i>
                            <p class="ml-1">Cancelar</p>
                        </button>
                    </div>
                </form>
            </div>
 
        </div>
    </div>
</x-app-layout>
