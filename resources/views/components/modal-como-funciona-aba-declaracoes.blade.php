<div class="justify-center items-center hidden" id="modal-info-detalhes">
  <div>
    <div class="fixed inset-0 px-2 z-10 overflow-hidden flex items-center justify-center animate__animated animate__fadeIn">
      <div class="absolute inset-0 bg-gray-800 bg-opacity-75 transition-opacity"></div>
      <!-- Modal Content -->
      <div
        class="bg-white rounded-md shadow-xl overflow-hidden max-w-md w-full sm:w-96 md:w-1/2 lg:w-2/3 xl:w-1/3 z-50">
        <!-- Modal Header -->
        <div class="bg-[#25688B] text-white px-4 py-2 flex justify-between">
          <h2 class="text-sm font-semibold">
            Aba Declarações
          </h2>
          <svg
            onclick="fechaModalComInformacoesDetalhes()"
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="w-6 h-6 cursor-pointer">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
          </svg>
        </div>

        <div class="p-4 font-light">
          <p>O objetivo da aba declarações é gerar as declarações de conteúdo de forma rápida e prática.<br><br>
            
            A declaração de conteúdo é um documento obrigatório para quem não emite nota fiscal. Para gerar você precisa gerar uma coleta antes, depois que a coleta for gerada a declaração estará disponível aqui.<br><br>
            
            Basta clicar no conjunto de envios, incluir o cpf do destinatário, o nome do item, quantidade e valor, depois de feito isso é só clicar em gerar declarações. Logo após gerada elas estarão disponíveis para impressão na aba das coletas.<br><br>
          
        </div>
        <hr>
        <div class="flex flex-row-reverse p-4">
          <button onclick="fechaModalComInformacoesDetalhes()" class="bg-blue-500 hover:bg-blue-600 text-white flex items-center font-bold text-xs rounded px-3 py-2">OK</button>
        </div>
      </div>
    </div>
  </div>
</div>