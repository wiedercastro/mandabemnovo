<x-app-layout>
  <div class="sm:w-5/6 w-full ml-auto lg:px-12 p-4">
    <div class="flex justify-center sm:hidden">
      <img src="{{asset('images/logo_mandabem_az.png')}}" alt="" class="w-32"/>
    </div>

    <div class="w-full bg-white p-10 shadow-md mt-10 sm:mt-0">
      <div class="flex items-center flex-col justify-center p-4 mb-4 sm:w-96 w-full text-sm text-green-800 border border-green-300 rounded-lg bg-green-50" role="alert">
        <h1 class="text-xl font-bold">Cobrança PayPal</h1>
        <div class="flex items-center font-bold">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
          </svg>          
          <p class="text-xl">Inativa</p>
        </div>
      </div>

      <div class="flex flex-row sm:flex-col justify-center">
        <div class="w-48">
          <h2 class="flex justify-center text-gray-800">Ativar cobranças</h2>
          <div class="bg-[#008adb] hover:bg-blue-500 rounded flex justify-center items-center py-1 cursor-pointer">
            <img src="{{asset('images/p_palpay.svg')}}" alt="" class="w-4 h-4">
            <img src="{{asset('images/text_palpay.svg')}}" alt="">
          </div>
          <div class="flex justify-between mt-1">
            <img src="{{asset('images/visa.svg')}}" alt="" class="w-9 h-9">
            <img src="{{asset('images/mastercard.svg')}}" alt="" class="w-9 h-9">
            <img src="{{asset('images/amex.svg')}}" alt="" class="w-9 h-9">
            <img src="{{asset('images/hiper.svg')}}" alt="" class="w-9 h-9">
            <img src="{{asset('images/elo.svg')}}" alt="" class="w-9 h-9">
          </div>
        </div>
      </div>
      <div class="mt-10 overflow-x-auto">
        <div class="flex items-center text-gray-500 font-bold">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
          </svg>
          <h1 class="text-3xl text">Minhas Cobranças</h1>
        </div>
        <hr>

        <table
          class="min-w-full table-auto ml-auto bg-white font-normal rounded mt-6
          text-sm text-left text-gray-500 border-collapse overflow-x-auto border-1">
          <thead class="text-xs text-white font-bold uppercase bg-[#008adb]">
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
            <tr class="bg-white hover:bg-gray-100 border rounded-full font-light">
              <td class="px-6 py-4">
                09/11/2023
              </td>
              <td class="px-6 py-4">
                <span class="text-red-500">Cobrança</span>
              </td>
              <td class="px-6 py-4">
                Crédito concedido por transferência (PIX) em 09/11/2023
              </td>
              <td class="px-2 py-2 text-blue-400 font-bold">
                1212asdasdas2323
              </td>
              <td class=" py-2 text-[#154864] font-bold">
                + R$ 10000,00
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</x-app-layout>



