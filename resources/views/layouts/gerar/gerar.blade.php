<x-app-layout>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


  <div class="w-5/6 ml-auto lg:px-12">
    <div class="text-4xl">
        <h1 class="text-gray-500 font-bold">Envios Pendentes</h1>
    </div>

    <x-modal-bling/>

    <div class="w-full">
      <div x-data="{ open: false }" class="flex flex-row-reverse">
        <!-- Botão para abrir o modal -->
        <div class="flex text-xs mt-12">
          <button @click="open = true"
            class="bg-red-700 hover:bg-red-800 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>                                  
            <p class="ml-1">Importar loja integrada</p>
          </button>
          <button 
            onclick="abreModalBling()"
            class="bg-green-600 hover:bg-green-700 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>                                  
            <p class="ml-1">Importar bling</p>
          </button>
          <button @click="open = true"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0 3 3m-3-3-3 3M6.75 19.5a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z" />
            </svg>                                   
            <p class="ml-1">Importar nuvem</p>
          </button>
          <button @click="open = true"
            class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m6.75 12-3-3m0 0-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
            </svg>                 
            <p class="ml-1">Importar csv</p>
          </button>
          <button @click="open = true"
            class="bg-gray-500 hover:bg-gray-700 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m6.75 12-3-3m0 0-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
            </svg>                 
            <p class="ml-1">Incluir envio</p>
          </button>
        </div>
        <!-- O modal -->
        <div x-show="open" @click.away="open = false" class="w-full fixed inset-0 overflow-y-auto animate__animated animate__fadeIn">
          <div
            class="w-full flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0 bg-gray-800 bg-opacity-75 transition-opacity">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="open"
              class="ml-56 inline-block align-bottom bg-white 
              rounded-lg text-left overflow-hidden shadow-xl 
              transform transition-all sm:my-8 sm:align-middle sm:w-1/2"
              >
              <!-- Conteúdo do modal aqui -->
              <div class=" px-4 pt-5 pb-4 sm:p-6 sm:pb-4 bg-white">
                <div class="sm:flex sm:items-start">
                    <!-- Título do modal -->
                  <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <div class="flex justify-between">
                      <h2 class="text-2xl mb-6 text-gray-500">Incluir envio</h2>
                      <svg
                        @click="open = false"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 cursor-pointer">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                      </svg>
                    </div>
                    <div class="w-full mx-auto bg-white p-8 rounded-md mt-8 border">
                      <h4 class="text-2xl mb-6 text-gray-600">Dados do envio</h4>
                      <form action="" method="POST">
                        @csrf

                        <div class="flex w-full">
                          <div class="w-full mb-4 mr-4">
                            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Remetente</label>
                            <input type="text" name="title" id="title" class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500" value="Principal">  
                          </div>

                          <div class="w-full mb-4 mr-4">
                            <label for="delivery_address" class="block text-gray-700 text-sm font-bold mb-2">Destinatário</label>
                            <input type="text" name="delivery_address" id="delivery_address" class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                          </div>

                          <div class="w-full mb-4">
                              <label for="category_id" class="block text-gray-700 text-sm font-bold mb-2">Forma de envio</label>
                              <select name="category_id" id="category_id" class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                  {{-- @foreach ($categories as $category) --}}
                                  <option value="">Selecione</option>
                                  <option value="">Sedex</option>
                                  <option value="">Pac</option>
                                  {{-- @endforeach --}}
                              </select>
                          </div>
                        </div>

                        <div class="flex w-full">
                          <div class="w-1/2 mb-4 mr-4">
                            <label for="cep" class="block text-gray-700 text-sm font-bold mb-2">CEP</label>
                            <input type="text" name="cep" id="cep"
                              class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500"
                              placeholder="Digite seu CEP">
                          </div>

                          <div class="w-full mb-4 mr-4">
                            <label for="delivery_address" class="block text-gray-700 text-sm font-bold mb-2">Logradouro</label>
                            <input type="text" name="delivery_address"
                              id="delivery_address" class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                          </div>

                          <div class="w-1/2 mb-4">
                            <label for="category_id" class="block text-gray-700 text-sm font-bold mb-2">Número</label>
                            <input type="text" name="delivery_address"
                              id="delivery_address" class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                          </div>
                        </div>

                        <div class="flex w-full">
                          <div class="w-full mb-4 mr-4">
                            <label for="cep" class="block text-gray-700 text-sm font-bold mb-2">Complemento</label>
                            <input type="text" name="cep" id="cep"
                              class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500" 
                              placeholder="">
                          </div>

                          <div class="w-full mb-4 mr-4">
                            <label for="delivery_address" class="block text-gray-700 text-sm font-bold mb-2">Bairro</label>
                            <input type="text" name="delivery_address"
                              id="delivery_address" class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                          </div>

                          <div class="w-full mb-4">
                            <label for="category_id" class="block text-gray-700 text-sm font-bold mb-2">Cidade</label>
                            <input type="text" name="delivery_address"
                              id="delivery_address" class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                          </div>
                        </div>

                        <div class="flex w-full">
                          <div class="w-full mb-4 mr-4">
                            <label for="cep" class="block text-gray-700 text-sm font-bold mb-2">Estado</label>
                            <select name="category_id" id="category_id"
                              class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                              {{-- @foreach ($categories as $category) --}}
                              <option value="">Selecione</option>
                              {{-- @endforeach --}}
                            </select>
                          </div>

                          <div class="w-full mb-4 mr-4">
                            <label for="delivery_address" class="block text-gray-700 text-sm font-bold mb-2">Peso</label>
                            <select name="category_id" id="category_id"
                              class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                              {{-- @foreach ($categories as $category) --}}
                              <option value="">Selecione</option>
                              {{-- @endforeach --}}
                            </select>
                          </div>
                        </div>

                        <hr class="border-gray-400 border-dashed mt-4">

                        <h4 class="text-2xl mb-6 text-gray-600 mt-10">Campos opcionais</h4>

                        <div class="flex w-full">
                          <div class="w-full mb-4 mr-4">
                            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">email</label>
                            <input type="text" name="title" id="title" class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                          </div>

                          <div class="w-full mb-4 mr-4">
                            <label for="delivery_address" class="block text-gray-700 text-sm font-bold mb-2">Seguro</label>
                            <input type="text" name="delivery_address" id="delivery_address" class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                          </div>
                        </div>

                        <div class="flex w-full">
                          <div class="w-full mb-4 mr-4">
                            <label for="cep" class="block text-gray-700 text-sm font-bold mb-2">Nota fiscal</label>
                            <input type="text" name="cep" id="cep"
                              class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500" 
                              placeholder="">
                          </div>

                          <div class="w-full mb-4 mr-4">
                            <label for="delivery_address" class="block text-gray-700 text-sm font-bold mb-2">AR</label>
                            <select name="category_id" id="category_id"
                              class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                              {{-- @foreach ($categories as $category) --}}
                              <option value="">Não</option>
                              <option value="">Sim</option>
                              {{-- @endforeach --}}
                            </select>
                          </div>
                        </div>

                        <div class="flex w-full">
                          <div class="w-full mb-4 mr-4">
                            <label for="cep" class="block text-gray-700 text-sm font-bold mb-2">Altura</label>
                            <select name="category_id" id="category_id"
                              class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                              {{-- @foreach ($categories as $category) --}}
                              <option value="">Selecione</option>
                              {{-- @endforeach --}}
                            </select>
                          </div>

                          <div class="w-full mb-4 mr-4">
                            <label for="delivery_address" class="block text-gray-700 text-sm font-bold mb-2">Comprimento</label>
                            <select name="category_id" id="category_id"
                              class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                              {{-- @foreach ($categories as $category) --}}
                              <option value="">Selecione</option>
                              {{-- @endforeach --}}
                            </select>
                          </div>
                        </div>

                        <div class="flex w-full">
                          <div class="w-full mb-4 mr-4">
                            <label for="cep" class="block text-gray-700 text-sm font-bold mb-2">Largura</label>
                            <select name="category_id" id="category_id"
                              class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                              {{-- @foreach ($categories as $category) --}}
                              <option value="">Selecione</option>
                              {{-- @endforeach --}}
                            </select>
                          </div>

                          <div class="w-full mb-4 mr-4">
                            <label for="delivery_address" class="block text-gray-700 text-sm font-bold mb-2">Observação</label>
                            <input type="text" name="cep" id="cep"
                              class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500" placeholder="">
                          </div>
                        </div>

                        <div class="flex justify-end text-xs mt-4">
                          <button @click="open = false"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold px-2 py-1 rounded flex items-center">    
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                              <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>                                                 
                            <p class="ml-1">Salvar envio</p>
                          </button>
                          <button @click="open = false"
                            class="bg-red-500 hover:bg-red-700 text-white font-bold px-2 py-1 rounded flex items-center ml-1">    
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>                          
                            <p class="ml-1">Fechar</p>
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
      <table
        class="min-w-full table-auto ml-auto bg-white font-normal rounded shadow-lg
        text-sm text-left text-gray-500 border-collapse overflow-x-auto border-1">
        <thead class="text-xs text-gray-700 uppercase bg-gray-200">
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

            </th>
          </tr>
        </thead>
        <tbody>
          @foreach ($envios as $envio)
            <tr class="bg-white hover:bg-gray-100 border-b rounded-full font-light" id="linha_{{ $envio->id }}">
              <th class="px-6 py-4 rounded-s-lg" id="idenvio_{{ $envio->id }}">
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
              <td dir="rtl" class=" py-2 rounded-s-lg">
                  R$ {{ $envio->total }}

              </td>
              <td dir="rtl" class="py-2 rounded-s-lg">
                R$ {{ $envio->desconto }}
              </td>

              <td dir="rtl" class="px-2 py-2 rounded-s-lg ml-2">
                <div class="flex cursor-pointer">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                      stroke-width="1.5" stroke="currentColor"
                      class="w-4 h-4 sm:w-5 sm:h-6 stroke-red-600">
                      <path stroke-linecap="round" stroke-linejoin="round"
                          d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                  </svg>

                  <a>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        class="w-4 h-4 sm:w-5 sm:h-6 stroke-yellow-600">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                  </a>
                </div>
              </td>
            </tr>
            @endforeach
        </tbody>
      </table>
      <div class="flex justify-between pb-6">
        <div class="flex mt-2 text-xs">
          <button @click="open = true"
            class="bg-red-500 hover:bg-red-700 text-white font-bold px-2 py-1 rounded flex items-center">    
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>                          
            <p class="ml-1">Remover</p>
          </button>
          <button @click="open = true"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold px-2 py-1 ml-2 rounded flex items-center"> 
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6 9 12.75l4.286-4.286a11.948 11.948 0 0 1 4.306 6.43l.776 2.898m0 0 3.182-5.511m-3.182 5.51-5.511-3.181" />
            </svg>                
            <p class="ml-1">Gerar etiquetas</p>
          </button>
        </div>
        <nav class="mt-2">
          <ul class="inline-flex -space-x-px mt-2 text-xs">
            @if ($envios->currentPage() > 1)
              <li>
                <a href="?page={{ $envios->currentPage() - 1 }}" 
                  class="py-2 px-3 ml-0 leading-tight text-gray-500 bg-white 
                    rounded-l-lg border border-gray-300 hover:bg-gray-100 
                    hover:text-gray-700">
                  Anterior
                </a>
              </li>
            @endif
            @for ($i = 1; $i <= $envios->lastPage(); $i++)
              <li>
                <a href="?page={{ $i }}" 
                    class="py-2 px-3 {{ $envios->currentPage() == $i ? 'text-blue-600 bg-blue-50' : 'text-gray-500 bg-white' }}
                    border border-gray-300 hover:bg-gray-100 
                    hover:text-gray-700">
                    {{ $i }}
                </a>
              </li>
            @endfor
            @if ($envios->currentPage() < $envios->lastPage())
              <li>
                <a href="?page={{ $envios->currentPage() + 1 }}" 
                  class="py-2 px-3 leading-tight text-gray-500 bg-white
                  rounded-r-lg border border-gray-300 hover:bg-gray-100 
                  hover:text-gray-700">
                  Próxima
                </a>
              </li>
            @endif
          </ul>
        </nav>
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
