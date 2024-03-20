<x-app-layout>
    <div class="w-5/6 ml-auto lg:px-12 shadow bg-white rounded">
        <h1 class="px-4 py-2 text-gray-500 font-bold text-2xl">Simulador para Cotação Manda Bem</h1>
        <hr class="border-gray-240 border-dashed mt-2">
        <div class="mt-4 px-4 py-2">

            <form id="simuladorCotacao" method="POST">
                @csrf
                <div class="mt-2 sm:flex">
                    <div class=" flex flex-col w-full">
                        <label for="origem" class="text-gray-600 sm:text-sm text-xs">CEP origem</label>
                        <input required id="cep_origem" name="cep_origem"
                            class="p-2 w-full border outline-none rounded border-gray-200 bg-white shadow">
                    </div>

                    <div class="flex flex-col w-full sm:ml-2">
                        <label for="destino" class="text-gray-600 sm:text-sm text-xs">CEP destino</label>
                        <input required id="cep_destino" name="cep_destino"
                            class="p-2 w-full border outline-none rounded border-gray-200 bg-white shadow">
                    </div>

                </div>

                <div class="mt-2 sm:flex">
                    <div class=" flex flex-col w-full">
                        <label for="peso" class="text-gray-600 sm:text-sm text-xs">Peso</label>
                        <select required id="peso" name="peso"
                            class="p-2 w-full border outline-none rounded border-gray-200 bg-white shadow text-gray-600">
                            {{-- <option disabled selected>Selecione:</option>
              @foreach ($pesosEnum as $peso)
                <option>{{ $peso->value }}</option>
              @endforeach --}}
                        </select>
                    </div>

                    <div class="flex flex-col w-full sm:ml-2">
                        <label for="valor_declaracao" class="text-gray-600 sm:text-sm text-xs">Valor Assegurado</label>
                        <input id="valor_declaracao" name="valor_declaracao" placeholder="Valor opcional"
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
                    <p>Medidas (Só preencha se a soma da altura, largura e comprimento do seu pacote for maior que <b>90
                            cm</b>).</p>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 stroke-red-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                        <p class="ml-1">Para Envio Mini as dimensões máximas aceitas <b>são 4 cm (A), 16 cm (L) e 24
                                cm (C)</b>.</p>
                    </div>
                </div>

                <hr class="border-gray-300 mt-6 border-dashed my-4">

                <div class="mt-6">
                    <div class="mt-2 sm:flex">
                        <div class=" flex flex-col w-full">
                            <label for="altura" class="text-gray-600 sm:text-sm text-xs">Altura</label>
                            <select required id="altura" name="altura"
                                class="p-2 w-full border outline-none rounded border-gray-200 bg-white shadow text-gray-600">
                                <option disabled selected>Selecione:</option>
                                @foreach ($alturaEnum as $altura)
                                    <option>{{ $altura->value }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex flex-col w-full sm:ml-4">
                            <label for="largura" class="text-gray-600 sm:text-sm text-xs">Largura</label>
                            <select required id="largura" name="largura"
                                class="p-2 w-full border outline-none rounded border-gray-200 bg-white shadow text-gray-600">
                                <option disabled selected>Selecione:</option>
                                @foreach ($largurasEnum as $largura)
                                    <option>{{ $largura->value }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class=" flex flex-col w-full sm:ml-4">
                            <label for="comprimento" class="text-gray-600 sm:text-sm text-xs">Comprimento</label>
                            <select required id="comprimento" name="comprimento"
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
                        class="flex w-64 items-center justify-center text-xs bg-red-600 hover:bg-red-900 text-white font-bold px-4 py-2 rounded mt-6"
                        id="login" type="submit">
                        <svg id="iconeCalcular" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5 ml-1">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008Zm0 2.25h.008v.008H8.25V13.5Zm0 2.25h.008v.008H8.25v-.008Zm0 2.25h.008v.008H8.25V18Zm2.498-6.75h.007v.008h-.007v-.008Zm0 2.25h.007v.008h-.007V13.5Zm0 2.25h.007v.008h-.007v-.008Zm0 2.25h.007v.008h-.007V18Zm2.504-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5Zm0 2.25h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V18Zm2.498-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5ZM8.25 6h7.5v2.25h-7.5V6ZM12 2.25c-1.892 0-3.758.11-5.593.322C5.307 2.7 4.5 3.65 4.5 4.757V19.5a2.25 2.25 0 0 0 2.25 2.25h10.5a2.25 2.25 0 0 0 2.25-2.25V4.757c0-1.108-.806-2.057-1.907-2.185A48.507 48.507 0 0 0 12 2.25Z" />
                        </svg>
                        <svg id="loadingCalcular" class="inline w-4 h-4 mr-3 animate-spin stroke-blue-200 hidden"
                            viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                fill="#E5E7EB" />
                            <path
                                d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                fill="currentColor" />
                        </svg>
                        <span id="loadingNome">Calcular</span>

                    </button>
                </div>

            </form>
            <hr class="mt-4">
            <div class="mt-6">
                <table id="tabelaCotacao"
                    class="min-w-full table-auto ml-auto bg-white font-normal rounded
          text-sm text-left text-gray-500 border-collapse overflow-x-auto border-1 hidden">
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
                    <tbody id="resultadoCotacao">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    $('#simuladorCotacao').submit(function(event) {
        event.preventDefault();
        var formData = new FormData(document.getElementById('simuladorCotacao'));

        var iconeCalcular = document.getElementById("iconeCalcular");
        iconeCalcular.classList.add("hidden");

        var loadingCalcular = document.getElementById("loadingCalcular");
        loadingCalcular.classList.remove("hidden");

        document.getElementById("loadingNome").innerHTML = "Calculando";


        $.ajax({
            url: '{{ route('simuladorCotacao') }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                var tabela = document.getElementById("tabelaCotacao");
                tabela.classList.remove("hidden");

                document.getElementById('resultadoCotacao').innerHTML = data;


                iconeCalcular.classList.remove("hidden");

                loadingCalcular.classList.add("hidden");

                document.getElementById("loadingNome").innerHTML = "Calcular";

            },
        });
    });

    async function carregarDadosSelectPeso() {
        const response = await fetch('/obter-dados-peso');
        const dados = await response.json();
        const select = document.getElementById('peso');

        // Limpar opções existentes
        select.innerHTML = '';
        const option = document.createElement('option');
        option.value = '0';
        option.text = 'Selecione um Peso';
        select.appendChild(option);

        // Adicionar novas opções
        for (const [id, nome] of Object.entries(dados)) {
            const option = document.createElement('option');

            option.value = id == 0 ? '0.300' : id;
            option.text = nome;
            select.appendChild(option);
        }
    }
    $(document).ready(function() {
    $('#valor_declaracao').inputmask('currency', {
        prefix: '', // Altere o prefixo conforme necessário
        alias: 'numeric',
        autoGroup: true,
        digits: 2,
        radixPoint: ",",
        groupSeparator: ".",
        allowMinus: false,
        rightAlign: false,
        numericInput: true, // Define entrada numérica da direita para a esquerda
        removeMaskOnSubmit: false
    });

    // Adiciona um evento de clique ao campo
    $('#valor_declaracao').click(function() {
        // Verifica se o valor é igual a zero
        if ($(this).val() === "0") {
            // Define o valor como null
            $(this).val(null);
        }
    });
});

    carregarDadosSelectPeso();
</script>
