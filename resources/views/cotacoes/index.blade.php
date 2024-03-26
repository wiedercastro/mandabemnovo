<x-app-layout>
  <div class="p-4">
    <div class="sm:w-5/6 w-full ml-auto lg:px-12 shadow bg-white rounded">
      <h1 class="px-4 py-2 text-gray-500 font-bold text-2xl">Simulador para Cotação Manda Bem</h1>
      <hr class="border-gray-240 border-dashed mt-2">
      <div class="mt-4 px-4 py-2">

        <form method="POST">
          <div class="mt-2 sm:flex">
            <div class=" flex flex-col w-full">
              <label for="origem" class="text-gray-600 sm:text-sm text-xs">CEP origem</label>
              <input 
                required
                id="cep_origem" 
                name="cep_origem" 
                class="p-2 w-full border outline-none rounded border-gray-200 bg-white shadow">
            </div>

            <div class="flex flex-col w-full sm:ml-2 mt-4 sm:mt-0">
              <label for="destino" class="text-gray-600 sm:text-sm text-xs">CEP destino</label>
              <input 
                required
                id="cep_destino"
                name="cep_destino"
                class="p-2 w-full border outline-none rounded border-gray-200 bg-white shadow">
            </div>
            
          </div>

          <div class="mt-2 sm:flex">
            <div class=" flex flex-col w-full">
              <label for="peso" class="text-gray-600 sm:text-sm text-xs">Peso</label>
              <select 
                required
                id="peso"
                name="peso"
                class="p-2 w-full border outline-none rounded border-gray-200 bg-white shadow text-gray-600">
                <option disabled selected>Selecione:</option>
                @foreach ($pesosEnum as $peso)
                  <option>{{ $peso->value }}</option>
                @endforeach
              </select>
            </div>

            <div class="flex flex-col w-full sm:ml-2 mt-4 sm:mt-0">
              <label for="valor_assegurado" class="text-gray-600 sm:text-sm text-xs">Valor Assegurado</label>
              <input 
                required
                id="valor_assegurado"
                name="valor_assegurado"
                placeholder="Valor opcional"
                class="p-2 w-full border outline-none rounded border-gray-200 bg-white shadow">
            </div>
          </div>

          <div class="mt-12">
            <div>
              <span class="font-bold text-[#25688B]">Valor Assegurado:</span>
            </div>
            <div class="flex items-center">
              <span class="font-bold text-[#25688B]">PAC: </span>
              <p class="ml-1 text-gray-500 text-sm">Entre R$ 20,50 e R$ 3000,00</p>
            </div>
            <div class="flex items-center">
              <span class="font-bold text-[#25688B]">SEDEX: </span>
              <p class="ml-1 text-gray-500 text-sm">Entre R$ 20,50 e R$ 3000,00</p>
            </div>
            <div class="flex items-center">
              <span class="font-bold text-[#25688B]">Envio Mini: </span>
              <p class="ml-1 text-gray-500 text-sm">Entre R$ 20,50 e R$ 3000,00</p>
            </div>
          </div>

          <hr class="border-gray-300 mt-6 border-dashed my-4">

          <div class="text-sm text-gray-500">
            <p>Medidas (Só preencha se a soma da altura, largura e comprimento do seu pacote for maior que <b>90 cm</b>).</p>
            <div class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 stroke-red-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
              </svg>           
              <p class="ml-1">Para Envio Mini as dimensões máximas aceitas <b>são 4 cm (A), 16 cm (L) e 24 cm (C)</b>.</p>
            </div>
          </div>

          <hr class="border-gray-300 mt-6 border-dashed my-4">

          <div class="mt-6">
            <div class="mt-2 sm:flex">
              <div class=" flex flex-col w-full">
                <label for="altura" class="text-gray-600 sm:text-sm text-xs">Altura</label>
                <select 
                  required
                  id="altura"
                  name="altura"
                  class="p-2 w-full border outline-none rounded border-gray-200 bg-white shadow text-gray-600">
                  <option disabled selected>Selecione:</option>
                  @foreach ($alturaEnum as $altura)
                    <option>{{ $altura->value }}</option>
                  @endforeach
                </select>
              </div>

              <div class="flex flex-col w-full sm:ml-4 mt-4 sm:mt-0">
                <label for="largura" class="text-gray-600 sm:text-sm text-xs">Largura</label>
                <select 
                  required
                  id="largura"
                  name="largura"
                  class="p-2 w-full border outline-none rounded border-gray-200 bg-white shadow text-gray-600">
                  <option disabled selected>Selecione:</option>
                  @foreach ($largurasEnum as $largura)
                    <option>{{ $largura->value }}</option>
                  @endforeach
                </select>
              </div>

              <div class=" flex flex-col w-full sm:ml-4 mt-4 sm:mt-0">
                <label for="comprimento" class="text-gray-600 sm:text-sm text-xs">Comprimento</label>
                <select 
                  required
                  id="comprimento"
                  name="comprimento"
                  class="p-2 w-full border outline-none rounded border-gray-200 bg-white shadow text-gray-600">
                  <option disabled selected>Selecione:</option>
                  @foreach ($comprimentoEnum as $comprimento)
                    <option>{{ $comprimento->value }}</option>
                  @endforeach
                </select>
              </div>

            </div>
          </div>

          <div class="flex flex-row-reverse">
            <button
              class="flex sm:w-64 w-full items-center justify-center text-xs bg-red-600 hover:bg-red-900 text-white font-bold px-4 py-2 rounded mt-6"
              id="login" type="submit">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-5 h-5 ml-1">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008Zm0 2.25h.008v.008H8.25V13.5Zm0 2.25h.008v.008H8.25v-.008Zm0 2.25h.008v.008H8.25V18Zm2.498-6.75h.007v.008h-.007v-.008Zm0 2.25h.007v.008h-.007V13.5Zm0 2.25h.007v.008h-.007v-.008Zm0 2.25h.007v.008h-.007V18Zm2.504-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5Zm0 2.25h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V18Zm2.498-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5ZM8.25 6h7.5v2.25h-7.5V6ZM12 2.25c-1.892 0-3.758.11-5.593.322C5.307 2.7 4.5 3.65 4.5 4.757V19.5a2.25 2.25 0 0 0 2.25 2.25h10.5a2.25 2.25 0 0 0 2.25-2.25V4.757c0-1.108-.806-2.057-1.907-2.185A48.507 48.507 0 0 0 12 2.25Z" />
              </svg>
              Calcular
            </button>
          </div>

        </form>
        <hr class="mt-4">

        <div class="mt-6 overflow-x-auto">
          <table
            class="min-w-full table-auto ml-auto bg-white font-normal rounded
            text-sm text-left text-gray-500 border-collapse overflow-x-auto border-1">
            <thead class="text-xs text-gray-700 border uppercase bg-gray-200">
              <tr>
                <th scope="col" class="px-6 py-3">
                  CEP ORIGEM
                </th>
                <th scope="col" class="px-6 py-3">
                  CEP DESTINO
                </th>
                <th scope="col" class="px-6 py-3">
                  PESO
                </th>
                <th scope="col" class="px-1 py-3">
                  FORMA ENVIO
                </th>
                <th scope="col" class="px-1 py-3">
                  PRAZO
                </th>
                <th scope="col" class="px-1 py-3">
                  BALCÃO
                </th>
                <th scope="col" class="px-1 py-3">
                  DESCONTO
                </th>
                <th scope="col" class="px-1 py-3">
                  TOTAL
                </th>
              </tr>
            </thead>
            <tbody>
              <tr class="bg-white hover:bg-gray-100 border rounded-full font-light">
                <td class="px-6 py-2">
                  39480000
                </td>
                <td class="px-6 py-4">
                  07940140
                </td>
                <td class="px-2 py-2">
                  De 300g a 1Kg
                </td>
                <td dir="rtl" class="py-2 rounded-s-lg">
                  SEDEX
                </td>
                <td dir="rtl" class="py-2 rounded-s-lg">
                  4 Dia(s)
                </td>
                <td dir="rtl" class="py-2 rounded-s-lg">
                  R$ 58,4
                </td>
                <td dir="rtl" class="py-2 rounded-s-lg">
                  R$ 12,12
                </td>
                <td dir="rtl" class="py-2 rounded-s-lg">
                  R$ 46,28
                </td>
              </tr>
            </tbody>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>