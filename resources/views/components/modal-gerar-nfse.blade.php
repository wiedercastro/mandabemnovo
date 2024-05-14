<div class="justify-center items-center hidden" id="modal_gerar_nfse">
    <div class="fixed inset-0 px-2 z-10 flex items-start justify-center animate__animated animate__fadeIn pb-16">
        <div class="absolute inset-0 bg-gray-800 bg-opacity-75 transition-opacity"></div>

        <!-- Modal Content -->
        <div
            class="sm:ml-56 ml-0 inline-block align-bottom bg-white
            rounded-lg text-left shadow-xl overflow-y-auto
            transform transition-all sm:my-8 sm:align-middle sm:w-1/4 w-full">
            <!-- Modal Header -->
            <div class="text-white px-4 py-4 flex justify-between bg-green-600">
                <div class="flex items-center text-2xl font-bold">
                    <i class="fa fa-cog"></i> 
                    <h2 class="ml-1">Gerar NF</h2>
                </div>
                <svg
                    onclick="fechaModalGerarNFSe()"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 cursor-pointer">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </div>

            <div class="mt-2 p-4">
                <form action="#" method="POST" class="mt-8 flex flex-col w-full" id="submitAtualizaEndereco">
                    @csrf
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" id="_token">

                    <div class="flex flex-col w-full">
                        <label for="descriminacao" class="text-sm text-gray-700">Cliente</label>
                        <input required type="text" id="descriminacao" name="descriminacao" class="px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                    </div>

                    <div class="flex flex-col w-full mt-4">
                        <label for="descricao" class="text-sm text-gray-700">Descriminação</label>
                        <textarea 
                            required 
                            id="descricao" 
                            name="descricao" 
                            rows="2" 
                            class="resize-none outline-none block p-2.5 w-full text-sm text-gray-900 shadow-sm rounded-lg border border-gray-300 shadow bg-white" 
                            placeholder="Escreva uma observação..."></textarea>
                    </div>

                    <div class="flex flex-col w-full mt-4">
                        <label for="valor_nf" class="text-sm text-gray-700">Valor NF *</label>
                        <input required type="text" id="valor_nf" name="valor_nf" placeholder="Valor NF" class="px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                    </div>

                    <div class="flex flex-col w-full mt-4">
                        <label for="iss" class="text-sm text-gray-700">ISS (%)</label>
                        <input required type="text" id="iss" name="iss" placeholder="Valor NF" class="px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                    </div>
                  
                    <div class="flex items-center mt-4">
                        <input 
                            type="checkbox" 
                            id="iss_retido"
                            name="iss_retido"
                            class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500 focus:ring-2"
                        >
                        <p class="block text-gray-700 text-sm ml-1">ISS Retido?</p>
                    </div>

                    <hr class="mt-6">

                    <div class="mt-4 flex items-center space-x-1">
                        <button
                            id="buttonFormCobranca"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-2 py-1 rounded flex items-center text-sm">
                            <i class="fa fa-cogs"></i>
                            <p class="ml-1">Gerar</p>
                        </button>
                        <button
                            onclick="fechaModalGerarNFSe()"
                            class="bg-red-600 hover:bg-red-700 text-white font-bold px-2 py-1 rounded flex items-center text-sm">
                            <i class="fa fa-trash"></i>
                            <p class="ml-1">Cancelar geração</p>
                        </button>
                    </div>
                </form>
            </div>
        
        </div>
    </div>
</div>
