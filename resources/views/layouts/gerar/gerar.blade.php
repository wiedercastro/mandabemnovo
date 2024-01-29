<x-app-layout>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

         
        <div class="w-5/6 ml-auto lg:px-12" style="border: 0px solid red;">
            <div class="text-4xl" style="margin-top:-25px">
                <h1 style="color:#728189"><b>Envios Pendentes</b></h1>
            </div>
            
            <div class="w-full dark:bg-gray-800">
                <div x-data="{ open: false }">
                    <!-- Botão para abrir o modal -->
                    <button @click="open = true" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Importar Nuvem
                    </button>
                    <button @click="open = true" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Incluir Envio
                    </button>
                    <!-- O modal -->
                    <div x-show="open" @click.away="open = false" class="w-full fixed inset-0 overflow-y-auto">
                        <div class="w-full flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                            </div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                            <div x-show="open" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3x1"  style="border: 1px solid black;">
                                <!-- Conteúdo do modal aqui -->
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="sm:flex sm:items-start">
                                        <!-- Título do modal -->
                                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                            <h2 class="text-2xl font-semibold mb-6">Incluir Envio</h2>
                                            <div class="w-full mx-auto bg-white p-8 border rounded-md mt-8">
                                                <h4 class="text-2xl font-semibold mb-6">Dados do Envio</h4>  
                                                
                                                <form action="" method="POST">
                                                    @csrf
                                                    
                                                    <div class="flex w-full" style="border: 0px solid red; width: 800px;">
                                                        
                                                        <div class=" w-fullmb-4 mr-4">
                                                            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Remetente</label>
                                                            <input type="text" name="title" id="title" class="w-full border p-2 rounded" value="Principal">
                                                        </div>
                                            
                                                        <div class="w-full mb-4 mr-4">
                                                            <label for="delivery_address" class="block text-gray-700 text-sm font-bold mb-2">Destinatário</label>
                                                            <input type="text" name="delivery_address" id="delivery_address" class="w-full border p-2 rounded">
                                                        </div>

                                                        <div class="w-full mb-4">
                                                            <label for="category_id" class="block text-gray-700 text-sm font-bold mb-2">Forma de Envio</label>
                                                            <select name="category_id" id="category_id" class="w-full border p-2 rounded">
                                                                {{-- @foreach ($categories as $category) --}}
                                                                    <option value="">Selecione</option>
                                                                    <option value="">Sedex</option>
                                                                    <option value="">Pac</option>
                                                                {{-- @endforeach --}}
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="flex w-full" style="border: 0px solid red; width: 800px;">
                                                        
                                                        <div class="w-1/2 mb-4 mr-4">
                                                            <label for="cep" class="block text-gray-700 text-sm font-bold mb-2">CEP</label>
                                                            <input type="text" name="cep" id="cep" class="w-full border p-2 rounded" placeholder="Digite seu CEP">
                                                        </div>
                                            
                                                        <div class="w-full mb-4 mr-4">
                                                            <label for="delivery_address" class="block text-gray-700 text-sm font-bold mb-2">Logradouro</label>
                                                            <input type="text" name="delivery_address" id="delivery_address" class="w-full border p-2 rounded">
                                                        </div>

                                                        <div class="w-1/2 mb-4">
                                                            <label for="category_id" class="block text-gray-700 text-sm font-bold mb-2">Número</label>
                                                            <input type="text" name="delivery_address" id="delivery_address" class="w-full border p-2 rounded">
                                                        </div>
                                                    </div>

                                                    <div class="flex w-full" style="border: 0px solid red; width: 800px;">
                                                        
                                                        <div class="w-full mb-4 mr-4">
                                                            <label for="cep" class="block text-gray-700 text-sm font-bold mb-2">Complemento</label>
                                                            <input type="text" name="cep" id="cep" class="w-full border p-2 rounded" placeholder="">
                                                        </div>
                                            
                                                        <div class="w-full mb-4 mr-4">
                                                            <label for="delivery_address" class="block text-gray-700 text-sm font-bold mb-2">Bairro</label>
                                                            <input type="text" name="delivery_address" id="delivery_address" class="w-full border p-2 rounded">
                                                        </div>

                                                        <div class="w-full mb-4">
                                                            <label for="category_id" class="block text-gray-700 text-sm font-bold mb-2">Cidade</label>
                                                            <input type="text" name="delivery_address" id="delivery_address" class="w-full border p-2 rounded">
                                                        </div>
                                                    </div>

                                                    <div class="flex w-full" style="border: 0px solid red; width: 800px;">
                                                        
                                                        <div class="w-full mb-4 mr-4">
                                                            <label for="cep" class="block text-gray-700 text-sm font-bold mb-2">Estado</label>
                                                            <select name="category_id" id="category_id" class="w-full border p-2 rounded">
                                                                {{-- @foreach ($categories as $category) --}}
                                                                    <option value="">Selecione</option>
                                                                    
                                                                {{-- @endforeach --}}
                                                            </select>
                                                        </div>
                                            
                                                        <div class="w-full mb-4 mr-4">
                                                            <label for="delivery_address" class="block text-gray-700 text-sm font-bold mb-2">Peso</label>
                                                            <select name="category_id" id="category_id" class="w-full border p-2 rounded">
                                                                {{-- @foreach ($categories as $category) --}}
                                                                    <option value="">Selecione</option>
                                                                    
                                                                {{-- @endforeach --}}
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <h4 class="text-2xl font-semibold mb-6">Campos Opcionais</h4> 


                                                    <div class="flex w-full" style="border: 0px solid red; width: 800px;">
                                                        
                                                        <div class="w-full mb-4 mr-4">
                                                            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">email</label>
                                                            <input type="text" name="title" id="title" class="w-full border p-2 rounded">
                                                        </div>
                                            
                                                        <div class="w-full mb-4 mr-4">
                                                            <label for="delivery_address" class="block text-gray-700 text-sm font-bold mb-2">Seguro</label>
                                                            <input type="text" name="delivery_address" id="delivery_address" class="w-full border p-2 rounded">
                                                        </div>

                                                       
                                                    </div>

                                                    <div class="flex w-full" style="border: 0px solid red; width: 800px;">
                                                        
                                                        <div class="w-full mb-4 mr-4">
                                                            <label for="cep" class="block text-gray-700 text-sm font-bold mb-2">Nota Fiscal</label>
                                                            <input type="text" name="cep" id="cep" class="w-full border p-2 rounded" placeholder="">
                                                        </div>
                                            
                                                        <div class="w-full mb-4 mr-4">
                                                            <label for="delivery_address" class="block text-gray-700 text-sm font-bold mb-2">AR</label>
                                                            <select name="category_id" id="category_id" class="w-full border p-2 rounded">
                                                                {{-- @foreach ($categories as $category) --}}
                                                                    <option value="">Não</option>
                                                                    <option value="">Sim</option>
                                                                    
                                                                {{-- @endforeach --}}
                                                            </select>
                                                        </div>

                                                        
                                                    </div>

                                                    <div class="flex w-full" style="border: 0px solid red; width: 800px;">
                                                        
                                                        <div class="w-full mb-4 mr-4">
                                                            <label for="cep" class="block text-gray-700 text-sm font-bold mb-2">Altura</label>
                                                            <select name="category_id" id="category_id" class="w-full border p-2 rounded">
                                                                {{-- @foreach ($categories as $category) --}}
                                                                    <option value="">Selecione</option>
                                                                    
                                                                {{-- @endforeach --}}
                                                            </select>
                                                        </div>
                                            
                                                        <div class="w-full mb-4 mr-4">
                                                            <label for="delivery_address" class="block text-gray-700 text-sm font-bold mb-2">Comprimento</label>
                                                            <select name="category_id" id="category_id" class="w-full border p-2 rounded">
                                                                {{-- @foreach ($categories as $category) --}}
                                                                    <option value="">Selecione</option>
                                                                    
                                                                {{-- @endforeach --}}
                                                            </select>
                                                        </div>

                                                       
                                                    </div>

                                                    <div class="flex w-full" style="border: 0px solid red; width: 800px;">
                                                        
                                                        <div class="w-full mb-4 mr-4">
                                                            <label for="cep" class="block text-gray-700 text-sm font-bold mb-2">Largura</label>
                                                            <select name="category_id" id="category_id" class="w-full border p-2 rounded">
                                                                {{-- @foreach ($categories as $category) --}}
                                                                    <option value="">Selecione</option>
                                                                    
                                                                {{-- @endforeach --}}
                                                            </select>
                                                        </div>
                                            
                                                        <div class="w-full mb-4 mr-4">
                                                            <label for="delivery_address" class="block text-gray-700 text-sm font-bold mb-2">Observação</label>
                                                            <input type="text" name="cep" id="cep" class="w-full border p-2 rounded" placeholder="">
                                                        </div>
                                                    </div>

                                        
                                                    <div class="flex justify-end">
                                                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Salvar Envior</button>
                                                        <button @click="open = false" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-500 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                                            Fechar
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Botões de fechar e outras ações -->
                                
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                {{-- <div class="text-4xl" style="margin-left: 5px;">
                    <h1 style="color:#728189"><b>Etiquetas</b></h1>
                </div> --}}

                <div  class="mx-auto overflow-x-auto">
                <table
                    class="min-w-full table-auto ml-auto bg-white text-sm text-left text-gray-500 dark:text-gray-400 border-collapse">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                               
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Data
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Destinatário
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Forma de Envio
                            </th>
                            <th scope="col" class="px-1 py-3">
                                Balcão
                            </th>
                            <th scope="col" class="px-1 py-3">
                                Desconto
                            </th>
                            <th scope="col" class="px-1 py-3">
                                Total
                            </th>
                            <th scope="col" class="px-1 py-3">
                                Ação
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($envios as $envio)
                            <tr class="bg-white border-b dark:bg-blue-800 dark:border-gray-700 hover:bg-blue-100 dark:hover:bg-blue-100 rounded-full"
                                id="linha_{{ $envio->id }}">

                                <th class="px-6 py-4 rounded-s-lg" style="color:#2d6984"
                                    id="idenvio_{{ $envio->id }}">
                                    <input type="checkbox" id="aceito_termos" name="aceito_termos" class="mr-2">
                                </th>
                                <td class="px-6 py-4">
                                    <button id="btnInfoCol" data-id ="{{ $envio->id }}">
                                        <i class="fa fa-clock-ow" aria-hidden="true"></i> 24/12/2023 <br>
                                    </button>
                                </td>
                                <td class="px-6 py-4">
                                    Teste
                                </td>
                                <td class="px-6 py-4 font-medium text-green-950">
                                    Sedex
                                </td>
                                <td class="px-2 py-2">
                                    R$ 10,00
                                </td>
                                <td dir="rtl" class="px-2 py-2 text-right rounded-s-lg">
                                    R$ {{ $envio->total }}
                                    
                                </td>
                                <td dir="rtl" class="px-2 py-2 text-right rounded-s-lg">
                                    R$ {{ $envio->desconto }}
                                    
                                </td>

                                <td dir="rtl" class="px-2 py-2 text-right rounded-s-lg">
                                    <div class="flex cursor-pointer">
                                    <a>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                          stroke="currentColor" class="w-4 h-4 sm:w-5 sm:h-6 stroke-gray-600">
                                          <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                      </a>
              
                                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" class="w-4 h-4 sm:w-5 sm:h-6 stroke-gray-600">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                          d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                      </svg>
                                    </div>
                                    
                                </td>

                            </tr>

                            <tr>
                                <td colspan="6">
                                    <div id="detalhes_{{ $envio->id }}" style="display: none">

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="w-full m-auto py-4">
                    {{ $envios->links() }}
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
