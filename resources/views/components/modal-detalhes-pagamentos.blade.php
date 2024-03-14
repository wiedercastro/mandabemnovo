<div class="justify-center items-center hidden" id="modal-container">
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