<div class="justify-center items-center hidden" id="modal_insere_faq">
    <div class="fixed inset-0 px-2 z-10 flex items-start justify-center animate__animated animate__fadeIn pb-16">
        <div class="absolute inset-0 bg-gray-800 bg-opacity-75 transition-opacity"></div>

        <!-- Modal Content -->
        <div
            class="sm:ml-56 ml-0 inline-block align-bottom bg-white
            rounded-lg text-left shadow-xl overflow-y-auto
            transform transition-all sm:my-8 sm:align-middle sm:w-1/2">
            <!-- Modal Header -->
            <div class="text-white px-4 py-4 flex flex-row-reverse">
                <svg onclick="fechaModalFaq()" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                    class="w-8 h-8 stroke-gray-600 cursor-pointer">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </div>
            
            <form action="#" method="POST" id="submitFormFaq">
                @csrf
                <input type="hidden" name="csrfToken" value="{{ csrf_token() }}" id="csrfToken">
    
    
                <div class="p-8 flex flex-col">
                    {{--  DADOS GERAIS --}}
                    <div class="w-full sm:space-y-4 space-y-0">
                        <h1 class="text-gray-500 font-bold text-4xl text-2xl">Dados Gerais</h1>
    
                        <div class="mt-8">
                            <label for="tipo_usuario" class="block text-gray-500 text-sm font-bold">Tipo de Usuário</label>
                            <select name="tipo_usuario" id="tipo_usuario" class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                <option value="cliente_com_contrato">Cliente com Contrato</option>
                                <option value="cliente_sem_contrato">Cliente sem Contrato</option>
                                <option value="cliente_franquia">Cliente franquia</option>
                                <option value="auditor">Auditor</option>
                                <option value="agencias">Agências</option>
                            </select>
                        </div>
    
                        <div class="mt-2">
                            <label for="status" class="block text-gray-500 text-sm font-bold">Status</label>
                            <select name="status" id="status" class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                <option value="ativo">ATIVO</option>
                                <option value="bloqueado">BLOQUEADO</option>
                            </select>
                        </div>

                        <div class="mt-2">
                            <label for="teste" class="block text-gray-500 text-sm font-bold">Estado</label>
                            <input type="text" name="teste" id="teste"
                                class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                        </div>
                    </div>

                </div>
    
                <div class="flex flex-row-reverse mt-8 p-2">
                    <button type="button" onclick="fechaModalFaq()"
                        class="text-sm bg-red-500 hover:bg-red-600 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                        <p>Fechar</p>
                    </button>
    
                    <button
                        id="buttonCreateFaq"
                        onclick="submitNewFaq()"
                        class="text-sm bg-green-600 hover:bg-green-700 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>                      
                        <p class="ml-1">Salvar</p>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
