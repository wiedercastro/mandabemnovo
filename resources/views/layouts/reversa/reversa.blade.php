<x-app-layout>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


    <div class="w-5/6 ml-auto lg:px-12" style="border: 0px solid red;">
        <div class="text-4xl" style="margin-top:-25px">
            <h1 style="color:#728189"><b>Reversa</b></h1>
        </div>
        <br>
        <div class=" w-full dark:bg-gray-800" style="border: 0px solid black;">
            <div class="w-full text-gray-900 dark:text-gray-100">

                <div class="flex w-full m-auto h-20 bg-white shadow-xl" style="border: 0px solid red">
                    <div class="w-11/12 flex ml-3.5" style="border:0px solid red">
                        <div class="w-80 mb-4">
                            <label for="category_id" class="block text-gray-700 text-sm font-bold mb-2">Sede que irá
                                Receber</label>
                            <select name="category_id" id="category_id" class="w-full border p-2 rounded">
                                {{-- @foreach ($categories as $category) --}}
                                <option value="">Principal</option>
                                <option value="">Teste</option>
                                {{-- @endforeach --}}
                            </select>
                        </div>

                    </div>
                    <div class="w-3/12 flex text-blue-700" style="margin-top: 20px;">

                        <label for="category_id" class="block text-blue-700 text-sm font-bold mb-2"
                            style="font-size: 20px;">Como funciona essa Aba
                        </label>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                        </svg>
                    </div>
                </div>
            </div>
            <br>


            {{-- <div class="mx-auto overflow-x-auto bg-white" style="border:1px solid red;">
                <div class="w-full m-auto" style="border: 1px solid black">
                
                </div>

            </div> --}}

            <div class="w-full mx-auto bg-white p-8 border rounded-md mt-8">

                <div class="w-5/6 mx-auto bg-white p-8 border rounded-md mt-8">


                    <div class="grid grid-cols-2 gap-4" s>
                        <!-- Coluna 1 -->
                        <div>
                            <h2 class="text-2xl font-semibold mb-6">Dados da Reversa</h2>

                            <div class="w-full mb-4 mr-4">
                                <label for="campo1" class="block text-gray-700 text-sm font-bold mb-2">Cliente que
                                    devolverá</label>
                                <input type="text" name="campo1" id="campo1" class="w-full border p-2 rounded">
                            </div>

                            <div class="w-full mb-4 mr-4">
                                <label for="category_id" class="block text-gray-700 text-sm font-bold mb-2">Forma de
                                    Envio</label>
                                <select name="category_id" id="category_id" class="w-full border p-2 rounded">
                                    {{-- @foreach ($categories as $category) --}}
                                    <option value="">Selecione</option>
                                    <option value="">Sedex</option>
                                    <option value="">Pac</option>
                                    {{-- @endforeach --}}
                                </select>
                            </div>

                            <div class="w-full mb-4 mr-4">
                                <label for="cep" class="block text-gray-700 text-sm font-bold mb-2">CEP</label>
                                <input type="text" name="cep" id="cep" class="w-full border p-2 rounded"
                                    placeholder="Digite seu CEP">
                            </div>

                            <div class="w-full mb-4 mr-4">
                                <label for="delivery_address"
                                    class="block text-gray-700 text-sm font-bold mb-2">Logradouro</label>
                                <input type="text" name="delivery_address" id="delivery_address"
                                    class="w-full border p-2 rounded">
                            </div>

                            <div class="w-full mb-4">
                                <label for="category_id"
                                    class="block text-gray-700 text-sm font-bold mb-2">Número</label>
                                <input type="text" name="delivery_address" id="delivery_address"
                                    class="w-full border p-2 rounded">
                            </div>

                            <div class="w-full mb-4 mr-4">
                                <label for="cep"
                                    class="block text-gray-700 text-sm font-bold mb-2">Complemento</label>
                                <input type="text" name="cep" id="cep" class="w-full border p-2 rounded"
                                    placeholder="">
                            </div>

                            <div class="w-full mb-4 mr-4">
                                <label for="delivery_address"
                                    class="block text-gray-700 text-sm font-bold mb-2">Bairro</label>
                                <input type="text" name="delivery_address" id="delivery_address"
                                    class="w-full border p-2 rounded">
                            </div>

                            <div class="w-full mb-4">
                                <label for="category_id"
                                    class="block text-gray-700 text-sm font-bold mb-2">Cidade</label>
                                <input type="text" name="delivery_address" id="delivery_address"
                                    class="w-full border p-2 rounded">
                            </div>

                            <div class="w-full mb-4 mr-4">
                                <label for="cep"
                                    class="block text-gray-700 text-sm font-bold mb-2">Estado</label>
                                <select name="category_id" id="category_id" class="w-full border p-2 rounded">
                                    {{-- @foreach ($categories as $category) --}}
                                    <option value="">Selecione</option>

                                    {{-- @endforeach --}}
                                </select>
                            </div>

                            <div class="w-full mb-4 mr-4">
                                <label for="delivery_address"
                                    class="block text-gray-700 text-sm font-bold mb-2">Peso</label>
                                <select name="category_id" id="category_id" class="w-full border p-2 rounded">
                                    {{-- @foreach ($categories as $category) --}}
                                    <option value="">Selecione</option>

                                    {{-- @endforeach --}}
                                </select>
                            </div>
                        </div>

                        <!-- Coluna 2 -->
                        <div>
                            <h2 class="text-2xl font-semibold mb-6">Campos Opcionais</h2>
                            <div class="w-full mb-4 mr-4">
                                <label for="title" class="block text-gray-700 text-sm font-bold mb-2">email</label>
                                <input type="text" name="title" id="title"
                                    class="w-full border p-2 rounded">
                            </div>

                            <div class="w-full mb-4 mr-4">
                                <label for="delivery_address"
                                    class="block text-gray-700 text-sm font-bold mb-2">Seguro</label>
                                <input type="text" name="delivery_address" id="delivery_address"
                                    class="w-full border p-2 rounded">
                            </div>

                            <div class="w-full mb-4 mr-4">
                                <label for="cep" class="block text-gray-700 text-sm font-bold mb-2">Nota
                                    Fiscal</label>
                                <input type="text" name="cep" id="cep"
                                    class="w-full border p-2 rounded" placeholder="">
                            </div>

                            <div class="w-full mb-4 mr-4">
                                <label for="delivery_address"
                                    class="block text-gray-700 text-sm font-bold mb-2">AR</label>
                                <select name="category_id" id="category_id" class="w-full border p-2 rounded">
                                    {{-- @foreach ($categories as $category) --}}
                                    <option value="">Não</option>
                                    <option value="">Sim</option>

                                    {{-- @endforeach --}}
                                </select>
                            </div>

                            <div class="w-full mb-4 mr-4">
                                <label for="cep"
                                    class="block text-gray-700 text-sm font-bold mb-2">Altura</label>
                                <select name="category_id" id="category_id" class="w-full border p-2 rounded">
                                    {{-- @foreach ($categories as $category) --}}
                                    <option value="">Selecione</option>

                                    {{-- @endforeach --}}
                                </select>
                            </div>

                            <div class="w-full mb-4 mr-4">
                                <label for="delivery_address"
                                    class="block text-gray-700 text-sm font-bold mb-2">Comprimento</label>
                                <select name="category_id" id="category_id" class="w-full border p-2 rounded">
                                    {{-- @foreach ($categories as $category) --}}
                                    <option value="">Selecione</option>

                                    {{-- @endforeach --}}
                                </select>
                            </div>



                            <div class="w-full mb-4 mr-4">
                                <label for="cep"
                                    class="block text-gray-700 text-sm font-bold mb-2">Largura</label>
                                <select name="category_id" id="category_id" class="w-full border p-2 rounded">
                                    {{-- @foreach ($categories as $category) --}}
                                    <option value="">Selecione</option>

                                    {{-- @endforeach --}}
                                </select>
                            </div>

                            <div class="w-full mb-4 mr-4">
                                <label for="delivery_address"
                                    class="block text-gray-700 text-sm font-bold mb-2">Observação</label>
                                <input type="text" name="cep" id="cep"
                                    class="w-full border p-2 rounded" placeholder="">
                            </div>

                        </div>
                    </div>

                    <!-- Outros campos do formulário -->

                    <div class="flex justify-end mt-4">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Gerar Reversa</button>
                    </div>
                </div>
            </div>


        </div>
    </div>

</x-app-layout>

<script>
    $(document).on("click", "#btnInfoCol", function() {
        var info = $(this).attr('data-id');
        var rota = "{{ route('coleta.show', ['id' => ':idenvio']) }}"
        rota = rota.replace(":idenvio", info);
        $.ajax({
            url: rota,

            success: function(data) {
                $('#linha_' + info).css("background", "#2d6984");
                $('#linha_' + info).css("color", "white");
                $('#idenvio_' + info).css("color", "white");
                $('#detalhes_' + info).show();
                $('#detalhes_' + info).append(data.html);
            },
        });


    });
</script>
