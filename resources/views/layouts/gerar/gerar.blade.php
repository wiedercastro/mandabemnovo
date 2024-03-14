<x-app-layout>

    <div class="w-5/6 ml-auto lg:px-12">
        <div class="text-4xl">
            <h1 class="text-gray-500 font-bold">Envios Pendentes</h1>
        </div>
  
        <div class="justify-center items-center hidden" id="modal_gerar">
          <div>
            <!-- Open modal button -->
            <!-- Modal Overlay -->
            <div class="fixed inset-0 px-2 z-10 overflow-hidden flex items-center justify-center animate__animated animate__fadeIn">
              <div class="absolute inset-0 bg-gray-800 bg-opacity-75 transition-opacity"></div>
              <!-- Modal Content -->
              <div
                class="bg-white rounded-md shadow-xl overflow-hidden max-w-xl w-full sm:w-full md:w-2/3 lg:w-3/4 xl:w-2/3 z-50">
                <!-- Modal Header -->
                <div class="bg-gray-200 text-white px-4 py-4 flex justify-between">
                  <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 stroke-gray-600">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    
                    <h2 class="text-2xl font-semibold text-gray-600 ml-2">
                      Gerar etiqueta
                    </h2>
                  </div>
                  <svg
                    onclick="fechaModal()"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-8 h-8 stroke-gray-600 cursor-pointer">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                  </svg>
                </div>
  
                <hr>
                <div id="content">
                  {{-- AQUI SERA EXIBIDO O CONTEUDO --}}
                </div>

                <div class="flex flex-row-reverse mt-8 p-2">
                  <button
                    onclick="fechaModal()"
                    class="text-sm bg-red-500 hover:bg-red-600 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>                                       
                    <p>Cancelar</p>
                  </button>
                  <button
                    id="buttonGerarEtiquetas"
                    onclick="gerarEtiquetas()"
                    class="text-sm bg-yellow-500 hover:bg-yellow-600 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.894.149c-.424.07-.764.383-.929.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.398.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.272-.806.108-1.204-.165-.397-.506-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894Z" />
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>                    
                    <p class="">Gerar</p>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
  
        <div class="w-full">
            <div x-data="{ open: false }" class="flex flex-row-reverse">
                <!-- Botão para abrir o modal -->
                <div class="flex text-xs mt-12">
                    <button @click="open = true"
                        class="bg-red-700 hover:bg-red-800 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        <p class="ml-1">Importar loja integrada</p>
                    </button>
                    <button @click="open = true"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        <p class="ml-1">Importar bling</p>
                    </button>
                    <button @click="open = true"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 16.5V9.75m0 0 3 3m-3-3-3 3M6.75 19.5a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z" />
                        </svg>
                        <p class="ml-1">Importar nuvem</p>
                    </button>
                    <button @click="open = true"
                        class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m6.75 12-3-3m0 0-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        <p class="ml-1">Importar csv</p>
                    </button>
                    <button @click="open = true"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m6.75 12-3-3m0 0-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        <p class="ml-1">Incluir envio</p>
                    </button>
                </div>
                <!-- O modal -->
                <div x-show="open" @click.away="open = false" id="modal_incluir"
                    class="w-full fixed inset-0 overflow-y-auto animate__animated animate__fadeIn">
                    <div
                        class="w-full flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0 bg-gray-800 bg-opacity-75 transition-opacity">
                        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                            <div class="absolute inset-0 opacity-75"></div>
                        </div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                            aria-hidden="true">&#8203;</span>
                        <div x-show="open" id="modal_incluir1"
                            class="ml-56 inline-block align-bottom bg-white 
              rounded-lg text-left overflow-hidden shadow-xl 
              transform transition-all sm:my-8 sm:align-middle sm:w-1/2">
                            <!-- Conteúdo do modal aqui -->
                            <div class=" px-4 pt-5 pb-4 sm:p-6 sm:pb-4 bg-white">
                                <div class="sm:flex sm:items-start">
                                    <!-- Título do modal -->
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                        <div class="flex justify-between">
                                            <h2 class="text-2xl mb-6 text-gray-500">Incluir envio</h2>
                                            <svg @click="open = false" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="w-6 h-6 cursor-pointer">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18 18 6M6 6l12 12" />
                                            </svg>
                                        </div>
                                        <div class="w-full mx-auto bg-white p-8 rounded-md mt-8 border">
                                            <h4 class="text-2xl mb-6 text-gray-600">Dados do envio</h4>
                                            <form id="myForm" action="" method="POST">
                                                @csrf
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}" id="_token">
                                                <div class="flex w-full">
                                                    <div class="w-full mb-4 mr-4">
                                                        <label for="remetente"
                                                            class="block text-gray-700 text-sm font-bold mb-2">Remetente</label>
                                                        <input type="text" name="remetente" id="remetente"
                                                            class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500"
                                                            value="Principal">
                                                    </div>
  
                                                    <div class="w-full mb-4 mr-4">
                                                        <label for="destinatario"
                                                            class="block text-gray-700 text-sm font-bold mb-2">Destinatário</label>
                                                        <input type="text" name="destinatario" id="destinatario"
                                                            class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                                    </div>
  
                                                    <div class="w-full mb-4">
                                                        <label for="forma_envio"
                                                            class="block text-gray-700 text-sm font-bold mb-2">Forma de
                                                            envio</label>
                                                        <select name="forma_envio" id="forma_envio"
                                                            class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                                            {{-- @foreach ($categories as $category) --}}
                                                            <option value="">Selecione</option>
                                                            <option value="SEDEX">Sedex</option>
                                                            <option value="PAC">Pac</option>
                                                            {{-- @endforeach --}}
                                                        </select>
                                                    </div>
                                                </div>
  
                                                <div class="flex w-full">
                                                    <div class="w-1/2 mb-4 mr-4">
                                                        <label for="CEP"
                                                            class="block text-gray-700 text-sm font-bold mb-2">CEP</label>
                                                        <input type="text" name="CEP" id="CEP"
                                                            value="{{ old('CEP') }}"
                                                            class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500"
                                                            placeholder="Digite seu CEP">
                                                    </div>
  
                                                    <div class="w-full mb-4 mr-4">
                                                        <label for="logradouro"
                                                            class="block text-gray-700 text-sm font-bold mb-2">Logradouro</label>
                                                        <input type="text" name="logradouro" id="logradouro"
                                                            class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                                    </div>
  
                                                    <div class="w-1/2 mb-4">
                                                        <label for="numero"
                                                            class="block text-gray-700 text-sm font-bold mb-2">Número</label>
                                                        <input type="text" name="numero" id="numero"
                                                            class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                                    </div>
                                                </div>
  
                                                <div class="flex w-full">
                                                    <div class="w-full mb-4 mr-4">
                                                        <label for="complemento"
                                                            class="block text-gray-700 text-sm font-bold mb-2">Complemento</label>
                                                        <input type="text" name="complemento" id="complemento"
                                                            class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500"
                                                            placeholder="">
                                                    </div>
  
                                                    <div class="w-full mb-4 mr-4">
                                                        <label for="bairro"
                                                            class="block text-gray-700 text-sm font-bold mb-2">Bairro</label>
                                                        <input type="text" name="bairro" id="bairro"
                                                            class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                                    </div>
  
                                                    <div class="w-full mb-4">
                                                        <label for="cidade"
                                                            class="block text-gray-700 text-sm font-bold mb-2">Cidade</label>
                                                        <input type="text" name="cidade" id="cidade"
                                                            class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                                    </div>
                                                </div>
  
                                                <div class="flex w-full">
                                                    <div class="w-full mb-4 mr-4">
                                                        <label for="estado"
                                                            class="block text-gray-700 text-sm font-bold mb-2">Estado</label>
                                                        <select name="estado" id="estado"
                                                            value="{{ old('estado') }}"
                                                            class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                                            {{-- @foreach ($categories as $category) --}}
                                                            <option value="">Selecione</option>
                                                            {{-- @endforeach --}}
                                                        </select>
                                                    </div>
  
                                                    <div class="w-full mb-4 mr-4">
                                                        <label for="peso"
                                                            class="block text-gray-700 text-sm font-bold mb-2">Peso</label>
                                                        <select name="peso" id="peso"
                                                            value="{{ old('peso') }}"
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
                                                        <label for="email"
                                                            class="block text-gray-700 text-sm font-bold mb-2">email</label>
                                                        <input type="text" name="email" id="email"
                                                            class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                                    </div>
  
                                                    <div class="w-full mb-4 mr-4">
                                                        <label for="seguro"
                                                            class="block text-gray-700 text-sm font-bold mb-2">Seguro</label>
                                                        <input type="text" name="seguro" id="seguro"
                                                            class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                                    </div>
                                                </div>
  
                                                <div class="flex w-full">
                                                    <div class="w-full mb-4 mr-4">
                                                        <label for="nota_fiscal"
                                                            class="block text-gray-700 text-sm font-bold mb-2">Nota
                                                            fiscal</label>
                                                        <input type="text" name="nota_fiscal" id="nota_fiscal"
                                                            class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500"
                                                            placeholder="">
                                                    </div>
  
                                                    <div class="w-full mb-4 mr-4">
                                                        <label for="AR"
                                                            class="block text-gray-700 text-sm font-bold mb-2">AR</label>
                                                        <select name="AR" id="AR"
                                                            class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                                            {{-- @foreach ($categories as $category) --}}
                                                            <option value="N">Não</option>
                                                            <option value="S">Sim</option>
                                                            {{-- @endforeach --}}
                                                        </select>
                                                    </div>
                                                </div>
  
                                                <div class="flex w-full">
                                                    <div class="w-full mb-4 mr-4">
                                                        <label for="altura"
                                                            class="block text-gray-700 text-sm font-bold mb-2">Altura</label>
                                                        <select name="altura" id="altura"
                                                            class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                                            {{-- @foreach ($categories as $category) --}}
                                                            <option value="">Selecione</option>
                                                            {{-- @endforeach --}}
                                                        </select>
                                                    </div>
  
                                                    <div class="w-full mb-4 mr-4">
                                                        <label for="comprimento"
                                                            class="block text-gray-700 text-sm font-bold mb-2">Comprimento</label>
                                                        <select name="comprimento" id="comprimento"
                                                            class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                                            {{-- @foreach ($categories as $category) --}}
                                                            <option value="">Selecione</option>
                                                            {{-- @endforeach --}}
                                                        </select>
                                                    </div>
                                                </div>
  
                                                <div class="flex w-full">
                                                    <div class="w-full mb-4 mr-4">
                                                        <label for="largura"
                                                            class="block text-gray-700 text-sm font-bold mb-2">Largura</label>
                                                        <select name="largura" id="largura"
                                                            class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                                            {{-- @foreach ($categories as $category) --}}
                                                            <option value="">Selecione</option>
                                                            {{-- @endforeach --}}
                                                        </select>
                                                    </div>
  
                                                    <div class="w-full mb-4 mr-4">
                                                        <label for="obs"
                                                            class="block text-gray-700 text-sm font-bold mb-2">Observação</label>
                                                        <input type="text" name="obs" id="obs"
                                                            class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500"
                                                            placeholder="">
                                                    </div>
                                                </div>
  
                                                <div class="flex justify-end text-xs mt-4">
                                                    <button type="submit"
                                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold px-2 py-1 rounded flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor" class="w-4 h-4">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="m4.5 12.75 6 6 9-13.5" />
                                                        </svg>
                                                        <p class="ml-1">Salvar envio</p>
                                                    </button>
                                                    <button type="button" @click="open = false" id="btnFechar"
                                                        class="bg-red-500 hover:bg-red-700 text-white font-bold px-2 py-1 rounded flex items-center ml-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor" class="w-4 h-4">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M6 18 18 6M6 6l12 12" />
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
  
            <table id="enviosTable" data-envios="{{ json_encode($envios) }}"
                class="min-w-full table-auto ml-auto bg-white font-normal rounded shadow-lg
              text-sm text-left text-gray-500 border-collapse overflow-x-auto border-1">
                <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                          <input type="checkbox" class="mr-2" name="seleciona_todos" id="seleciona_todos">
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
                        <tr class="bg-white hover:bg-gray-100 border-b rounded-full font-light"
                            id="linha_{{ $envio->id }}">
                            <th class="px-6 py-4 rounded-s-lg">
                                <input type="checkbox" id="id_envio_{{ $envio->id }}"
                                    data-id="{{ $envio->id }}" name="aceito_termos" class="mr-2">
                            </th>
                            <td class="px-6 py-4">
                                <button id="btnInfoCol" data-id ="{{ $envio->id }}">
                                    <i class="fa fa-clock-ow" aria-hidden="true"></i>
                                    {{ date('d/m/Y', strtotime($envio->date_insert)) }} <br>
                                </button>
                            </td>
                            <td class="px-6 py-4">
                                {{ $envio->destinatario }}
                            </td>
                            <td class="px-6 py-4 font-medium text-green-950">
                                {{ $envio->envio }}
                            </td>
                            <td class="px-2 py-2">
                                R$ {{ $envio->balcao }}
                            </td>
                            <td dir="rtl" class=" py-2 rounded-s-lg">
                                R$ {{ $envio->desconto }}
  
                            </td>
                            <td dir="rtl" class="py-2 rounded-s-lg">
                                R$ {{ $envio->total }}
                            </td>
  
                            <td dir="rtl" class="px-2 py-2 rounded-s-lg ml-2">
                                <div class="flex cursor-pointer">
                                    <button id="btnExcluir" data-row-id="{{ $envio->id }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor"
                                            class="w-4 h-4 sm:w-5 sm:h-6 stroke-red-600">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
  
                                    <button id="btnEditar" data-row-id="{{ $envio->id }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor"
                                            class="w-4 h-4 sm:w-5 sm:h-6 stroke-yellow-600">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="flex justify-between pb-6">
                <div class="flex mt-2 text-xs">
                    <button id="btnExcluirSelecionados"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold px-2 py-1 rounded flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                        <p class="ml-1">Remover</p>
                    </button>
                    <button onclick="abreModal()"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold px-2 py-1 ml-2 rounded flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 6 9 12.75l4.286-4.286a11.948 11.948 0 0 1 4.306 6.43l.776 2.898m0 0 3.182-5.511m-3.182 5.51-5.511-3.181" />
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
    let modal_gerar = document.getElementById('modal_gerar');
    let buttonGerarEtiquetas = document.getElementById('buttonGerarEtiquetas')
    let dadosSelecionados = [];
  
    const fechaModal = () => {
      modal_gerar.classList.add('hidden');
    }
  
    const calculaTotalDasEtiquetas = () => {
      let total = 0;
      dadosSelecionados.forEach(item => {
        total += parseFloat(item.total);
      });
      return total;
    }
  
    const abreModal = () => {
      if (dadosSelecionados.length === 0) {
        Swal.fire({
          title: 'Alerta!',
          text: 'Nenhuma etiqueta selecionada.',
          icon: 'warning',
          customClass: {
            confirmButton: 'bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline-yellow active:bg-yellow-800',
          },
          buttonsStyling: false,
          confirmButtonText: 'OK',
        })
        return;
      }
  
      modal_gerar.classList.remove('hidden');
      modal_gerar.classList.add('flex');
  
      let html = '';
  
      dadosSelecionados.forEach(item => {
        html += `
        <div class="flex space-x-8 font-light justify-between items-center text-sm mt-4 px-8">
          <div class="flex items-center w-1/3">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 stroke-green-600 font-bold">
              <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
            </svg>                    
            <p class="ml-1">${item.destinatario}</p>
          </div>
  
          <div class="flex w-1/4">
            <span>${item.id}</span>
          </div>
  
          <div class="flex w-1/3">
            <span class="font-bold text-gray-600">${item.envio}</span>
          </div>
  
          <div class="flex">
            <span>R$</span>
            <p class="ml-1">${item.total}</p>
          </div>
        </div>
        <hr class="mx-4 border-gray-400 border-dashed mt-1">`;
      })
      html += `
      <div class="px-8 py-2">
        <p class="text-xl font-bold text-gray-700">Total: R$ ${calculaTotalDasEtiquetas()}</p>
  
        <div id="alertaCobranca" class="hidden mt-2 flex items-center p-2 mb-4 text-xs text-red-800 rounded-lg bg-red-100" role="alert">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="flex-shrink-0 inline w-5 h-5 me-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
          </svg>                
          <span class="sr-only">Info</span>
          <div>
            <span class="font-medium">ATENÇÃO!</span>
            Cobranças estão INATIVAS. <a href="" class="text-blue-500">Clique aqui</a> para ativar e tente novamente.
          </div>
        </div>
        <div id="alertMensa" class="hidden mt-2 flex items-center p-2 mb-4 text-xs text-red-800 rounded-lg bg-red-100" role="alert">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="flex-shrink-0 inline w-5 h-5 me-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
          </svg>                
          <span class="sr-only">Info</span>
          <div>
            <span class="font-medium">ATENÇÃO!</span>
            <div id="mensagem">
            
            </div>
          </div>
          
        </div>
      </div>
      `
      document.getElementById('content').innerHTML = html
    }
  
    function adicionarEtiquetaAoArray(envio) {
      dadosSelecionados.push(envio);
    }
  
    function removerEtiquetaDoArray(envio) {
      const index = dadosSelecionados.findIndex(item => item.id === envio.id);
      if (index !== -1) {
        dadosSelecionados.splice(index, 1);
      }
    }
  
    const enviosTable = document.getElementById('enviosTable');
    const checkboxes = document.querySelectorAll('input[name="aceito_termos"]');
    const envios = JSON.parse(enviosTable.dataset.envios);
  
    // Função para lidar com o checkbox "Selecionar Todos"
    function selecionaTodosCheckBox(event) {
      const isChecked = event.target.checked;
  
      checkboxes.forEach(checkbox => {
        checkbox.checked = isChecked;
  
        const envioId = checkbox.getAttribute('data-id');
        const envio = envios.data.find(item => item.id === parseInt(envioId));
        if (isChecked) {
          adicionarEtiquetaAoArray(envio);
        } else {
          removerEtiquetaDoArray(envio);
        }
      });
    }
  
    // Obter o elemento checkbox "Selecionar Todos"
    const selectAllCheckbox = document.getElementById('seleciona_todos');
    selectAllCheckbox.addEventListener('change', selecionaTodosCheckBox);
  
    checkboxes.forEach(checkbox => {
      checkbox.addEventListener('change', function () {
        const envioId = this.getAttribute('data-id');
        const envio = envios.data.find(item => item.id === parseInt(envioId));
  
        if (this.checked) {
          adicionarEtiquetaAoArray(envio);
        } else {
          removerEtiquetaDoArray(envio);
        }
      });
    });
  
  
    const gerarEtiquetas = async () => {
      buttonGerarEtiquetas.innerHTML = 'Gerando...'
      buttonGerarEtiquetas.disabled = true;
  
      try {
        const response = await fetch('/gerar-etiquetas', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}' 
          },
          body: JSON.stringify(dadosSelecionados)
        });
          
        if (!response.ok) {
          throw new Error('Erro ao gerar etiquetas: ' + response.statusText);
        }

        const res = await response.json();
       
          if(res.status==1){
              Swal.fire({
                  title: 'Etiqueta gerada com sucesso!',
                  text: '',
                  icon: 'success',
                  customClass: {
                      confirmButton: 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline-blue active:bg-blue-800',
                  },
                  buttonsStyling: false,
                  confirmButtonText: 'OK',
              }).then(function() {
                  // Recarrega a página após a confirmação do usuário
                  location.reload();
              });
          }else{
                document.getElementById('alertMensa').classList.remove('hidden');
                document.getElementById('mensagem').innerHTML = res.error;
         }
        console.log(res);
      } catch (error) {
        console.log(error);
      } finally {
        buttonGerarEtiquetas.innerHTML = 'Gerar'
        buttonGerarEtiquetas.disabled = false;
      }
    }
  
  
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
  
    $('#myForm').submit(function(event) {
      event.preventDefault();
      var formData = new FormData(document.getElementById('myForm'));
  
      $.ajax({
          url: '{{ route('saveEnvio') }}',
          method: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(data) {
              console.log(data);
              Swal.fire({
                  title: 'Envio incluído com sucesso!',
                  text: '',
                  icon: 'success',
                  customClass: {
                      confirmButton: 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline-blue active:bg-blue-800',
                  },
                  buttonsStyling: false,
                  confirmButtonText: 'OK',
              }).then(function() {
                  // Recarrega a página após a confirmação do usuário
                  location.reload();
              });
          },
          error: function(xhr) {
  
              if (xhr.status == 500) {
                  alert(xhr.responseJSON.error);
              } else {
                  var errors = xhr.responseJSON.errors;
                  // Limpe os erros anteriores
                  $('.error-message').remove();
  
                  // Exiba os novos erros no formulário
                  $.each(errors, function(key, value) {
                      var errorField = $('[name="' + key + '"]');
                      errorField.addClass('is-invalid');
                      errorField.after('<span class="text-sm text-red-500">' + value[0] +
                          '</span>');
                  });
              }
  
  
          }
      });
    });
  
    async function carregarDadosSelectPeso() {
        const response = await fetch('/obter-dados-peso');
        const dados = await response.json();
        const select = document.getElementById('peso');
  
        // Limpar opções existentes
        select.innerHTML = '';
        const option = document.createElement('option');
        option.value = '0';
        option.text = 'Selecione um Peso';
        select.appendChild(option);
  
        // Adicionar novas opções
        for (const [id, nome] of Object.entries(dados)) {
            const option = document.createElement('option');
  
            option.value = id == 0 ? '0.300' : id;
            option.text = nome;
            select.appendChild(option);
        }
    }
  
    async function carregarDadosSelectEstado() {
  
        const response = await fetch('/obter-dados-estado');
        const dados = await response.json();
        const select = document.getElementById('estado');
  
        // Limpar opções existentes
        select.innerHTML = '';
        const option = document.createElement('option');
        option.value = '0';
        option.text = 'Selecione um Estado';
        select.appendChild(option);
  
        // Adicionar novas opções
        for (const [id, nome] of Object.entries(dados)) {
            const option = document.createElement('option');
  
            option.value = id;
            option.text = nome;
            select.appendChild(option);
        }
    }
  
    //buscar cep
    $(document).ready(function() {
        $('#CEP').on('change', function() {
            const cep = $(this).val();
            $.ajax({
                url: '/buscaCep/' + cep,
                type: 'GET',
                dataType: 'json',
                headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                success: function(data) {
                    // Preencha os campos restantes com as informações obtidas
                    $('#logradouro').val(data.logradouro);
                    $('#bairro').val(data.bairro);
                    $('#cidade').val(data.localidade);
                    $('#estado').val(data.uf);
                },
                error: function(error) {
                    console.error('Erro ao buscar CEP:', error);
                }
            });
        });
    });
  
    $(document).ready(function() {
        // Aplica a máscara ao campo de CEP
        $('#CEP').inputmask('99999-999');
    });
  
    $(document).on("click", "#btnExcluir", function() {
      
        var rowId = $(this).data("row-id"); // Obtém o ID da linha clicada
        Swal.fire({
            title: 'Você realmente deseja excluir este envio?',
            text: '',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then(function(result) {
  
            if (result.value) {
                $.ajax({
                    url: '/excluirEnvio/' + rowId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data) {
                            Swal.fire({
                                title: 'Envio deletado com sucesso!',
                                text: '',
                                icon: 'success',
                                customClass: {
                                    confirmButton: 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline-blue active:bg-blue-800',
                                },
                                buttonsStyling: false,
                                confirmButtonText: 'OK',
                            }).then(function() {
                                location.reload();
                            });
                        }
                    },
                    error: function(error) {
                        console.error('Erro a excluir o Envio:', error);
                    }
                });
            }
        });
    });
  
    $(document).ready(function() {
        $("#btnExcluirSelecionados").on("click", function() {
            var idsSelecionados = [];
  
            // Iterar sobre checkboxes marcados e coletar os IDs
            $("#id_envio:checked").each(function() {
                idsSelecionados.push($(this).data("id"));
            });
            Swal.fire({
                title: 'Você realmente deseja excluir este envio?',
                text: '',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: '{{ route('excluirEnviosSelecionados') }}',
                        data: {
                            ids: idsSelecionados
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            if (data) {
                                Swal.fire({
                                    title: 'Envio deletado com sucesso.',
                                    text: '',
                                    icon: 'success',
                                    customClass: {
                                        confirmButton: 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline-blue active:bg-blue-800',
                                    },
                                    buttonsStyling: false,
                                    confirmButtonText: 'OK',
                                }).then(function() {
                                    location.reload();
                                });
                            }
                        }
                    });
                }
            });
        });
    });
  
    $(document).on("click", "#btnEditar", function() {
        // Evento de clique no botão de edição
        var envioId = $(this).data("row-id");
  
        $.ajax({
            url: '/buscarEnvio/' + envioId,
            type: 'GET',
            success: function(response) {
                $('#destinatario').val(response.destinatario);
                $('#CEP').val(response.CEP);
                $('#logradouro').val(response.logradouro);
                $('#numero').val(response.numero);
                $('#complemento').val(response.complemento);
                $('#bairro').val(response.bairro);
                $('#cidade').val(response.cidade);
                $('#estado').val(response.estado);
                $('#email').val(response.email);
                $('#altura').val(response.altura);
                $('#comprimento').val(response.comprimento);
                $('#largura').val(response.largura);
                $('#seguro').val(response.seguro);
                $('#nota_fiscal').val(response.nota_fiscal);
                $('#AR').val(response.AR);
                $('#peso').val(response.peso);
                $('#forma_envio').val(response.forma_envio);
  
  
                $("#modal_incluir").attr("x-data", "{ open: true }");
                $("#modal_incluir1").attr("x-data", "{ open: true }");
  
                $("#modal_incluir").show();
                $("#modal_incluir1").show();
            },
            error: function(error) {
                console.log(error);
            }
        });
    });
  
    $(document).on("click", "#btnFechar", function() {
  
        $("#modal_incluir").attr("x-data", "{ open: false }");
        $("#modal_incluir1").attr("x-data", "{ open: false }");
  
        $("#modal_incluir").hide();
        $("#modal_incluir1").hide();
  
    });
  
    // Chamar a função ao carregar a página
    carregarDadosSelectPeso();
    carregarDadosSelectEstado();
  </script>
  