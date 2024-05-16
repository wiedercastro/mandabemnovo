<x-app-layout>
    <div class="sm:w-5/6 w-full ml-auto lg:px-12 p-4 flex justify-center items-center flex-col">
        <div class="sm:w-3/4 w-full bg-white shadow rounded p-4">
            <div class="p-2">
                <button
                    onclick="modalIncluirNovoGrupoTaxa()"
                    type="submit"
                    class="sm:text-sm text-xs bg-blue-600 hover:bg-blue-700 text-white font-bold px-2 py-1 mt-6 rounded flex items-center"> 
                    <i class="fa fa-file"></i>                   
                    <p class="ml-1">INCLUIR NOVO</p>
                </button>
            </div>

            <x-modal-grupo-taxas :faixas="$faixas" />

            <div class="p-2 rounded">
                <div class="border rounded p-2 mt-2">
                    <div class="flex items-center text-xl sm:text-4xl text-gray-500 font-bold">
                        <i class="fa fa-list"></i>
                        <h5 class="ml-1">Grupos de Taxas</h5>
                    </div>
                    <div class="flex flex-col mt-6">
                        <div class="-m-1.5 overflow-x-auto">
                            <div class="p-1.5 min-w-full inline-block align-middle">
                                <div class="border overflow-hidden">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-center text-sm font-medium">ID</th>
                                                <th scope="col" class="px-6 py-3 text-center text-sm font-medium">Nome</th>
                                                <th scope="col" class="px-6 py-3 text-center text-sm font-medium">Tipo</th>
                                                <th scope="col" class="px-6 py-3 text-center text-sm font-medium">Situação</th>
                                                <th scope="col" class="px-6 py-3 text-center text-sm font-medium">Alteração</th>
                                                <th scope="col" class="px-6 py-3 text-center text-sm font-medium">Ações</th>
                                            </tr>
                                        </thead>
                                        @foreach ($grupos as $grupo)
                                            <tbody class="divide-y divide-gray-200">
                                                <tr class="text-center text-gray-800">
                                                    <td class="px-2 py-4 whitespace-nowrap text-sm font-medium">
                                                        {{$grupo->id}}
                                                    </td>
                                                    <td class="px-2 py-4 whitespace-nowrap text-sm">
                                                        @if ($grupo->application == 'PACMINI')
                                                            <strong><i class="fa fa-cube"></i>{{$grupo->name}}</strong>
                                                        @else   
                                                            <i class="fa fa-list"></i>{{$grupo->name}}
                                                        @endif
                                                    </td>
                                                    <td class="px-2 py-4 whitespace-nowrap text-sm">
                                                        {{$grupo->type === "FIX" ? "Fixo" : "Percentual ({$grupo->percent}%)"}}
                                                    </td>
                                                    <td class="px-2 py-4 whitespace-nowrap text-sm">
                                                        {{$grupo->status == 1 ? 'Habilitado' : 'Não habilitado'}}
                                                    </td>
                                                    <td class="px-2 py-4 whitespace-nowrap text-sm">
                                                        {{date('d/m/Y H:i:s', strtotime($grupo->date_update))}}
                                                    </td>
                                                    <td class="px-2 py-2">
                                                        <button class="text-xl bg-blue-500 hover:bg-blue-600 px-2 py-1 rounded text-white font-bold inline-flex items-center">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                
                                                        <button class="text-xl bg-red-500 hover:bg-red-600 px-2 py-1 rounded text-white font-bold inline-flex items-center">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
