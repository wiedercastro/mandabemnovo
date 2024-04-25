<x-app-layout>

    <div class="sm:w-5/6 w-full ml-auto lg:px-12 p-4">

        <div class="mt-16">

            <div class="block sm:flex flex-row-reverse">
                <form action="{{route('estatisticas_admin_index')}}" method="GET" class="mt-1 flex flex-col space-x-1 p-4 items-end border rounded bg-white">
                    <div class="flex items-center">
                        <div class="flex flex-col w-full">
                            <label for="" class="text-sm text-gray-700">Período</label>
                            <select onchange="toggleCustomFields()" id="periodo" name="periodo"
                                class="px-1 py-1 sm:w-96 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                                <option @if(request('periodo') == 'semana_atual') selected @endif value="semana_atual">Semana Atual</option>
                                <option @if(request('periodo') == 'mes_atual') selected @endif value="mes_atual">Mês Atual</option>
                                <option @if(request('periodo') == 'ano_atual') selected @endif value="ano_atual">Ano Atual</option>
                                <option @if(request('periodo') == 'ano_anterior') selected @endif value="ano_anterior">Ano Anterior</option>
                                <option @if(request('periodo') == 'customizado') selected @endif value="customizado">Customizado</option>
                            </select>
                        </div>

                        <div class="flex flex-col w-full ml-2">
                            <label for="cliente" class="text-sm text-gray-700">Cliente</label>
                            <input type="text" id="cliente" name="cliente"
                                class="px-1 py-1 sm:w-96 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                        </div>
                    </div>

                    <div id="exibeParaCustomizado" class="hidden w-full">
                        <div class="flex flex-col mt-1">
                            <label for="data_inicial" class="text-sm text-gray-700">Data Inicial</label>
                            <input id="data_inicial" type="date" name="data_inicial"
                                class="px-1 py-1 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                        </div>

                        <div class="flex flex-col mt-1">
                            <label for="data_final" class="text-sm text-gray-700">Data Final</label>
                            <input id="data_final" type="date" name="data_final"
                                class="px-1 py-1 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                        </div>
                    </div>
                    <button type="submit"
                        class="text-white font-bold text-xs
                            hover:bg-green-700 rounded mt-2
                            bg-green-600 px-2 py-1.5 w-22">
                        Filtrar
                    </button>
                </form>
            </div>


            <div class="w-full bg-white rounded-lg shadow mt-6">
                <div class="flex justify-between p-4 md:p-6 pb-0 md:pb-0">
                    <div class="flex items-center text-gray-500 font-bold">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                        </svg>
                        <h5 class="text-3xl">Geração</h5>
                    </div>
                    <div class="flex flex-col text-gray-700">
                        <div class="flex items-center">
                            <p class="">Clientes Cadastrados:</p>
                            <span class="ml-1 font-bold">{{$users}}</span>
                        </div>
                        <div class="flex items-center">
                            <p>Clientes Efetivos:</p>
                            <span class="ml-1 font-bold">0</span>
                        </div>
                        <div class="flex items-center">
                            <p>Envios:</p>
                            <span id="envios" class="ml-1 font-bold">{{$envios}}</span>
                        </div>
                        <div class="flex items-center">
                            <p>Coletas:</p>
                            <span class="ml-1 font-bold">{{$coletas}}</span>
                        </div>
                    </div>
                </div>
                <div id="labels-chart" class="px-2.5"></div>
                <div class="grid grid-cols-1 items-center border-gray-200 border-t justify-between mt-5 p-4 md:p-6 pt-0 md:pt-0">
                    <div class="flex justify-between items-center pt-5"></div>
                </div>
            </div>

            <div class="mt-8">

                <div class="flex items-center sm:mt-0 mt-8">
                    <h1 class="text-gray-500 font-bold text-4xl">Ranking de Clientes</h1>
                </div>

                <div class="overflow-x-auto">
                    <table
                        class="mt-4 w-full table-auto rounded shadow-lg text-sm text-left text-gray-500 border-collapse border-1">
                        <thead class="text-xs text-white font-bold uppercase bg-gray-500">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Ranking
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    ID
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Razão Social
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Valor
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Envios
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-white hover:bg-gray-100 border-b rounded-full font-light cursor-pointer">
                                <td class="px-6 py-4">
                                    1 º
                                </td>
                                <td class="px-6 py-4">
                                    24
                                </td>
                                <td class="px-6 py-4">
                                    ESPELHO MEU BY ALYCIA (Cadastro em: 16/04/2020)
                                </td>
                                <td class="px-6 py-4">
                                    R$ 2.690.475,79
                                </td>
                                <td class="px-6 py-4">
                                    50052
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</x-app-layout>


<script>

    function toggleCustomFields() {
        let periodoSelect = document.getElementById('periodo');
        let customFields = document.getElementById('exibeParaCustomizado');

        if (periodoSelect.value === 'customizado') {
            customFields.style.display = 'block'; // Mostra os campos
        } else {
            customFields.style.display = 'none'; // Esconde os campos
        }
    }


   function fetchData(callback) {
        const selectElement = document.getElementById("periodo");

        fetch(`estatisticas/pega-estatisticas?periodo=${encodeURIComponent(selectElement.value)}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro ao fazer requisição');
            }
            return response.json();
        })
        .then(data => {
            const diasMeses = data.envios.map(entry => entry.dia);
            const diasCount = data.envios.map(entry => entry.count);
            const coletas = data.coletas.map(entry => entry.count);

            const result = {
                diasMeses: diasMeses,
                diasCount: diasCount,
                coletas: coletas,
            };

            callback(result);
        })
        .catch(error => {
            console.error('Erro:', error);
        });
    }

    function useDiasMeses(diasMeses) {
        console.log(diasMeses);
        // Operações adicionais com diasMeses
    }

    // Chama fetchData com a função de callback
    //fetchData(useDiasMeses.diasMeses);

    function createChart(categories) {
        console.log(categories)

        const options = {
            // set the labels option to true to show the labels on the X and Y axis
            xaxis: {
                show: true,
                categories: categories.diasMeses,
                labels: {
                    show: true,
                    style: {
                        fontFamily: "Inter, sans-serif",
                        cssClass: 'text-xs font-normal fill-gray-500'
                    }
                },
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false,
                },
            },
            yaxis: {
                show: true,
                labels: {
                    show: true,
                    style: {
                        fontFamily: "Inter, sans-serif",
                        cssClass: 'text-xs font-normal fill-gray-500'
                    }
                }
            },
            series: [{
                    name: "Envios",
                    data: categories.diasCount,
                    color: "#1A56DB",
                },
                {
                    name: "Coletas",
                    data: categories.coletas,
                    color: "#16A34A",
                },
                {
                    name: "Clientes Efetivos",
                    data: categories.coletas,
                    color: "#6B7280",
                },
            ],
            chart: {
                sparkline: {
                    enabled: false
                },
                height: "100%",
                width: "100%",
                type: "area",
                fontFamily: "Inter, sans-serif",
                dropShadow: {
                    enabled: false,
                },
                toolbar: {
                    show: false,
                },
            },
            tooltip: {
                enabled: true,
                x: {
                    show: false,
                },
            },
            fill: {
                type: "gradient",
                gradient: {
                    opacityFrom: 0.55,
                    opacityTo: 0,
                    shade: "#1C64F2",
                    gradientToColors: ["#1C64F2"],
                },
            },
            dataLabels: {
                enabled: false,
            },
            stroke: {
                width: 6,
            },
            legend: {
                show: false
            },
            grid: {
                show: false,
            },
        }

        if (document.getElementById("labels-chart") && typeof ApexCharts !== 'undefined') {
            const chart = new ApexCharts(document.getElementById("labels-chart"), options);
            chart.render();
        }
    }

    fetchData(createChart);
</script>
