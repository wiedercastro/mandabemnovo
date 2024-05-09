<x-app-layout>
    <div class="sm:w-5/6 w-full ml-auto lg:px-12 p-4 flex justify-center">
        <div class="sm:w-3/4 w-full bg-white shadow rounded p-4">
            <div class="flex items-center text-gray-500 font-bold text-4xl">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12.75 3.03v.568c0 .334.148.65.405.864l1.068.89c.442.369.535 1.01.216 1.49l-.51.766a2.25 2.25 0 0 1-1.161.886l-.143.048a1.107 1.107 0 0 0-.57 1.664c.369.555.169 1.307-.427 1.605L9 13.125l.423 1.059a.956.956 0 0 1-1.652.928l-.679-.906a1.125 1.125 0 0 0-1.906.172L4.5 15.75l-.612.153M12.75 3.031a9 9 0 0 0-8.862 12.872M12.75 3.031a9 9 0 0 1 6.69 14.036m0 0-.177-.529A2.25 2.25 0 0 0 17.128 15H16.5l-.324-.324a1.453 1.453 0 0 0-2.328.377l-.036.073a1.586 1.586 0 0 1-.982.816l-.99.282c-.55.157-.894.702-.8 1.267l.073.438c.08.474.49.821.97.821.846 0 1.598.542 1.865 1.345l.215.643m5.276-3.67a9.012 9.012 0 0 1-5.276 3.67m0 0a9 9 0 0 1-10.275-4.835M15.75 9c0 .896-.393 1.7-1.016 2.25" />
                </svg>
                <h1 class="ml-1">Mapa de Usuários</h1>
            </div>
            <form action="{{route('maps')}}" method="GET" class="border p-4 rounded mt-6">
                <div class="flex space-x-6">
                    <div class="flex flex-col w-full mt-4">
                        <label for="date_inicio" class="text-sm text-gray-700">A partir da data</label>
                        <input type="text" id="date_inicio" name="date_inicio" placeholder="dd/mm/yyyy" class="cliente px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                    </div>
    
                    <div class="flex flex-col w-full mt-4">
                        <label for="date_fim" class="text-sm text-gray-700">Até a data</label>
                        <input type="text" id="date_fim" name="date_fim" placeholder="dd/mm/yyyy" class="cliente px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                    </div>

                    <div class="flex flex-col w-full mt-4">
                        <label for="estado" class="text-sm text-gray-700">Selecione o estado</label>
                        <select id="estado" name="estado" class="px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                            @foreach ($listaEstados as $key => $estados)
                                <option value="{{$key}}">{{$estados}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex space-x-6">
                    <div class="flex flex-col w-full mt-4">
                        <label for="view_agencia_correios" class="text-sm text-gray-700">Deseja Visualizar as Agencias dos Correios?</label>
                        <select id="view_agencia_correios" name="view_agencia_correios" class="px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                            <option @if (request('view_agencia_correios') == 'sim') selected @endif value="sim">Sim</option>
                            <option @if (request('view_agencia_correios') == 'nao') selected @endif value="nao">Não</option>
                        </select>
                    </div>
    
                    <div class="flex flex-col w-full mt-4">
                        <label for="filter_xml" class="text-sm text-gray-700">Deseja Visualizar as Agencias Industriais?</label>
                        <select id="view_agencia_correios" name="view_agencia_industriais" class="px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                            <option @if (request('view_agencia_industriais') == 'sim') selected @endif value="sim">Sim</option>
                            <option @if (request('view_agencia_industriais') == 'nao') selected @endif value="nao">Não</option>
                        </select>
                    </div>
                </div>

                <hr class="mt-6">

                <div>
                    <button
                        type="submit"
                        id="buttonCreateManifestacao"
                        class="sm:text-sm text-xs bg-yellow-600 hover:bg-yellow-700 text-white font-bold px-2 py-1 mt-6 rounded flex items-center"> 
                        <i class="fa fa-search"></i>                   
                        <p class="ml-1">Buscar no mapa</p>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
