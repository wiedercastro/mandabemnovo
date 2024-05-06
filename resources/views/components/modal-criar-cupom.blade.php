<div class="justify-center items-center hidden" id="modal_criar_cupom">
    <div class="fixed inset-0 px-2 z-10 flex items-start justify-center animate__animated animate__fadeIn pb-16">
        <div class="absolute inset-0 bg-gray-800 bg-opacity-75 transition-opacity"></div>

        <!-- Modal Content -->
        <div
            class="sm:ml-56 ml-0 inline-block align-bottom bg-white
            rounded-lg text-left shadow-xl overflow-y-auto
            transform transition-all sm:my-8 sm:align-middle sm:w-1/4 w-full">
            <!-- Modal Header -->
            <div class="text-white font-bold px-4 py-4 flex justify-between bg-blue-500">
                <div class="flex items-center">
                    <h2 class="text-2xl font-bold">
                        Novo Cupom
                    </h2>
                </div>
                <svg
                    onclick="fecharModalCriarCupom()"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 cursor-pointer">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </div>

            <div class="p-4">
                <form action="#" method="POST" class="mt-8 flex flex-col w-full border p-4 rounded" id="submitFormCredito">
                    @csrf
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" id="_token">

                    <div class="flex flex-col w-full mt-4">
                        <label for="tipo_cupom" class="text-sm text-gray-700">Tipo de Cupom</label>
                        <select required id="tipo_cupom" name="tipo_cupom"
                            class="px-1 py-1 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                            <option value="credito">CRÉDITO R$</option>
                            <option value="desconto">DESCONTO %</option>
                        </select>
                    </div>

                    <div class="flex flex-col w-full mt-4">
                        <label for="nome_ativacao" class="text-sm text-gray-700">Nome de Ativação</label>
                        <input required type="text" id="nome_ativacao" name="nome_ativacao" placeholder="Nome de Ativação" class="px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                    </div>

                    <div class="flex flex-col w-full mt-4">
                        <label for="valor" class="text-sm text-gray-700">Valor</label>
                        <input required type="text" id="valor" name="valor" placeholder="Nome de Ativação" class="px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                    </div>

                    <div class="flex flex-col w-full mt-4">
                        <label for="tempo_duracao_dias" class="text-sm text-gray-700">Tempo de Duração em Dias</label>
                        <input required type="text" id="tempo_duracao_dias" name="tempo_duracao_dias" placeholder="Tempo de Duração em Dias" class="px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                    </div>

                    <div class="flex flex-col w-full mt-4">
                        <label for="qtd_envios" class="text-sm text-gray-700">Quantidade de Envios</label>
                        <input required type="text" id="qtd_envios" name="qtd_envios" placeholder="Quantidade de Envios" class="px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                    </div>

                    <div class="flex flex-col w-full mt-4">
                        <label for="vincular_afiliado" class="text-sm text-gray-700">Vincular um Afiliado</label>
                        <select required id="vincular_afiliado" name="vincular_afiliado"
                            class="px-1 py-1 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                            @foreach ($afiliados as $afiliado)
                                <option value="{{$afiliado->id}}">{{$afiliado->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <hr class="mt-6">

                    <div class="mt-4 flex items-center space-x-1">
                        <button
                            id="buttonFormCredito"
                            class="bg-green-600 hover:bg-green-700 text-white font-bold px-2 py-1 rounded flex items-center text-sm">
                            <i class="fa fa-plus"></i>
                            <p class="ml-1">Criar cupom</p>
                        </button>
                        <button
                            onclick="fecharModalCriarCupom()"
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
