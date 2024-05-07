<x-app-layout>
    <div class="w-full md:w-full lg:w-4/5 2xl:w-5/6 ml-auto lg:px-12 p-4">
        <div class="flex justify-center sm:hidden">
            <img src="{{ asset('images/logo_mandabem_az.png') }}" alt="" class="w-32" />
        </div>

        <div class="bg-white p-10 shadow-md mt-10 sm:mt-0">
            <div class="border rounded p-4">
                <div class="flex items-center sm:text-4xl text-xl text-gray-500 font-bold">
                    <i class="fa fa-barcode"></i>
                    <h1 class="ml-1">Boletos</h1>
                </div>

                <hr class="mt-4">

                <div class="mt-4 p-4 mt-8 flex sm:flex-row flex-col">
                    <div class="border rounded p-4 sm:w-96 w-full sm:ml-4 ml-0 shadow text-xs sm:text-sm">
                        <form action="#" method="GET" class="mt-2 flex flex-col w-full">
                
                            <div class="flex flex-col w-full">
                                <label for="cliente" class="text-xs sm:text-sm text-gray-600 font-bold">Cliente</label>
                                <input required type="text" id="cobranca_cliente" name="cliente" placeholder="Digite o nome do cliente..." class="cliente id_cliente px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                            </div>
        
                            <div class="mt-2">
                                <button
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-2 py-1 rounded flex items-center text-xs sm:text-sm">
                                    <i class="fa fa-search"></i>
                                    <p class="ml-1">Buscar</p>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="border rounded p-4 sm:w-96 w-full sm:ml-4 ml-0 shadow h-full text-xs sm:text-sm">
                        <div class="flex justify-between">
                            <h4 class="font-light">Boletos Pagos ({{ $boletosPago->total }}):</h4>
                            <span class="text-gray-700 font-bold">R$ {{ $boletosPago->value }}</span>
                        </div>
                        <hr class="mt-4">
                        <div class="flex justify-between mt-4 font-light">
                            <h4>Boletos Pendente ({{ $boletosPendente->total }}):</h4>
                            <span>R$ {{ $boletosPendente->value }}</span>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto mt-6">
                    <table
                        class="mt-2 w-full table-auto text-xs sm:text-sm text-left text-gray-500 border-collapse overflow-x-auto border-1">
                        <thead class="text-xs text-white font-bold uppercase bg-[#008adb]">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Data
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Cliente
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Valor
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Pgto
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Crédito
                                </th>
                            </tr>
                        </thead>
                        @foreach ($boletos as $boleto)
                            <tbody>
                                <tr class="bg-white hover:bg-gray-100 border cursor-pointer">
                                    <td class="px-6 py-4">
                                        {{ $boleto->date_insert }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $boleto->cliente }}
                                    </td>
                                    <td class="px-6 py-4">
                                        R$ {{ $boleto->value }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($boleto->status !== "PAGO")
                                            <div class="2xl:inline flex items-center px-2 py-1 text-xs font-normal rounded-full text-yellow-700 gap-x-2 bg-yellow-100/60 border border-yellow-500">
                                                <i class="fa fa-hourglass-half"></i>
                                                <span class="text-xs">Pendente</span>
                                            </div>
                                        @else
                                            <div class="2xl:inline flex items-center px-2 py-1 text-xs font-normal rounded-full text-green-700 gap-x-2 bg-green-100/60 border border-green-500">
                                                <i class="fa fa-check"></i>
                                                <span class="text-xs">Pago</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-xs">Informação ainda estatitica</span>
                                    </td>
                                </tr>
                            </tbody>
                        @endforeach
                    </table>
                    <div class="mt-4">
                        {{ $boletos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>