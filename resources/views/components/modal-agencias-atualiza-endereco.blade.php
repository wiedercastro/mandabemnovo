<div class="justify-center items-center hidden" id="modal_atualiza_endereco_agencia">
    <div class="fixed inset-0 px-2 z-10 flex items-start justify-center animate__animated animate__fadeIn pb-16">
        <div class="absolute inset-0 bg-gray-800 bg-opacity-75 transition-opacity"></div>

        <!-- Modal Content -->
        <div
            class="sm:ml-56 ml-0 inline-block align-bottom bg-white
            rounded-lg text-left shadow-xl overflow-y-auto
            transform transition-all sm:my-8 sm:align-middle sm:w-1/4 w-full">
            <!-- Modal Header -->
            <div class="text-white px-4 py-4 flex justify-between bg-blue-600">
                <div class="flex items-center text-2xl font-bold">
                    <i class="fa fa-address-book"></i>
                    <h2 class="ml-1">Endereço agencia</h2>
                </div>
                <svg
                    onclick="fechaModalAtualizaEndereco()"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 cursor-pointer">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </div>

            <div class="mt-2 p-4">
                <form action="#" method="POST" class="mt-8 flex flex-col w-full" id="submitAtualizaEndereco">
                    @csrf
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" id="_token">

                    <div class="flex flex-col w-full mt-4">
                        <label for="cep_agencia" class="text-sm text-gray-700">CEP *</label>
                        <input required type="text" id="cep_agencia" name="cep_agencia" class="px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                    </div>

                    <div class="flex flex-col w-full mt-4">
                        <label for="logradouro_agencia" class="text-sm text-gray-700">Logradouro *</label>
                        <input required type="text" id="logradouro_agencia" name="logradouro_agencia" class="px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                    </div>

                    <div class="flex flex-col w-full mt-4">
                        <label for="numero_agencia" class="text-sm text-gray-700">Número *</label>
                        <input required type="text" id="numero_agencia" name="numero_agencia" class="px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                    </div>

                    <div class="flex flex-col w-full mt-4">
                        <label for="complemento_agencia" class="text-sm text-gray-700">Complemento *</label>
                        <input required type="text" id="complemento_agencia" name="complemento_agencia" class="px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                    </div>

                    <div class="flex flex-col w-full mt-4">
                        <label for="bairro_agencia" class="text-sm text-gray-700">Bairro *</label>
                        <input required type="text" id="bairro_agencia" name="bairro_agencia" class="px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                    </div>

                    <div class="flex flex-col w-full mt-4">
                        <label for="descricao" class="text-sm text-gray-700"></label>
                        <textarea 
                            required 
                            id="descricao" 
                            name="descricao" 
                            rows="6" 
                            class="resize-none outline-none block p-2.5 w-full text-sm text-gray-900 shadow-sm rounded-lg border border-gray-300 shadow bg-white" 
                            placeholder="Escreva uma observação..."></textarea>
                    </div>
                    <hr class="mt-6">

                    <div class="mt-4 flex items-center space-x-1">
                        <button
                            id="buttonFormCobranca"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-2 py-1 rounded flex items-center text-sm">
                            <i class="fa fa-save"></i>
                            <p class="ml-1">Salvar</p>
                        </button>
                        <button
                            onclick="fechaModalAtualizaEndereco()"
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
