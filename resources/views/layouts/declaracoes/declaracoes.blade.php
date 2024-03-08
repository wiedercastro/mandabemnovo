<x-app-layout>
    <div class="w-5/6 ml-auto lg:px-12" style="border: 0px solid red;">
        <div class="text-4xl" style="margin-top:-25px">
            <h1 style="color:#728189"><b>Declarações</b></h1>
        </div>
        <br>
        <div class=" w-full dark:bg-gray-800" style="border: 0px solid black;">
            <div class="w-full text-gray-900 dark:text-gray-100">

                <div class="w-full m-auto h-20 bg-white shadow-xl" style="border: 0px solid red">
                    <div class="w-11/12 flex m-auto" style="border:0px solid red">
                        <div class="w-full mt-3.5 pr-4">
                            <input
                                class="w-full m-auto pl-10 text-base placeholder-gray-500 border rounded-full focus:shadow-outline"
                                type="search" placeholder="Buscar por Nome, Destinatário, Etiqueta...">
                        </div>
                        <div class="w-3/12 mt-3.5">
                            <button class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4"
                                style="background-color: #2d6984;">
                                <i class="fa fa-search" aria-hidden="true" style="border:0px solid red"></i>
                                Buscar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <br>

        </div>
        <div  class="mx-auto overflow-x-auto">
            <table
                class="min-w-full table-auto ml-auto bg-white text-sm text-left text-gray-500 dark:text-gray-400 border-collapse">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Id
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Origem
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Data
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Valor
                        </th>
                        <th scope="col" class="px-1 py-3">
                            Destino
                        </th>
                        <th scope="col" class="px-1 py-3">
                            <span class="sr-only">Itens</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($envios as $envio)
                        <tr class="bg-white border-b dark:bg-blue-800 dark:border-gray-700 hover:bg-blue-100 dark:hover:bg-blue-100 rounded-full"
                            id="linha_{{ $envio->id }}">

                            <th class="px-6 py-4 rounded-s-lg" style="color:#2d6984"
                                id="idenvio_{{ $envio->id }}">
                                <button id="btnInfoCol" data-id ="{{ $envio->id }}">
                                    
                                    MB{{ $envio->id }}
                                </button>
                            </th>
                            <td class="px-6 py-4">
                                Teste
                            </td>
                            <td class="px-6 py-4">
                                <i class="fa fa-clock-ow" aria-hidden="true"></i> 24/12/2023 <br>
                            </td>
                            <td class="px-6 py-4 font-medium text-green-950">
                                R$ {{ $envio->total }}
                            </td>
                            <td class="px-2 py-2">
                                <button dir="ltr" type="button" class="w-1/2 text-white bg-cyan-700 hover:bg-cyan-800 focus:ring-4 focus:ring-cyan-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-cyan-600 dark:hover:bg-cyan-700 focus:outline-none dark:focus:ring-cyan-800">{{ $envio->qte }} 
                                    @if ($envio->qte>1) 
                                        Destinatários
                                    @else
                                        Destinatário
                                    @endif
                            </td>
                           

                        </tr>

                        <tr>
                            <td colspan="6">
                                <div id="detalhes_{{ $envio->id }}" style="display: none">

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="w-full m-auto py-4">
                {{ $envios->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
