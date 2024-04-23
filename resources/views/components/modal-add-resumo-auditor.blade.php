<div class="justify-center items-center hidden" id="modal_add_resumo_auditor">
    <div class="fixed inset-0 px-2 z-10 flex items-start justify-center animate__animated animate__fadeIn pb-16">
        <div class="absolute inset-0 bg-gray-800 bg-opacity-75 transition-opacity"></div>

        <!-- Modal Content -->
        <div
            class="sm:ml-56 ml-0 inline-block align-bottom bg-white
            rounded-lg text-left shadow-xl overflow-y-auto
            transform transition-all sm:my-8 sm:align-middle sm:w-2/5">
            <!-- Modal Header -->
            <div class="text-gray-600 px-4 py-4 flex justify-between bg-gray-200">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-9 h-9">
                        <path fill-rule="evenodd" d="M4.804 21.644A6.707 6.707 0 0 0 6 21.75a6.721 6.721 0 0 0 3.583-1.029c.774.182 1.584.279 2.417.279 5.322 0 9.75-3.97 9.75-9 0-5.03-4.428-9-9.75-9s-9.75 3.97-9.75 9c0 2.409 1.025 4.587 2.674 6.192.232.226.277.428.254.543a3.73 3.73 0 0 1-.814 1.686.75.75 0 0 0 .44 1.223ZM8.25 10.875a1.125 1.125 0 1 0 0 2.25 1.125 1.125 0 0 0 0-2.25ZM10.875 12a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Zm4.875-1.125a1.125 1.125 0 1 0 0 2.25 1.125 1.125 0 0 0 0-2.25Z" clip-rule="evenodd" />
                    </svg> 
                    <h2 class="text-2xl font-bold">
                        Adicionar Resumo
                    </h2>
                </div>
                <svg
                    onclick="fechaModalAutidor()"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 cursor-pointer">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </div>

            <div class="mt-2 p-4">
                <div class="flex justify-between w-96 mt-4">
                    <div class="text-gray-600">
                        <p>Objeto:</p>
                    </div>
                    <div>
                        <p class="font-bold text-gray-700" id="etiqueta_correios">AA049836142BR</p>
                    </div>
                </div>
                 <hr class="mt-4 border border-dashed border-gray-300">
                <div class="flex justify-between w-96 mt-4">
                    <div class="text-gray-600">
                        <p>Destinatário:</p>
                    </div>
                    <div class="text-gray-600">
                        <p class="font-bold text-gray-700" id="destinatario">Bruno Lucas Candido</p>
                    </div>
                </div>
                 <hr class="mt-4 border border-dashed border-gray-300">
                <div class="flex justify-between w-96 mt-4">
                    <div class="text-gray-600">
                        <p>Cidade:</p>
                    </div>
                    <div>
                        <p class="font-bold text-gray-700" id="cidade"></p>
                    </div>
                </div>
                <hr class="mt-4 border border-dashed border-gray-300">
            </div>
            
            <form action="#" method="POST" id="submitFormAuditor">
                @csrf
                <input type="hidden" name="csrfToken" value="{{ csrf_token() }}" id="csrfToken">
                <input type="hidden" name="idEtiquetaAuditor" value="" id="idEtiquetaAuditor">
    
                <div class="mt-2 p-4">
                    <label for="resposta" class="block text-gray-500 text-sm font-bold">Resumo</label>
                    <textarea 
                        required 
                        id="resumo" 
                        name="resumo" 
                        rows="6" 
                        class="resize-none outline-none block p-2.5 w-full text-sm text-gray-900 shadow-sm rounded-lg border border-gray-300 shadow bg-white" 
                        placeholder="Resumo..."></textarea>
                    
                    <button type="button"
                        class="text-sm bg-green-500 hover:bg-green-600 text-white font-bold px-2 py-1 rounded mt-2 flex items-center">
                        <i class="fa fa-upload"></i>
                        <p class="ml-1">Anexar</p>
                    </button>
                </div>
    
                <div class="flex flex-row-reverse mt-8 p-2">
                    <button type="button" onclick="fechaModalAutidor()"
                        class="text-sm bg-red-500 hover:bg-red-600 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                        <p>Fechar</p>
                    </button>
    
                    <button
                        type="submit"
                        id="buttonSendAuditor"
                        class="text-sm bg-blue-600 hover:bg-blue-700 text-white font-bold px-2 py-1 rounded ml-2 flex items-center"> 
                        <i class="fa fa-paper-plane"></i>                   
                        <p class="ml-1">Enviar</p>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
