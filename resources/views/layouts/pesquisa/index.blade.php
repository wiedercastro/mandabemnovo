<x-app-layout>
    <div class="sm:w-5/6 w-full ml-auto lg:px-12 p-4 flex justify-center">
        <div class="sm:w-5/6 w-full bg-white shadow rounded p-4">
            <div class="flex items-center">
                <h1 class="text-gray-500 font-bold text-2xl sm:text-4xl">Apuração Pesquisa Indicação Clientes</h1>
                <div class="flex items-center ml-4">
                    <button
                        id="btn-refresh"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold px-2 py-1 rounded flex items-center text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>           
                        <p class="ml-1">Atualizar</p>
                    </button>
                </div>
            </div>

            <hr class="mt-4">
            
            <div class="flex justify-between mt-10 space-x-8">
                <div class="w-full" id="grafico">

                </div>
                <div class="w-full">
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 border">
                            <thead class="text-xs text-gray-700 uppercase border">
                                <tr>
                                    <th scope="col" class="px-6 py-3 border-r">
                                        Valor da Indicação
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Qtde Clientes
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data->lista as $lista => $valor)
                                    <tr class="bg-white border hover:bg-gray-100">
                                        <td class="px-6 py-4 border-r">
                                            {{ $lista }}
                                        </td>
                                        <td class="px-6 py-4 border-r">
                                            @if ($data->numero_respostas > 0)
                                                @php
                                                    $perc = number_format((100 * $valor) / $data->numero_respostas, 0, '.', '');
                                                @endphp
                                                <span class="font-bold text-gray-700"> {{ $valor }}</span>
                                                <small> 
                                                    ({{ $perc }}%)
                                                </small>
                                            @else
                                                <strong>
                                                    {{ $valor }}<small> (0%)</small>
                                                </strong>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6 border p-4 rounded">
                        <div class="flex items-center">
                            <b class="text-2xl font-bold text-gray-600">{{$data->numero_respostas}}</b> <p class="text-gray-600 text-base ml-2">clientes responderam à pesquisa</p>
                        </div>
                        <div class="flex items-center mt-2">
                            <b class="text-2xl font-bold text-red-600">{{$data->numero_respostas_nulas}}</b> <p class="text-red-600 text-base ml-2">clientes optaram por não responder</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>


<script>

    $(function () {

        $('#btn-refresh').click(function () {
            $('#btn-refresh').html('<i class="fa fa-cog fa-spin"></i> Atualizando');
            $('#btn-refresh').attr('disabled', true);
            location.reload();
        });

        setInterval(function () {
            $('#btn-refresh').html('<i class="fa fa-cog fa-spin"></i><p class="ml-1 text-sm">Atualizando</p>');
            $('#btn-refresh').attr('disabled', true);
            location.reload();
        }, 60000);

        new Morris.Donut({
            element: 'grafico',

            data: <?= json_encode($data->resumo['chart']) ?>,
            formatter: function (y, data) {
                var perc = number_format((100 * y) / <?= $data->numero_respostas ?>, 0, '.', '');
                return  y + ' clientes (' + perc + '%)';
            },
        });
    });

</script>