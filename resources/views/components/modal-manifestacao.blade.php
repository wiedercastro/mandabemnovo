<div class="justify-center items-center hidden" id="modal-manifestacao">
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
                        <path fill-rule="evenodd" d="M7.502 6h7.128A3.375 3.375 0 0 1 18 9.375v9.375a3 3 0 0 0 3-3V6.108c0-1.505-1.125-2.811-2.664-2.94a48.972 48.972 0 0 0-.673-.05A3 3 0 0 0 15 1.5h-1.5a3 3 0 0 0-2.663 1.618c-.225.015-.45.032-.673.05C8.662 3.295 7.554 4.542 7.502 6ZM13.5 3A1.5 1.5 0 0 0 12 4.5h4.5A1.5 1.5 0 0 0 15 3h-1.5Z" clip-rule="evenodd" />
                        <path fill-rule="evenodd" d="M3 9.375C3 8.339 3.84 7.5 4.875 7.5h9.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 0 1 3 20.625V9.375ZM6 12a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H6.75a.75.75 0 0 1-.75-.75V12Zm2.25 0a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75ZM6 15a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H6.75a.75.75 0 0 1-.75-.75V15Zm2.25 0a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75ZM6 18a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H6.75a.75.75 0 0 1-.75-.75V18Zm2.25 0a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                    </svg>                   
                    <h2 class="text-2xl font-bold">
                        Manifestação de Objeto
                    </h2>
                </div>
                <svg
                    onclick="fechaModalManifestacaoObjeto()"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 cursor-pointer">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </div>

            <div class="mt-2 p-4">
                <div class="flex justify-between w-96 mt-4">
                    <div class="text-gray-600">
                        <p>Envio:</p>
                    </div>
                    <div class="text-gray-600">
                        <p id="destinatario_manifestacao"></p>
                        <p class="font-bold" id="etiqueta_correios_manifestacao"></p>
                    </div>
                </div>
                 <hr class="mt-4 border border-dashed border-gray-300">
                <div class="flex justify-between w-96 mt-4">
                    <div class="text-gray-600" >
                        <p>CEP:</p>
                    </div>
                    <div class="text-gray-600">
                        <p class="font-bold text-gray-700" id="cep"></p>
                    </div>
                </div>
            </div>
            
            <div class="p-4">
                <form action="#" method="POST" class="border p-2 rounded mt-2">
                    <h4 class="text-sm text-gray-600">Por favor, informe o Motivo de abertura da Manifestação:</h4>
                    @csrf
                    <input type="hidden" name="csrfToken" value="{{ csrf_token() }}" id="csrfToken">
        
                    <div class="mt-4">
                        <label for="categoria" class="block text-gray-500 text-sm font-bold">Motivo</label>
                        <select required name="categoria" id="categoria" class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                            <option value="" disabled selected >Selecione</option>
                            <option value="132">Remessa/Objeto Postal entregue</option>
                            <option value="133">Remessa/Objeto Postal violada</option>
                            <option value="134">Remessa/Objeto Postal avariada/danificada</option>
                            <option value="135">Remessa/Objeto Postal entregue com atraso</option>
                            <option value="136">Remessa/Objeto Postal devolvida indevidamente</option>
                            <option value="141">Não recebimento do pedido de confirmação</option>
                            <option value="142">Remetente não recebeu o pedido de cópia</option>
                            <option value="148">Remetente não recebeu o AR</option>
                            <option value="211">Remessa/Objeto Postal não entregue</option>
                            <option value="240">AR Digital - Imagem não disponível</option>
                            <option value="1414">Remessa/Objeto Postal sem tentativa de entrega domiciliar</option>
                        </select>
                    </div>
        
                    <div class="mt-4">
                        <p class="text-sm text-gray-600">Deseja realmente abrir a Manifestação para o envio?</p>
                        <div class="flex items-center mt-2">            
                            <button
                                type="submit"
                                id="buttonCreateFaq"
                                class="text-sm bg-blue-600 hover:bg-blue-700 text-white font-bold px-2 py-1 rounded flex items-center"> 
                                <i class="fa fa-check"></i>                   
                                <p class="ml-1">Sim</p>
                            </button>

                            <button type="button" onclick="fechaModalManifestacaoObjeto()"
                                class="text-sm bg-red-500 hover:bg-red-600 text-white font-bold px-2 py-1 ml-1 rounded flex items-center">
                                <i class="fa fa-times"></i>
                                <p>Não</p>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
