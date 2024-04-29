<div class="justify-center items-center hidden" id="modal_edit_banco_user">
    <div class="fixed inset-0 px-2 z-10 flex items-start justify-center animate__animated animate__fadeIn">
        <div class="absolute inset-0 bg-gray-800 bg-opacity-75 transition-opacity"></div>
        <!-- Modal Content -->
        <div
            class="sm:ml-56 ml-0 inline-block align-bottom bg-white
            rounded-lg text-left shadow-xl
            transform transition-all sm:my-8 sm:align-middle sm:w-2/5">
            <!-- Modal Header -->
            <div class="text-gray-600 px-4 py-4 flex justify-between bg-gray-200">
                <div class="flex items-center text-gray-500 font-bold text-3xl">
                    <i class="fa fa-edit"></i>
                    <h1 class="ml-1">Editar Banco Transferência</h1>
                </div>
                <svg
                    onclick="fechaModalEditBancoCliente()"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 cursor-pointer">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </div>

            <div class="mt-4 p-4">
                <form action="#" method="GET" class="mt-1 flex w-full flex-col space-x-1 p-4 items-end border rounded bg-white">
                    <div class="flex items-center w-full">
                        <div class="flex flex-col w-full ml-2">
                            <label for="banco" class="text-sm text-gray-700">Banco</label>
                            <select name="banco" id="banco" class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                <option value="itau">Banco Itaú</option>
                                <option value="neon">Banco Neon</option>
                                <option value="mercado_pago">Mercado Pago</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center space-x-1">
                        <button
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-2 py-1 rounded flex items-center text-sm">
                            <i class="fa fa-save"></i>
                            <p class="ml-1">Salvar</p>
                        </button>
                        <button
                            onclick="fechaModalEditBancoCliente()"
                            class="bg-red-600 hover:bg-red-700 text-white font-bold px-2 py-1 rounded flex items-center text-sm">
                            <i class="fa fa-times"></i>
                            <p class="ml-1">Fechar</p>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
