{{-- @props([

]) --}}

<div x-show="open" @click.away="open = false" id="modal_incluir"
    class="w-full fixed inset-0 overflow-y-auto animate__animated animate__fadeIn">
    <div
        class="w-full flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0 bg-gray-800 bg-opacity-75 transition-opacity">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div x-show="open" id="modal_incluir1"
            class="sm:ml-56 ml-0 inline-block align-bottom bg-white 
          rounded-lg text-left overflow-hidden shadow-xl 
          transform transition-all sm:my-8 sm:align-middle sm:w-1/2">
            <!-- Conteúdo do modal aqui -->
            <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4 bg-white">
                <div class="sm:flex sm:items-start">
                    <!-- Título do modal -->
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <div class="flex justify-between">
                            <h2 class="text-3xl mb-6 text-gray-500 font-bold">Incluir envio</h2>
                            <svg @click="open = false" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="w-6 h-6 cursor-pointer">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <div class="w-full mx-auto bg-white p-8 rounded-md mt-8 border">
                            <h4 class="text-2xl mb-6 text-gray-600">Dados do envio</h4>
                            <form id="myForm" action="" method="POST">
                                @csrf
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" id="_token">
                                <div class="flex flex-col w-full flex justify-start items-start">
                                    <div class="w-full mb-4 mr-4">
                                        <label for="remetente"
                                            class="flex justify-start items-start text-gray-700 text-xs sm:text-sm font-bold">Remetente</label>
                                        <input type="text" name="remetente" id="remetente"
                                            class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500"
                                            value="Principal">
                                    </div>

                                    <div class="w-full mb-4 mr-4">
                                        <label for="destinatario"
                                            class="flex justify-start items-start text-gray-700 text-xs sm:text-sm font-bold">Destinatário</label>
                                        <input type="text" 
                                          
                                          onkeyup="buscaPorDestinatario(event)"
                                          name="destinatario" id="destinatario"
                                          placeholder="Busque por um destinatário..."
                                          class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                        <div class="bg-white border rounded mt-1 hidden flex flex-col h-96 overflow-x-auto" id="resultDestinatarios"> 
                                         
                                        </div>
                                    </div>

                                    <div class="w-full mb-4">
                                        <label for="forma_envio"
                                            class="flex justify-start items-start text-gray-700 text-xs sm:text-sm font-bold">Forma
                                            de envio</label>
                                        <select name="forma_envio" id="forma_envio"
                                            class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                            <option value="">Selecione</option>
                                            <option value="SEDEX">Sedex</option>
                                            <option value="PAC">Pac</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="w-full flex sm:flex-row flex-col justify-start items-start">
                                    <div class="sm:w-1/2 w-full mb-4 mr-4">
                                        <label for="CEP"
                                            class="block text-gray-700 text-xs sm:text-sm font-bold flex justify-start items-start">CEP</label>
                                        <input type="text" name="CEP" id="CEP" value="{{ old('CEP') }}"
                                            class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500"
                                            placeholder="Digite seu CEP">
                                    </div>

                                    <div class="w-full mb-4 mr-4">
                                        <label for="logradouro"
                                            class="block text-gray-700 text-xs sm:text-sm font-bold flex justify-start items-start">Logradouro</label>
                                        <input type="text" name="logradouro" id="logradouro"
                                            class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                    </div>

                                    <div class="sm:w-1/2 w-full mb-4">
                                        <label for="cidade"
                                            class="block text-gray-700 text-xs sm:text-sm font-bold flex justify-start items-start">Cidade</label>
                                        <input type="text" name="cidade" id="cidade"
                                            class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                    </div>
                                </div>

                                <div class="flex w-full">
                                    <div class="w-full mb-4 mr-4">
                                        <label for="complemento"
                                            class="block text-gray-700 text-xs sm:text-sm font-bold">Complemento</label>
                                        <input type="text" name="complemento" id="complemento"
                                            class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                    </div>

                                    <div class="w-full mb-4 mr-4">
                                        <label for="bairro"
                                            class="block text-gray-700 text-xs sm:text-sm font-bold">Bairro</label>
                                        <input type="text" name="bairro" id="bairro"
                                            class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                    </div>

                                    <div class="w-full mb-4">
                                        <label for="numero"
                                            class="block text-gray-700 text-xs sm:text-sm font-bold">Número</label>
                                        <input type="text" name="numero" id="numero"
                                            class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                    </div>
                                </div>

                                <div class="flex w-full">
                                    <div class="w-full mb-4 mr-4">
                                        <label for="estado"
                                            class="block text-gray-700 text-xs sm:text-sm font-bold">Estado</label>
                                        <select name="estado" id="estado" value="{{ old('estado') }}"
                                            class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                            {{-- @foreach ($categories as $category) --}}
                                            <option value="">Selecione</option>
                                            {{-- @endforeach --}}
                                        </select>
                                    </div>

                                    <div class="w-full mb-4 mr-4">
                                        <label for="peso"
                                            class="block text-gray-700 text-xs sm:text-sm font-bold">Peso</label>
                                        <select name="peso" id="peso" value="{{ old('peso') }}"
                                            class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                            {{-- @foreach ($categories as $category) --}}
                                            <option value="">Selecione</option>
                                            {{-- @endforeach --}}
                                        </select>
                                    </div>
                                </div>

                                <hr class="border-gray-400 border-dashed mt-4">

                                <h4 class="text-2xl mb-6 text-gray-600 mt-10">Campos opcionais</h4>

                                <div class="flex flex-col w-full flex justify-start items-start">

                                    <label for="email"
                                        class="block text-gray-700 text-xs sm:text-sm font-bold">E-mail</label>
                                    <input type="text" name="email" id="email"
                                        class="w-full text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">

                                    <label for="seguro"
                                        class="block text-gray-700 text-xs sm:text-sm font-bold mt-2">Seguro</label>
                                    <input type="text" name="seguro" id="seguro"
                                        class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                </div>


                                <div class="flex flex-col w-full flex justify-start items-start mt-2">
                                    <label for="nota_fiscal"
                                        class="block text-gray-700 text-xs sm:text-sm font-bold">Nota fiscal</label>
                                    <input type="text" name="nota_fiscal" id="nota_fiscal"
                                        class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500"
                                        placeholder="">

                                    <label for="AR"
                                        class="block text-gray-700 text-xs sm:text-sm font-bold mt-2">AR</label>
                                    <select name="AR" id="AR"
                                        class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                        <option value="N">Não</option>
                                        <option value="S">Sim</option>
                                    </select>
                                </div>

                                <div class="mt-4">
                                    <p class="text-xs sm:text-sm text-gray-500 font-bold">Medidas</p>
                                    <p class="text-xs text-gray-500">(Só preencha se a soma da altura, largura e
                                        comprimento do seu pacote for maior que 90 cm)</p>
                                    <p class="text-xs sm:text-sm text-red-500">* Atenção: Não é permitido o envio de
                                        caixas em formato cilíndrico.</p>
                                    <p class="text-xs sm:text-sm text-red-500">** Atenção: Nenhuma medida sozinha pode
                                        ultrapassar 70cm.</p>
                                </div>
                                <hr class="mt-4 border-dashed">

                                <div class="flex flex-col justify-start items-start sm:flex-row w-full mt-6">
                                    <div class="w-full mb-4 mr-4">
                                        <label for="altura"
                                            class="block text-gray-700 text-xs sm:text-sm font-bold flex justify-start items-start">Altura</label>
                                        <select name="altura" id="altura"
                                            class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                            <option value="">Selecione</option>
                                            @foreach ($alturas as $altura)
                                                <option value="{{ $altura->value }}">{{ $altura->value }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="w-full mb-4 mr-4">
                                        <label for="comprimento"
                                            class="block text-gray-700 text-xs sm:text-sm font-bold flex justify-start items-start">Comprimento</label>
                                        <select name="comprimento" id="comprimento"
                                            class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                            <option value="">Selecione</option>
                                            @foreach ($comprimentos as $comprimento)
                                                <option value="{{ $comprimento->value }}">{{ $comprimento->value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="flex flex-col justify-start items-start sm:flex-row w-full">
                                    <div class="w-full mb-4 mr-4">
                                        <label for="largura"
                                            class="block text-gray-700 text-xs sm:text-sm font-bold flex justify-start items-start">Largura</label>
                                        <select name="largura" id="largura"
                                            class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                            <option value="">Selecione</option>
                                            @foreach ($larguras as $largura)
                                                <option value="{{ $largura->value }}">{{ $largura->value }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="w-full mb-4 mr-4">
                                        <label for="obs"
                                            class="block text-gray-700 text-xs sm:text-sm font-bold flex justify-start items-start">Observação</label>
                                        <input type="text" name="obs" id="obs"
                                            class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500"
                                            placeholder="">
                                    </div>
                                </div>

                                <div class="flex justify-end text-xs mt-4">
                                    <button type="submit"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold px-2 py-1 rounded flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m4.5 12.75 6 6 9-13.5" />
                                        </svg>
                                        <p class="ml-1">Salvar envio</p>
                                    </button>
                                    <button type="button" @click="open = false" id="btnFechar"
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold px-2 py-1 rounded flex items-center ml-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
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
        </div>
    </div>
</div>


<script>
const buscaPorDestinatario = async (event) => {
  let textoDigitado = event.target.value

  const res = await fetch(`http://localhost:8989/buscaDestinatario?text=${encodeURIComponent(textoDigitado)}`, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json'
    },
  });
  
  if (!res.ok) {
    throw new Error('Erro ao fazer requisição');
  }

  const resJson = await res.json();
  let destinatarioList = document.getElementById('resultDestinatarios');

  if (resJson.destinatario.length === 0 || textoDigitado.length === 0) {
    destinatarioList.classList.add('hidden');
  } else {
    destinatarioList.classList.remove('hidden');
  }

  let lista = ''

  resJson.destinatario.forEach(item => {
    lista += 
    `<ul>
      <li class="bg-white-50 hover:bg-blue-500 hover:text-white hover:font-bold text-xs px-2 py-1 cursor-pointer">${item.destinatario}</li>
    </ul>`;
  });

  destinatarioList.innerHTML = lista;
}
</script>