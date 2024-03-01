<x-app-layout>
  {{--  INICIO MODAL ACOMPANHAMENTOS--}}
  <div 
    class="justify-center items-center hidden" id="modal-info-acompanhamentos">
    <div>
      <div class="fixed inset-0 px-2 z-10 overflow-hidden flex items-center justify-center animate__animated animate__fadeIn">
        <div class="absolute inset-0 bg-gray-800 bg-opacity-75 transition-opacity"></div>
        <!-- Modal Content -->
        <div
          class="bg-white rounded-md shadow-xl overflow-hidden max-w-md w-full sm:w-96 md:w-1/2 lg:w-2/3 xl:w-1/3 z-50">
          <!-- Modal Header -->
          <div class="bg-[#25688B] text-white px-4 py-2 flex justify-between">
            <h2 class="text-sm font-semibold">
              ABA ACOMPANHAMENTO
            </h2>
            <svg
              onclick="fechaModalComInformacoes()"
              xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
              stroke="currentColor" class="w-6 h-6 cursor-pointer">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
          </div>

          <div class="p-4 font-light">
            <p>Na Aba Acompanhamento você terá acesso ao gerenciamento de e-mails enviados para seus clientes acompanharem os envios e ao gerenciamento de crise.<br><br>
              No gerenciamento de e-mails você pode ativar e editar as mensagens que o seu cliente receberá por e-mail sobre o fluxo postal, são 4 situação chaves do fluxo:<br><br>
              
              <b>Objeto Postado, Objeto saiu para entrega, Entrega não pode ser efetuada e Objeto encontra-se para retirada.</b><br><br>
              
              Você pode ativar essas notificações e também editar o corpo da mensagem do e-mail enviado para o cliente.<br><br>
              
              Para o cliente receber o e-mail é preciso que, ao gerar o envio, você coloque o e-mail dele nas informações opcionais.<br><br>
              
              No gerenciamento de crise nós antecipamos e informamos quando algo der errado no fluxo postal como objeto roubado, extraviado ou quando a entrega não pode ser efetuada. Sempre que isso ocorrer um sinal de exclamação será indicado na aba para você tomar as providências cabíveis em relação ao ocorrido.</p>
          </div>
          <hr>
          <div class="flex flex-row-reverse p-4">
            <button onclick="fechaModalComInformacoes()" class="bg-blue-500 hover:bg-blue-600 text-white flex items-center font-bold text-xs rounded px-3 py-2">OK</button>
          </div>
        </div>
      </div>
    </div>
  </div>
 {{--  FINAL MODAL INFORMACOES ACOMPANHAMENTOS--}}

 {{--  INICIO MODAL DETALHES--}}
  <div 
    class="justify-center items-center hidden" id="modal-container">
    <div>
      <div class="fixed inset-0 px-2 z-10 overflow-hidden flex items-center justify-center animate__animated animate__fadeIn">
        <div class="absolute inset-0 bg-gray-800 bg-opacity-75 transition-opacity"></div>
        <!-- Modal Content -->
        <div
          class="bg-white rounded-md shadow-xl overflow-hidden max-w-md w-full sm:w-96 md:w-1/2 lg:w-2/3 xl:w-1/3 z-50">
          <!-- Modal Header -->
          <div class="bg-[#25688B] text-white px-4 py-2 flex justify-between">
            <h2 class="text-sm font-semibold">
              Visualizar detalhe
            </h2>
            <svg
              onclick="fechaModal()"
              xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
              stroke="currentColor" class="w-6 h-6 cursor-pointer">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
          </div>

          <form method="POST" action="#" id="submitForm">
            @csrf
            
            <input type="hidden" name="id" id="id">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" id="_token">

            <div class="p-4">
              <div class="flex items-center p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-100" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="flex-shrink-0 inline w-5 h-5 me-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>                
                <span class="sr-only">Info</span>
                <div>
                  <span class="font-medium">ATENÇÃO!</span> Não altere o texto entre < e >, pois é usado para associar as informações no email
                </div>
              </div>

              <div class="flex flex-col w-full mt-6">
                <label for="assunto" class="text-gray-600 text-sm">ASSUNTO</label>
                <input 
                  id="assunto"
                  name="assunto"
                  class="px-4 p-2 w-full border shadow outline-none rounded bg-white border-gray-200 text-gray-600 text-sm">
              </div>

              <div class="flex flex-col w-full mt-4">
                <label for="corpo_email" class="text-gray-600 text-sm">CORPO DO E-MAIL</label>
                <textarea 
                  required 
                  rows="14" 
                  id="corpo_email"
                  name="corpo_email"
                  class="resize-none outline-none block p-2.5 w-full text-sm text-gray-900 shadow rounded-lg border border-gray-300 bg-white overflow-y-auto" 
                  placeholder="Digite..."></textarea>
              </div>
            </div>

            <div>
              <div class="flex flex-row-reverse p-2">
                <button 
                  id="submitFormButton" 
                  class="bg-blue-500 hover:bg-blue-600 text-white flex items-center font-bold text-xs rounded px-2 py-1">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                  Salvar
                </button>
                <button  
                  type="button" 
                  onclick="fechaModal()"
                  class="mr-2 bg-red-500 hover:bg-red-600 text-white flex items-center font-bold text-xs rounded px-2 py-1">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                  </svg>                  
                  Cancelar
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
 {{--  FINAL MODAL DETALHES--}}

  <div class="w-5/6 ml-auto lg:px-12">
    <div class="flex justify-between">
      <div class="bg-white rounded-lg w-1/2 shadow">
        <div class="p-4 font-light">
          <h1 class="text-2xl text-gray-800 font-bold">Total</h1>
          <div class="flex justify-between text-sm text-gray-800">
            <div class="flex flex-col space-y-4 mt-2">
              <span class="">Fevereiro 2024</span>
              <span class="">Clientes c/ Postagens: 0</span>
            </div>
            <div class="flex flex-col space-y-4 mt-2"> 
              <span class="">Totais: 0 | Nuvem: 0 | LI : 0</span>
              <span class="">Hoje: 0 | Nuvem: 0 | LI: 0</span>
            </div>
          </div>
        </div>
      </div>
      <div class="flex text-blue-700 items-center">
        <p
          type="button"
          onclick="exibiModalComInformacoes()" 
          class="font-bold text-[#25688B] text-xl font-bold cursor-pointer">
          Como funciona essa aba
        </p>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="cursor-pointer w-8 h-8">
          <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
        </svg>                  
      </div>
  </div>
  <div class="mt-16">
    <h1 class="text-gray-500 font-bold text-3xl">Gerenciamento de Emails</h1>
    <table
      class="mt-2 w-2/3 table-auto font-normal rounded shadow-lg
      text-sm text-left text-gray-500 border-collapse overflow-x-auto border-1">
      <thead class="text-xs text-gray-700 uppercase bg-gray-300">
        <tr>
          <th scope="col" class="px-6 py-3">
             NOME
          </th>
          <th scope="col" class="px-6 py-3">
            OPÇÕES
          </th>
        </tr>
      </thead>
      @foreach ($acompanhamento_email as $acompanhamento)
        <tbody>
          <tr 
            class="bg-white hover:bg-gray-100 border-b rounded-full font-light cursor-pointer">
            <td class="px-6 py-4">
              {{$acompanhamento->name}}
            </td>
            <td class="px-4 py-4">
              <button 
                onclick="abreModal({{$acompanhamento->id}})"
                class="bg-blue-400 hover:bg-blue-500 border border-blue-400 rounded text-sm px-2 py-1 text-white font-bold">
                <div class="flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                  </svg>
                  <p class="ml-1">Editar</p>
                </div>
              </button>
            </td>
          </tr>
        </tbody>
      @endforeach
    </table>
  </div>
</x-app-layout>
