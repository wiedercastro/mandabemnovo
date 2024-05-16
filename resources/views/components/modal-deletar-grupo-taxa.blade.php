<div class="justify-center items-center hidden" id="modal_deleta_grupo_taxa">
    <div class="fixed inset-0 px-2 z-10 flex items-center justify-center animate__animated animate__fadeIn pb-16">
        <div class="absolute inset-0 bg-gray-800 bg-opacity-75 transition-opacity"></div>

        <!-- Modal Content -->
        <div
            class="sm:ml-56 ml-0 inline-block align-bottom bg-white
            rounded-lg text-left shadow-xl overflow-y-auto
            transform transition-all sm:my-8 sm:align-middle sm:w-1/3">
            
            <div class="relative bg-white rounded-lg">
                <button onclick="cancelaDeletaGrupoTaxa()" type="button" class="absolute top-3 end-2.5 text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Fechar</span>
                </button>
                <div class="p-4 md:p-5 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 stroke-red-500 mx-auto mb-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>

                    <form action="#" method="POST">
                        @csrf
                        <input type="hidden" name="idGrupoTaxa" id="idGrupoTaxa">
                        <input type="hidden" name="csrfTokenGrupoTaxa" value="{{ csrf_token() }}" id="csrfTokenGrupoTaxa">
                    </form>
                    
                    <h3 class="mb-5 text-lg font-bold text-gray-500">Confirma remover este Registro?</h3>
                    <button id="buttonDeleteGrupoFaixa" onclick="deletaGrupoTaxa()" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                        Sim, tenho certeza
                    </button>
                    <button onclick="cancelaDeletaGrupoTaxa()" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
        <!-- Final Modal Content -->
    </div>
</div>

