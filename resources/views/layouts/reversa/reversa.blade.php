<x-app-layout>

  <div class="w-5/6 ml-auto lg:px-12">
    <div class="text-4xl">
      <h1 class="text-gray-500 font-bold">Reversa</h1>
    </div>
    <div class="w-full mt-6 ">
      <div class="w-full text-gray-900">
        <div class="flex w-full m-auto p-4 bg-white rounded shadow-xl ">
          <div class="w-11/12 flex ml-3.5 flex items-center">
            <div class="w-80">
              <label for="category_id" class="block text-sm font-bold text-gray-500">
                Sede que irá receber
              </label>
              <select name="category_id" id="category_id" class="px-1 py-2 w-full border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600">
                  <option value="">Principal</option>
                  <option value="">GS INFORMEGAMES PR</option>
                  <option value="">Remetente rio</option>
              </select>
            </div>
          </div>

          <div class="w-3/12 flex text-blue-700 items-center">
              <label for="category_id" class="font-bold text-[#25688B] text-xl font-bold">
                Como funciona essa aba
              </label>
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="cursor-pointer w-8 h-8">
                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
              </svg>                  
          </div>
        </div>
      </div>

      <div class="w-full mx-auto bg-white p-8 rounded-md mt-8 shadow">
        <div class="w-5/6 mx-auto bg-white p-12 border shadow rounded-md mt-8">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
            <!-- Coluna 1 -->
            <div class="mr-8">
              <h2 class="text-2xl font-semibold mb-6 text-[#25688B]">Dados da Reversa</h2>

              <div class="w-full mb-4 mr-4">
                <label for="campo1" class="block text-gray-500 font-bold text-sm">Cliente que devolverá</label>
                <input placeholder="Digite o cliente que devolverá..." type="text" name="campo1" id="campo1" class="px-1 py-2 w-full border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600">
              </div>

              <div class="w-full mb-4 mr-4">
                <label for="category_id" class="block text-gray-500 font-bold text-sm">Forma de envio</label>
                <select name="category_id" id="category_id" class="px-1 py-2 w-full border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600">
                  <option value="">Selecione</option>
                  <option value="">Sedex</option>
                  <option value="">Pac</option>
                </select>
              </div>

              <div class="w-full mb-4 mr-4">
                <label for="cep" class="block text-gray-500 font-bold text-sm">CEP</label>
                <input placeholder="Digite seu cep..." type="text" name="cep" id="cep" class="px-1 py-2 w-full border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600">
              </div>

              <div class="w-full mb-4 mr-4">
                <label for="delivery_address" class="block text-gray-500 font-bold text-sm">Logradouro</label>
                <input placeholder="Digite o logradouro..." type="text" name="delivery_address" id="delivery_address" class="px-1 py-2 w-full border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600">
              </div>

              <div class="w-full mb-4">
                <label for="category_id" class="block text-gray-500 font-bold text-sm">Número</label>
                <input placeholder="Digite o número..." type="text" name="delivery_address" id="delivery_address" class="px-1 py-2 w-full border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600">
              </div>

              <div class="w-full mb-4 mr-4">
                <label for="cep" class="block text-gray-500 font-bold text-sm">Complemento</label>
                <input placeholder="Digite o complemento..." type="text" name="cep" id="cep" class="px-1 py-2 w-full border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600" placeholder="">
              </div>

              <div class="w-full mb-4 mr-4">
                <label for="delivery_address" class="block text-gray-500 font-bold text-sm">Bairro</label>
                <input placeholder="Digite o bairro..." type="text" name="delivery_address" id="delivery_address" class="px-1 py-2 w-full border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600">
              </div>

              <div class="w-full mb-4">
                <label for="category_id" class="block text-gray-500 font-bold text-sm">Cidade</label>
                <input placeholder="Digite a cidade..." type="text" name="delivery_address" id="delivery_address" class="px-1 py-2 w-full border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600">
              </div>

              <div class="w-full mb-4 mr-4">
                <label for="cep" class="block text-gray-500 font-bold text-sm">Estado</label>
                <select name="category_id" id="category_id" class="px-1 py-2 w-full border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600">
                  <option value="">Selecione</option>
                </select>
              </div>

              <div class="w-full mb-4 mr-4">
                <label for="delivery_address" class="block text-gray-500 font-bold text-sm">Peso</label>
                <select name="category_id" id="category_id" class="px-1 py-2 w-full border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600">
                  <option value="">Selecione</option>
                </select>
              </div>
            </div>

            <!-- Coluna 2 -->
            <div class="ml-8">
              <h2 class="text-2xl font-semibold mb-6 text-[#25688B]">Campos Opcionais</h2>
              <div class="w-full mb-4 mr-4">
                <label for="title" class="block text-gray-500 font-bold text-sm">E-mail</label>
                <input placeholder="Digite o e-mail..." type="text" name="title" id="title" class="px-1 py-2 w-full border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600">
              </div>

              <div class="w-full mb-4 mr-4">
                <label for="delivery_address" class="block text-gray-500 font-bold text-sm">Seguro</label>
                <input placeholder="Digite o seguro..." type="text" name="delivery_address" id="delivery_address" class="px-1 py-2 w-full border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600">
              </div>

              <div class="w-full mb-4 mr-4">
                <label for="cep" class="block text-gray-500 font-bold text-sm">Nota fiscal</label>
                <input placeholder="Digite a nota fiscal..." type="text" name="cep" id="cep" class="px-1 py-2 w-full border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600" placeholder="">
              </div>

              <div class="w-full mb-4 mr-4">
                <label for="delivery_address" class="block text-gray-500 font-bold text-sm">AR</label>
                <select name="category_id" id="category_id" class="px-1 py-2 w-full border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600">
                  <option value="">Não</option>
                  <option value="">Sim</option>
                </select>
              </div>

              <hr>
              {{-- AVISO!!!!!!! --}}
              <div class="mt-4">
                <p class="text-sm text-gray-500 font-bold">Medidas</p>
                <p class="text-xs text-gray-500">(Só preencha se a soma da altura, largura e comprimento do seu pacote for maior que 90 cm)</p>
                <p class="text-sm text-red-500">* Atenção: Não é permitido o envio de caixas em formato cilíndrico.</p>
                <p class="text-sm text-red-500">** Atenção: Nenhuma medida sozinha pode ultrapassar 70cm.</p>
              </div>
              {{-- FINAL AVISO!!!!!!! --}}

              <div class="w-full mb-4 mr-4 mt-4">
                <label for="cep" class="block text-gray-500 font-bold text-sm">Altura</label>
                <select name="category_id" id="category_id" class="px-1 py-2 w-full border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600">
                  <option value="">Selecione</option>
                </select>
              </div>

              <div class="w-full mb-4 mr-4">
                <label for="delivery_address" class="block text-gray-500 font-bold text-sm">Comprimento</label>
                <select name="category_id" id="category_id" class="px-1 py-2 w-full border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600">
                  <option value="">Selecione</option>
                </select>
              </div>

              <div class="w-full mb-4 mr-4">
                <label for="cep" class="block text-gray-500 font-bold text-sm">Largura</label>
                <select name="category_id" id="category_id" class="px-1 py-2 w-full border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600">
                  <option value="">Selecione</option>
                </select>
              </div>

              <div class="w-full mb-4 mr-4">
                <label for="delivery_address" class="block text-gray-500 font-bold text-sm">Observação</label>
                <textarea 
                  required 
                  id="delivery_address" 
                  rows="3" 
                  class="resize-none px-1 py-2 w-full border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600" 
                  placeholder="Digite alguma observação..."></textarea>
              </div>
            </div>
          </div>

          <!-- Outros campos do formulário -->
          <hr class="border-gray-300 border-dashed my-4"">

          <div class="flex justify-end mt-4">
            <button
              class="bg-blue-500 hover:bg-blue-700 text-white font-bold px-2 py-1 rounded flex items-center text-sm">    
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
              </svg>                                       
              <p class="ml-1">Gerar reversa</p>
            </button>
            <button
              class="bg-red-500 hover:bg-red-700 text-white font-bold px-2 py-1 rounded flex items-center ml-2 text-sm">    
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
              </svg>                   
              <p class="ml-1">Remover</p>
            </button>
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
