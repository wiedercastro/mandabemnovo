<x-app-layout>
    <div class="sm:w-5/6 w-full ml-auto lg:px-12 p-4 flex justify-center items-center flex-col">
        <div class="sm:w-3/4 w-full bg-white shadow rounded p-4 mt-4">
            @if (!request('filter_cliente'))
                <form action="#" method="GET" class="form-container">
                    <div class="flex flex-col w-full mt-4">
                        <label for="filter_cliente" class="text-sm text-gray-700">Cliente</label>
                        <input onkeyup="buscaClientes(event)" required type="text" id="filter_cliente" name="filter_cliente" placeholder="Busque por um cliente" class="cliente px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                        <div class="bg-white border rounded mt-1 hidden flex flex-col h-96 overflow-x-auto resultDestinatarios"> 

                        </div>
                    </div>

                    <button
                        type="submit"
                        id="buttonCreateManifestacao"
                        class="sm:text-sm text-xs bg-blue-600 hover:bg-blue-700 text-white font-bold px-2 py-1 mt-2 rounded flex items-center"> 
                        <i class="fa fa-search"></i>                   
                        <p class="ml-1">Procurar</p>
                    </button>
                </form>
            @endif

            @if (request('filter_cliente'))
                <div>
                    <h1 class="text-2xl font-bold text-gray-600">Enviar Mensagem para: <b class="text-blue-600">{{ request('filter_cliente') }}</b></h1>
                    <div class="border mt-2 p-2 rounded">
                        <form action="#" method="GET" class="form-container">
                            <div class="flex flex-col w-full mt-4">
                                <label for="mensagem" class="text-2xl font-bold text-gray-600">Conteúdo</label>
                                <textarea 
                                    required 
                                    id="mensagem" 
                                    name="mensagem" 
                                    rows="6" 
                                    class="resize-none outline-none block p-2.5 w-full text-sm text-gray-900 shadow-sm rounded-lg border border-gray-300 shadow bg-white" 
                                    placeholder="Escreva sua mensagem e clique para enviar..."></textarea>
                            </div>
            
                            <div class="flex items-center">
                                <button
                                    type="submit"
                                    id="buttonCreateManifestacao"
                                    class="sm:text-sm text-xs bg-blue-600 hover:bg-blue-700 text-white font-bold px-2 py-1 mt-2 rounded flex items-center">                    
                                    <p class="ml-1">Enviar</p>
                                </button>
                                <a href="{{route('mensagem.index')}}" class="ml-1 sm:text-sm text-xs bg-red-600 hover:bg-red-700 text-white font-bold px-2 py-1 mt-2 rounded flex items-center">Voltar</a>
                            </div>
                        </form> 
                    </div>
                </div>
            @endif
        </div>

        <div class="w-full p-4 mt-8 overflow-x-auto">
            <div class="flex items-center text-gray-500 font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                </svg>                  
                <h5 class="text-3xl ml-1">Mensagens</h5>
            </div>
            <table
                class="min-w-full table-auto ml-auto bg-white font-normal rounded shadow-lg
                text-xs sm:text-sm text-left text-gray-500 border-collapse border-1 mt-2">
                <thead class="text-xs text-white uppercase bg-red-800">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Data
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Cliente
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Mensagem
                        </th>
                        <th scope="col" class="px-1 py-3">
                            Status
                        </th>
                        <th scope="col" class="px-1 py-3">
                            Visualizada
                        </th>
                        <th scope="col" class="px-1 py-3">
                            Autor Mensagem
                        </th>
                        <th scope="col" class="px-1 py-3">
                            Opções
                        </th>
                    </tr>
                </thead>
                @foreach ($messages as $message)
                    <tbody>
                        <tr class="bg-white border-b rounded-full font-light">
                            <td class="px-6 py-4 max-w-sm">
                                {{ $message->date_insert }}
                            </td>
                            <td class="px-6 py-4">
                                0
                            </td>
                            <td class="px-6 py-4 max-w-sm">
                                {{ $message->texto }}
                            </td>
                            <td class="px-2 py-2">
                                @if ($message->status === "APAGADO")    
                                    <span class="text-red-600">{{$message->status}}</span>
                                @else
                                    <span class="text-green-600">{{$message->status}}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                {{ $message->conf_leitura === 1 ? "SIM" : "NÃO" }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $message->user_id }}
                            </td>
                            <td class="px-2 py-2">
                                <div class="flex cursor-pointer">
        
                                    <button>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor"
                                            class="w-4 h-4 sm:w-5 sm:h-6 stroke-red-600">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                 @endforeach
            </table>
        </div>
    </div>
</x-app-layout>
