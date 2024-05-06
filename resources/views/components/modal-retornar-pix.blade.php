<div class="justify-center items-center hidden" id="modal_retorna_pix">
    <div class="fixed inset-0 px-2 z-10 flex items-start justify-center animate__animated animate__fadeIn pb-16">
        <div class="absolute inset-0 bg-gray-800 bg-opacity-75 transition-opacity"></div>

        <!-- Modal Content -->
        <div
            class="sm:ml-56 ml-0 inline-block align-bottom bg-white
            rounded-lg text-left shadow-xl overflow-y-auto
            transform transition-all sm:my-8 sm:align-middle sm:w-1/4 w-full">
            <!-- Modal Header -->
            <div class="text-white font-bold px-4 py-4 flex justify-between bg-blue-500">
                <div class="flex flex-col">
                    <h2 class="text-2xl">
                        Retornar PIX
                    </h2>
                    <p class="text-xs text-white">*Dos últimos 7 dias</p>
                </div>
                <svg
                    onclick="fechaModalRetornarPix()"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 cursor-pointer">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </div>

            <div class="p-4 form-container">
                <form action="#" method="POST" class="mt-8 flex flex-col w-full border p-4 rounded" id="submitRetornarPix">
                    @csrf
                    <input type="hidden" name="token" value="{{ csrf_token() }}" id="token">
                    <input type="hidden" value="" id="cliente_id_retornar_pix" class="id_cliente">

                    <div class="flex flex-col">
                        <div class="flex flex-col w-full mt-4">
                            <label for="cliente_retornar_pix" class="text-sm text-gray-700">Cliente</label>
                            <input onkeyup="buscaClientes(event)" type="text" id="cliente_retornar_pix" name="cliente_retornar_pix" placeholder="Busque por um cliente..." class="cliente px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                            <div class="bg-white border rounded mt-1 hidden flex flex-col h-96 overflow-x-auto resultDestinatarios"></div>
                        </div>
                        <div>
                            <button
                                id="buscaRetornarPix"
                                class="mt-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-2 py-1 rounded flex items-center text-sm">
                                <i class="fa fa-search"></i>
                                <p class="ml-1">Buscar</p>
                            </button>
                        </div>
                    </div>

                    <hr class="mt-6">

                    <p class="font-bold text-gray-700 text-xs mt-6 hidden" id="msgRetornarPix">* Usuário não possue PIX nos últimos 7 dias para retornar.</p>

                    <hr class="mt-6">

                    <div class="mt-4 flex flex-row-reverse">
                        <button
                            onclick="fechaModalRetornarPix()"
                            class="bg-red-600 hover:bg-red-700 text-white font-bold px-2 py-1 rounded flex items-center text-sm">
                            <i class="fa fa-trash"></i>
                            <p class="ml-1">Fechar</p>
                        </button>
                    </div>
                </form>
            </div>
        
        </div>
    </div>
</div>