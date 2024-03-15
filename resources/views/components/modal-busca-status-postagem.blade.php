<div class="justify-center items-center hidden" id="modal_status_postagem">
  <div>
    <!-- Open modal button -->
    <!-- Modal Overlay -->
    
    <div class="fixed inset-0 px-2 z-10 overflow-hidden flex items-start justify-center animate__animated animate__fadeIn">
      <div class="absolute inset-0 bg-gray-800 bg-opacity-75 transition-opacity"></div>
      
      <!-- Modal Content -->
      <div class="bg-white rounded-md shadow-xl overflow-hidden max-w-xl w-full sm:w-full md:w-2/3 lg:w-3/4 xl:w-2/3 z-50 mt-16">

        <!-- Modal Header -->
        <div class="bg-gray-100 text-gray-600 font-bold px-4 py-4 flex justify-between">
          <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
            </svg>                
            <h2 class="text-2xl ml-2 font-bold">
              Movimentações do Objeto
            </h2>
          </div>
          <svg
            onclick="fechaModalPostagem()"
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="w-8 h-8 cursor-pointer">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
          </svg>
        </div>

        <hr>
        <div class="p-4">
          <div class="flex items-center">
            <div class="w-full relative">
              <div class="absolute top-2 border border-1 border-green-500 w-full"></div>
              <div class="flex justify-between">
                <div class="flex flex-col items-center">
                  <span class="relative flex h-4 w-4">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-4 w-4 bg-green-500"></span>
                  </span>
                  <span class="text-xs text-gray-600 font-bold">13/03/2024 14:19</span>
                  <span class="text-xs text-gray-600">Entregue ao destinatário</span>
                  <div class="mt-2 flex flex-col justify-center items-center">
                    <span class="text-xs text-gray-600 font-bold">Local</span>
                    <span class="text-xs text-gray-600">Unidade de Distribuição</span>
                    <span class="text-xs text-gray-600">SAO PAULO/SP</span>
                  </div>
                </div>
                <div class="flex flex-col items-center">
                  <span class="relative flex h-4 w-4">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-4 w-4 bg-green-500"></span>
                  </span>
                  <span class="text-xs text-gray-600 font-bold">13/03/2024 14:19</span>
                  <span class="text-xs text-gray-600">Entregue ao destinatário</span>
                  <div class="mt-2 flex flex-col justify-center items-center">
                    <span class="text-xs text-gray-600 font-bold">Local</span>
                    <span class="text-xs text-gray-600">Unidade de Distribuição</span>
                    <span class="text-xs text-gray-600">SAO PAULO/SP</span>
                  </div>
                </div>
                <div class="flex flex-col items-center">
                  <span class="relative flex h-4 w-4">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-4 w-4 bg-green-500"></span>
                  </span>
                  <span class="text-xs text-gray-600 font-bold">13/03/2024 14:19</span>
                  <span class="text-xs text-gray-600">Entregue ao destinatário</span>
                  <div class="mt-2 flex flex-col justify-center items-center">
                    <span class="text-xs text-gray-600 font-bold">Local</span>
                    <span class="text-xs text-gray-600">Unidade de Distribuição</span>
                    <span class="text-xs text-gray-600">SAO PAULO/SP</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="flex items-center" style="margin-top: 70px;">
            <div class="w-full relative">
              <div class="absolute top-2 border border-1 border-green-500" style="width: calc(100% - 72px);"></div> <!-- 36px é a largura do ícone (24px) + algum espaço extra -->
              <div class="flex justify-between">
                <div class="flex flex-col items-center">
                  <span class="relative flex h-4 w-4">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-4 w-4 bg-green-500"></span>
                  </span>
                  <span class="text-xs text-gray-600 font-bold">13/03/2024 14:19</span>
                  <span class="text-xs text-gray-600">Entregue ao destinatário</span>
                  <div class="mt-2 flex flex-col justify-center items-center">
                    <span class="text-xs text-gray-600 font-bold">Local</span>
                    <span class="text-xs text-gray-600">Unidade de Distribuição</span>
                    <span class="text-xs text-gray-600">SAO PAULO/SP</span>
                  </div>
                </div>
                <div class="flex flex-col items-center">
                  <span class="relative flex h-4 w-4">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-4 w-4 bg-green-500"></span>
                  </span>
                  <span class="text-xs text-gray-600 font-bold">13/03/2024 14:19</span>
                  <span class="text-xs text-gray-600">Entregue ao destinatário</span>
                  <div class="mt-2 flex flex-col justify-center items-center">
                    <span class="text-xs text-gray-600 font-bold">Local</span>
                    <span class="text-xs text-gray-600">Unidade de Distribuição</span>
                    <span class="text-xs text-gray-600">SAO PAULO/SP</span>
                  </div>
                </div>
                <div class="flex flex-col items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="blink w-6 h-6 text-green-500">
                    <path d="M7.493 18.5c-.425 0-.82-.236-.975-.632A7.48 7.48 0 0 1 6 15.125c0-1.75.599-3.358 1.602-4.634.151-.192.373-.309.6-.397.473-.183.89-.514 1.212-.924a9.042 9.042 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75A.75.75 0 0 1 15 2a2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H14.23c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23h-.777ZM2.331 10.727a11.969 11.969 0 0 0-.831 4.398 12 12 0 0 0 .52 3.507C2.28 19.482 3.105 20 3.994 20H4.9c.445 0 .72-.498.523-.898a8.963 8.963 0 0 1-.924-3.977c0-1.708.476-3.305 1.302-4.666.245-.403-.028-.959-.5-.959H4.25c-.832 0-1.612.453-1.918 1.227Z" />
                  </svg>                  
                  <span class="text-xs text-gray-600 font-bold">13/03/2024 18:19</span>
                  <span class="text-xs text-gray-600">Objeto Postado</span>
                  <div class="mt-2 flex flex-col justify-center items-center">
                    <span class="text-xs text-gray-600 font-bold">Local</span>
                    <span class="text-xs text-gray-600">Unidade de Distribuição</span>
                    <span class="text-xs text-gray-600">SAO PAULO/SP</span>
                  </div>
                </div>
              </div>
            </div>
          </div>    
        </div>
        <hr>
        <div class="flex flex-row-reverse mt-2 p-2">
          <button
            onclick="fechaModalPostagem()"
            class="text-sm bg-blue-500 hover:bg-blue-600 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">                                     
            OK
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

 