<div class="justify-center items-center hidden" id="modal_insere_faq">
    <div class="fixed inset-0 px-2 z-10 flex items-start justify-center animate__animated animate__fadeIn pb-16">
        <div class="absolute inset-0 bg-gray-800 bg-opacity-75 transition-opacity"></div>

        <!-- Modal Content -->
        <div
            class="sm:ml-56 ml-0 inline-block align-bottom bg-white
            rounded-lg text-left shadow-xl overflow-y-auto
            transform transition-all sm:my-8 sm:align-middle sm:w-1/2">
            <!-- Modal Header -->
            <div class="text-gray-600 px-4 py-4 flex justify-between bg-gray-200">
                <h2 class="text-2xl font-bold">
                    Inserir Nova Questão
                </h2>
                <svg
                    onclick="fechaModalFaq()"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 cursor-pointer">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </div>
            
            <form action="#" method="POST" id="submitFormFaq">
                @csrf
                <input type="hidden" name="csrfToken" value="{{ csrf_token() }}" id="csrfToken">
                <input type="hidden" id="idFaqEdit" name="idFaqEdit" value="" id="idFaqEdit">
    
                <div class="p-8 flex flex-col">
                    <div class="w-full sm:space-y-4 space-y-0">
                        <div class="mt-4">
                            <label for="categoria" class="block text-gray-500 text-sm font-bold">Categoria</label>
                            <select required name="categoria" id="categoria" class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                @foreach ($categories as $categorie)
                                    <option value="{{$categorie->id}}">{{$categorie->name}}</option>
                                @endforeach
                            </select>
                        </div>
    
                        <div class="mt-2">
                            <label for="pergunta" class="block text-gray-500 text-sm font-bold">Pergunta</label>
                            <textarea 
                                required 
                                id="pergunta" 
                                name="pergunta" 
                                rows="4" 
                                class="resize-none outline-none block p-2.5 w-full text-sm text-gray-900 shadow-sm rounded-lg border border-gray-300 shadow bg-white" 
                                placeholder="Digite a pergunta..."></textarea>
                        </div>

                        <div class="mt-2">
                            <label for="resposta" class="block text-gray-500 text-sm font-bold">Resposta</label>
                            <textarea 
                                required 
                                id="resposta" 
                                name="resposta" 
                                rows="10" 
                                class="resize-none outline-none block p-2.5 w-full text-sm text-gray-900 shadow-sm rounded-lg border border-gray-300 shadow bg-white" 
                                placeholder="Resposta..."></textarea>
                        </div>

                        <div class="flex flex-col">
                            <div class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    id="visivel_mandabem"
                                    name="visivel_mandabem"
                                    class="text-blue-500 border-gray-300 rounded w-3 h-3 shadow-sm"
                                >
                                <p class="block text-gray-500 text-sm font-bold ml-1">Visível Manda Bem</p>
                            </div>

                            <div class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    id="visivel_ecommerce"
                                    name="visivel_ecommerce"
                                    class="text-blue-500 border-gray-300 rounded w-3 h-3 shadow-sm"
                                >
                                <p class="block text-gray-500 text-sm font-bold ml-1">Visível Ecommerce</p>
                            </div>
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
                        type="submit"
                        id="buttonCreateFaq"
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
