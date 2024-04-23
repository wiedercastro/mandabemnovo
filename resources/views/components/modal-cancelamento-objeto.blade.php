<div class="justify-center items-center hidden" id="modal-cancelamento">
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
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-9 h-9">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>                  
                    <h2 class="text-2xl font-bold">
                        Cancelamento Objeto Já postado
                    </h2>
                </div>
                <svg
                    onclick="fechaModalCancelamentoObjeto()"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 cursor-pointer">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </div>

            <div class="p-4">
                <div class="flex items-center p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-100" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="flex-shrink-0 inline w-8 h-8 me-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>                
                    <span class="sr-only">Info</span>
                    <div>
                        <span class="font-medium">ATENÇÃO!</span> Ao cancelar esse envio o objeto irá retornar para você e o valor do envio não será ressarcido pelos correios.
                    </div>
                </div>
            </div>

            <div class="p-4">
                <div class="flex justify-between w-96 mt-4">
                    <div class="text-gray-600">
                        <p>Envio:</p>
                    </div>
                    <div class="text-gray-600">
                        <p id="destinatario_cancelamento"></p>
                        <p class="font-bold" id="etiqueta_correios_cancelamento"></p>
                    </div>
                </div>
                 <hr class="mt-4 border border-dashed border-gray-300">
                <div class="flex justify-between w-[17rem] mt-4">
                    <div class="text-gray-600" >
                        <p>CEP:</p>
                    </div>
                    <div class="text-gray-600">
                        <p class="font-bold text-gray-700" id="cep_cancelamento"></p>
                    </div>
                </div>
                <hr class="mt-4 border border-dashed border-gray-300">
            </div>
            
            <div class="p-4">
                <div class="mt-4">
                    <p class="text-sm text-gray-600">Deseja realmente cancelar o envio?</p>
                    <div class="flex items-center mt-2">            
                        <form action="#" method="POST" id="submitFormCancelamentoObjeto">
                            @csrf
                            <input type="hidden" name="csrfToken" value="{{ csrf_token() }}" id="csrfToken">@csrf
                            <input type="hidden" name="idEtiquetaCancelamento" value="{{ csrf_token() }}" id="idEtiquetaCancelamento">

                            <div class="flex items-center">
                                <button
                                    type="submit"
                                    id="buttonCancelamentoObjeto"
                                    class="text-sm bg-blue-600 hover:bg-blue-700 text-white font-bold px-2 py-1 rounded flex items-center"> 
                                    <i class="fa fa-trash"></i>                   
                                    <p class="ml-1">Sim</p>
                                </button>

                                <button type="button" onclick="fechaModalCancelamentoObjeto()"
                                    class="text-sm bg-red-500 hover:bg-red-600 text-white font-bold px-2 py-1 ml-1 rounded flex items-center">
                                    <i class="fa fa-times"></i>
                                    <p>Não</p>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
