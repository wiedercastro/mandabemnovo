<div 
    class="justify-center items-center hidden" id="modal_aba_estatistica">
    <div>
      <div class="fixed inset-0 px-2 z-10 overflow-hidden flex items-center justify-center animate__animated animate__fadeIn">
        <div class="absolute inset-0 bg-gray-800 bg-opacity-75 transition-opacity"></div>
        <!-- Modal Content -->
        <div
          class="bg-white rounded-md shadow-xl overflow-hidden max-w-md w-full sm:w-96 md:w-1/2 lg:w-2/3 xl:w-1/3 z-50">
          <!-- Modal Header -->
          <div class="bg-[#25688B] text-white px-4 py-2 flex justify-between">
            <h2 class="text-sm font-semibold">
              Aba Estatísticas
            </h2>
            <svg
              onclick="fechaModalAbaEstatistica()"
              xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
              stroke="currentColor" class="w-6 h-6 cursor-pointer">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
          </div>

          <div class="p-4 font-light">
            <p>Nessa aba você pode ver todo o seu histórico da Manda Bem, isto é, o número de envios, o valor total e o desconto que você recebeu e economizou usando a nossa solução.<br><br>
              A barra de rolamento período a esquerda permite você filtrar e analisar o mês atual, a semana atual, ano atual, ano anterior ou customizar nas datas que preferir.<br><br>
          </div>
          <hr>
          <div class="flex flex-row-reverse p-4">
            <button onclick="fechaModalAbaEstatistica()" class="bg-blue-500 hover:bg-blue-600 text-white flex items-center font-bold text-xs rounded px-3 py-2">OK</button>
          </div>
        </div>
      </div>
    </div>
  </div>