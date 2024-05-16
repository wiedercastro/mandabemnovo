<div class="justify-center items-center hidden" id="modal_grupo_taxas">
    <div class="fixed inset-0 px-2 z-10 flex items-start justify-center animate__animated animate__fadeIn pb-16">
        <div class="absolute inset-0 bg-gray-800 bg-opacity-75 transition-opacity"></div>

        <!-- Modal Content -->
        <div
            class="sm:ml-56 ml-0 inline-block align-bottom bg-white
            rounded-lg text-left shadow-xl overflow-y-auto
            transform transition-all sm:my-8 sm:align-middle sm:w-1/2 w-full">
            <!-- Modal Header -->
            <div class="text-white px-4 py-4 flex justify-between bg-blue-600">
                <h2 class="ml-1 text-2xl font-bold">INCLUIR NOVO</h2>
                <svg
                    onclick="fechaIncluirNovoGrupoTaxa()"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 cursor-pointer">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </div>

            <div class="mt-2 p-4">
                <form action="#" method="POST" class="mt-8 flex flex-col w-full border p-2 rounded" id="submitAtualizaEndereco">
                    @csrf
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" id="_token">

                    <div class="flex items-center space-x-8">
                        <div class="flex flex-col w-full">
                            <label for="nome_grupo" class="text-sm text-gray-700">Nome</label>
                            <input required type="text" id="nome_grupo" name="nome_grupo" placeholder="Nome do grupo" class="px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                        </div>
    
                        <div class="flex flex-col w-full">
                            <div class="flex items-center">
                                <label for="aplicacao" class="text-sm text-gray-700">Aplicação</label>
                                <i class="fa fa-info-circle text-blue-600 ml-1"></i>
                            </div>
                            <select name="aplicacao" id="aplicacao" class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                <option value="default">Default</option>
                                <option value="pac_mini">Pac Mini</option>
                            </select>
                        </div>

                        <div class="flex flex-col w-full">
                            <label for="tipo_desconto" class="text-sm text-gray-700">Tipo de Desconto *</label>
                            <select name="tipo_desconto" id="tipo_desconto" class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                <option value="fixos">Valores Fixos</option>
                                <option value="percentual">Percentual</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center space-x-8">
                        <div class="flex flex-col w-full mt-4">
                            <label for="situacao" class="text-sm text-gray-700">Situação</label>
                            <select name="situacao" id="situacao" class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                <option value="habilitado">Habilitado</option>
                                <option value="desabilitado">Desabilitado</option>
                            </select>
                        </div>
    
                        <div class="flex flex-col w-full mt-4">
                            <label for="tabela" class="text-sm text-gray-700">Tabela *</label>
                            <select name="tabela" id="tabela" class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                <option value="varejo">Varejo</option>
                                <option value="industrial">Industrial</option>
                            </select>
                        </div>
    
                        <div class="flex flex-col w-full mt-4">
                            <label for="percentual" class="text-sm text-gray-700">Percentual (%)</label>
                            <input required type="text" id="percentual" name="percentual" placeholder="Percentual a ser aplicado" class="px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                        </div>
                    </div>

                    <div class="mt-6 overflow-y-auto h-96">
                        <h3 class="text-2xl text-gray-600 font-bold">Faixas</h3>
                        <hr class="bg-gray-500 border-2">
                        <table class="mt-2 w-96 divide-y divide-gray-200">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-sm font-medium">De</th>
                                <th scope="col" class="px-6 py-3 text-sm font-medium">Até</th>
                                <th scope="col" class="px-6 py-3 text-sm font-medium">Taxa (R$)</th>
                            </tr>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($faixas as $key => $faixa)
                                    <tr>
                                        <td class="px-2 py-4 whitespace-nowrap text-sm"><input disabled type="text" id="inp_min_<?= $key ?>" name="faixa_init[<?= $key ?>]" class="px-1 py-2 w-full rounded bg-gray-200 border-gray-200 text-sm text-gray-600" value="<?= $faixa['min'] ? $faixa['min'] : '--' ?>"/></td>
                                        <td class="px-2 py-4 whitespace-nowrap text-sm"><input disabled type="text" id="inp_max_<?= $key ?>" name="faixa_end[<?= $key ?>]" class="px-1 py-2 w-full bg-gray-200 rounded bg-white border-gray-200 text-sm text-gray-600" value="<?= $faixa['max'] ? $faixa['max'] : '--' ?>"/></td>
                                        <td class="px-2 py-4 whitespace-nowrap text-sm"><input type="text" id="taxa_<?= $key ?>" name="taxa[<?= $key ?>]" class="px-1 py-2 w-full rounded bg-white border-gray-200 text-sm text-gray-600 focus:border-gray-200" value="<?= $key ?>"/></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <hr class="mt-6">

                    <div class="mt-4 flex flex-row-reverse">
                        <button
                            onclick="fechaIncluirNovoGrupoTaxa()"
                            class="ml-1 bg-red-600 hover:bg-red-700 text-white font-bold px-2 py-1 rounded flex items-center text-sm">
                            <i class="fa fa-trash"></i>
                            <p class="ml-1">Cancelar</p>
                        </button>
                        <button
                            id="buttonFormCobranca"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-2 py-1 rounded flex items-center text-sm">
                            <i class="fa fa-save"></i>
                            <p class="ml-1">Salvar</p>
                        </button>
                    </div>
                </form>
            </div>
        
        </div>
    </div>
</div>
