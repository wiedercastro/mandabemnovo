<div class="justify-center items-center hidden" id="modal-info-acompanhamentos">
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

        <div class="p-4 font-light text-xs sm:text-base">
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