<x-app-layout>
  <div class="p-4 sm:p-0 ml-auto lg:px-12 sm:w-5/6 w-full sm:ml-6 lg:ml-80">

    <div class="flex justify-center sm:hidden">
      <img src="{{asset('images/logo_mandabem_az.png')}}" alt="" class="w-32"/>
    </div>
    
    <div class="mt-4 sm:mr-0">
      <x-card-pagamentos/>
    </div>

    <div class="mt-6">
      <h1 class="text-gray-500 font-bold text-3xl text">Pagamentos</h1>
    </div>
    <div class="flex flex-col sm:flex-row h-full sm:space-x-4 space-x-0 mt-2">
      <div class="p-4 bg-white w-full rounded-md shadow-md flex flex-col justify-between">
        <div>
          <h1 class="text-xl font-bold text-[#25688B]">Transferência Bancária</h1>
          <div class="mt-2 text-gray-600 text-sm">
            <p class="font-light">Essa é a nossa conta <b class="font-bold">Itaú</b>!</p>
            <div class="flex font-light">
              <p class="font-bold text-gray-500">Agencia:</p>
              <p class="ml-1">0413</p>
            </div>
            <div class="flex font-light">
              <p class="font-bold text-gray-500">Conta:</p>
              <p class="ml-1">99825-3</p>
            </div>
            <div class="flex font-light">
              <p class="font-bold text-gray-500">Número do Banco:</p>
              <p class="ml-1">
                BANCO ITAU 341 MANDA BEM
              </p>
            </div>
            <div class="flex font-light">
              <p>INTERMEDIAÇÔES LIMITADA LTDA</p>
            </div>
            <div class="flex font-light">
              <p class="font-bold text-gray-500">CNPJ:</p>
              <p class="ml-1">27.347.642/0001-18</p>
            </div>
            <hr class="mt-4">
            <p class="font-light mt-4">Depois de fazer a transferência basta incluir o comprovante no link abaixo que colocaremos o crédito o mais rápido possível.</p>
          </div>
        </div>
        <div class="flex flex-row-reverse">
          <button
            class="bg-[#25688B] hover:bg-[#154864] text-white font-bold px-2 py-1 rounded flex items-center text-sm">    
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
            </svg>                                                                       
            <p class="ml-1">Incluir comprovante</p>
          </button>
        </div>
      </div>

      <div class="p-4 bg-white w-full rounded-md shadow-md flex flex-col justify-between sm:mt-0 mt-6">
        <div>
          <h1 class="text-xl font-bold text-[#25688B]">Transferência Mercado Pago</h1>
          <div class="text-gray-600 text-sm">
            <p class="font-light mt-4">Em sua conta do Mercado Pago clique em: "Enviar dinheiro" para:</p>
            <p class="mt-6 font-bold text-gray-500">marcos@mandabem.com.br</p>
            <p class="mt-6 font-light ">Depois é só incluir o compravante no link abaixo que colocaremos o crédito o mais rápido possível.</p>
          </div>
        </div>

        <div class="flex flex-row-reverse sm:mt-0 mt-4 ">
          <button
            class="bg-[#25688B] hover:bg-[#154864] text-white font-bold px-2 py-1 rounded flex items-center text-sm">    
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
            </svg>                                                                       
            <p class="ml-1">Incluir comprovante</p>
          </button>
        </div>
      </div>

      <div class="w-full flex flex-col sm:mt-0 mt-6">
        <div class="bg-white  flex-1 p-4 rounded-md shadow-md">
          <h1 class="text-xl font-bold text-[#25688B]">Pix</h1>
          <form action="" class="mt-4">
            <div class="flex flex-col">
              <label for="" class="text-sm text-gray-700">Informe o valor</label>
              <input type="text"  class="text-gray-700 shadow p-1 w-48 rounded outline-none border-gray-200 focus:border-gray-200">
            </div>
            </form>
            <p class="text-xs text-gray-500">Liberação em até 5 minutos!</p>
            <div class="flex flex-row-reverse mt-2">
              <button
                class="bg-[#25688B] hover:bg-[#154864] text-white font-bold px-2 py-1 rounded flex items-center text-sm">    
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 13.5 9.375v-4.5Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75ZM6.75 16.5h.75v.75h-.75v-.75ZM16.5 6.75h.75v.75h-.75v-.75ZM13.5 13.5h.75v.75h-.75v-.75ZM13.5 19.5h.75v.75h-.75v-.75ZM19.5 13.5h.75v.75h-.75v-.75ZM19.5 19.5h.75v.75h-.75v-.75ZM16.5 16.5h.75v.75h-.75v-.75Z" />
              </svg>
              <p class="ml-1">Gerar Qrcode</p>
              </button>
            </div>
        </div>
        <div class="bg-white flex-1 p-4 rounded-md shadow-md sm:mt-4 mt-6">
          <h1 class="text-xl font-bold text-[#25688B]">Boleto</h1>
          <form action="" class="mt-4">
            <div class="flex flex-col">
            <label for="" class="text-sm text-gray-700">Informe o valor</label>
            <input type="text"  class="text-gray-700 shadow p-1 w-48 rounded outline-none border-gray-200 focus:border-gray-200">
            </div>
          </form>
          <p class="text-xs text-gray-500">Meus boletos pagos</p>
          <div class="flex flex-row-reverse mt-2">
            <button
              class="bg-[#25688B] hover:bg-[#154864] text-white font-bold px-2 py-1 rounded flex items-center text-sm">    
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
              </svg>                                                            
              <p class="ml-1">Gerar boleto</p>
            </button>
          </div>
        </div>
      </div>
    </div>

    {{-- INICIO COBRANÇAS --}} 
    <div class="mt-6 overflow-x-auto">
      <h1 class="text-gray-500 font-bold text-3xl text">Cobranças</h1>
      <table
        class="min-w-full table-auto ml-auto bg-white font-normal rounded shadow-lg
        text-sm text-left text-gray-500 border-collapse overflow-x-auto border-1">
        <thead class="text-xs text-gray-700 uppercase bg-gray-300">
          <tr>
            <th scope="col" class="px-6 py-3">
              DATA
            </th>
            <th scope="col" class="px-6 py-3">
              TIPO
            </th>
            <th scope="col" class="px-6 py-3">
              DESCRIÇÃO
            </th>
            <th scope="col" class="px-1 py-3">
                ID TRANSAÇÃO
            </th>
            <th scope="col" class="px-1 py-3">
                TOTAL
            </th>
          </tr>
        </thead>
        <tbody>
          @foreach ($cobrancas as $cobranca)
            <tr class="bg-white hover:bg-gray-100 border-b rounded-full font-light">
              <td class="px-6 py-4">
                {{$cobranca->date }}
              </td>
              <td class="px-6 py-4">
                {{$cobranca->tipo }}
              </td>
              <td class="px-6 py-4">
                {{$cobranca->description }}
              </td>
              <td class="px-2 py-2 text-blue-400 font-bold">
                {{$cobranca->payment_id }}
              </td>
              <td class=" py-2 text-[#154864] font-bold">
                + R$ {{ $cobranca->value }}
              </td>
            </tr>
            @endforeach
        </tbody>
      </table>
    </div>
      {{-- FINAL COBRANÇAS --}} 

    {{-- INICIO TRANSFERENCIAS --}} 
    <div class="mt-6 overflow-x-auto">
      <h1 class="text-gray-500 font-bold text-3xl text">Transferências em aberto</h1>
      <table
        class="min-w-full table-auto ml-auto bg-white font-normal rounded shadow-lg
        text-sm text-left text-gray-500 border-collapse overflow-x-auto border-1">
        <thead class="text-xs text-gray-700 uppercase bg-gray-300">
          <tr>
            <th scope="col" class="px-6 py-3">
              DATA
            </th>
            <th scope="col" class="px-6 py-3">
              TIPO DE PAGAMENTO
            </th>
            <th scope="col" class="px-6 py-3">
              VALOR SOLICITADO
            </th>
            <th scope="col" class="px-1 py-3">
              COMPROVANTE
            </th>
            <th scope="col" class="px-1 py-3">
              STATUS
            </th>
          </tr>
        </thead>
        <tbody>
        {{--   @foreach ($cobrancas as $cobranca) --}}
            <tr class="bg-white hover:bg-gray-100 border-b rounded-full font-light">
              <td class="px-6 py-4">
                data
              </td>
              <td class="px-6 py-4">
                tipo
              </td>
              <td class="px-6 py-4">
                valor
              </td>
              <td class="px-2 py-2 text-blue-400 font-bold">
                teste
              </td>
              <td class=" py-2 text-[#154864] font-bold">
                teste
              </td>
            </tr>
    {{--         @endforeach --}}
        </tbody>
      </table>
    </div>

    {{-- FINAL TRANSFERENCIAS --}} 
  </div>
</x-app-layout>



