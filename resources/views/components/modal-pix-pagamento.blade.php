<div class="justify-center items-center hidden" id="modal_pix">
  <div>
    <!-- Open modal button -->
    <!-- Modal Overlay -->
    
    <div class="fixed inset-0 px-2 z-10 overflow-hidden flex items-start justify-center animate__animated animate__fadeIn">
      <div class="absolute inset-0 bg-gray-800 bg-opacity-75 transition-opacity"></div>
      
      <!-- Modal Content -->
      <div
        class="bg-white rounded-md shadow-xl overflow-hidden max-w-xl w-full sm:w-full md:w-2/3 lg:w-3/4 xl:w-2/3 z-50 mt-16">
        <!-- Modal Header -->
        <div class="bg-gray-100 text-gray-600 px-4 py-4 flex justify-between">
          <div class="flex items-center">  
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 stroke-green-500">
              <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
            </svg>              
            <h2 class="text-2xl ml-2 font-bold">
              SUCESSO
            </h2>
          </div>
          <svg
            onclick="fechaModalParaPixPagamento()"
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="w-8 h-8 stroke-gray-600 cursor-pointer">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
          </svg>
        </div>

        <hr>
        <div class="p-4">
          <div class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-100" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="flex-shrink-0 inline w-8 h-8 me-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
            </svg>                
            <span class="sr-only">Info</span>
            <div>
              <span class="font-medium">Prontinho!</span>
              Agora basta ler o QR code na opção PIX do seu
              Internet Bank e efetuar o pagamento, ou então
              copiar o código e colar na área <b>"Pix Copia e Cola"</b>
              também na opção PIX do seu Internet Bank.
            </div>
          </div>
          <div class="flex justify-center">
            <div class="flex flex-col">
              <div class="border w-full rounded flex justify-center">
                <img id="image_pix_pagamento" src="" alt="" class="w-64 h-64">
              </div>
              <input type="text" id="valorQrCode" value="" class="text-xs w-full mt-1">
              <div class="flex flex-col">
                <button
                  id="pix_copia_e_cola"
                  onclick="copiaPix()"
                  class="flex justify-center text-sm mt-2 bg-blue-500 hover:bg-blue-600 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">
                  <i class="fa fa-barcode" aria-hidden="true"></i>                 
                  <p class="ml-1">PIX copia e cola</p>
                </button>
                <div class="flex items-center justify-center mt-2 ml-2 hidden" id="exibeMsg">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 stroke-blue-500 font-bold">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                  </svg>  
                  <div class="flex items-center">
                    <p class="text-xs text-gray-600">Copiado com sucesso!</p>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 ml-1">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15.182 15.182a4.5 4.5 0 0 1-6.364 0M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Z" />
                    </svg>
                  </div>   
                </div> 
              
            </div>
            </div>
          </div>
        </div>
        <hr class="mt-6">
        <div class="flex flex-row-reverse mt-8 p-2">
          <button
            onclick="fechaModalParaPixPagamento()"
            class="text-sm bg-red-500 hover:bg-red-600 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>                                       
            <p>Fechar</p>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>

let exibeMsgCopiado = document.getElementById('exibeMsg');

const copiaPix = () => {
  exibeMsgCopiado.classList.remove('hidden'); 
  exibeMsgCopiado.classList.add('flex');

  let pixQrCodeText = document.getElementById('valorQrCode');
  pixQrCodeText.select();
  document.execCommand("copy");
}
</script>