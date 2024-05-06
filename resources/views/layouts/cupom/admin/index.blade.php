<x-app-layout>
    <div class="sm:w-5/6 w-full ml-auto lg:px-12 p-4 sm:0">
        <div class="flex justify-center sm:hidden">
            <img src="{{ asset('images/logo_mandabem_az.png') }}" alt="" class="w-32" />
        </div>

        <x-modal-criar-cupom :afiliados="$afiliados"/>

        <div class="bg-white p-10 shadow-md mt-10 sm:mt-0">

            <div class="flex justify-between">
                <h1 class="text-gray-500 font-bold sm:text-4xl text-xl mt-10 sm:mt-0">Cupom de Desconto</h1>
                <div>
                    <button
                        type="submit"
                        id="botaoCriarCupom"
                        onclick="abreModalParaCriarCupom()"
                        class="text-sm bg-green-600 hover:bg-green-700 text-white font-bold px-2 py-1 mt-6 rounded flex items-center"> 
                        <i class="fa fa-plus"></i>                   
                        <p class="ml-1">Criar cupom</p>
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table
                    class="mt-2 w-full table-auto
            sm:text-sm text-xs text-left text-gray-500 border-collapse overflow-x-auto border-1">
                    <thead class="text-xs text-white font-bold uppercase bg-[#008adb]">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Cupom
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Tipo
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Valor
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Qnt Usados
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Duração
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Qnt Envios
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Afiliado
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Ativação
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Opções
                            </th>
                        </tr>
                    </thead>
                    @foreach ($listCupons as $list)
                        <tbody>
                            <tr class="bg-white hover:bg-gray-100 border cursor-pointer">
                                <td class="px-6 py-4 flex flex-col">
                                    {{ $list->name }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $list->type }}
                                </td>
                                <td class="px-6 py-4">
                                    R$ {{ $list->valor }}
                                </td>
                                <td class="px-6 py-4">
                                    1 
                                </td>
                                <td class="px-6 py-4">
                                    {{ $list->duracao }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $list->num_envios }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($list->status === 0)
                                        <div class="inline px-3 py-1 text-xs font-normal rounded-full text-red-500 gap-x-2 bg-red-100/60">
                                            INATIVO
                                        </div>
                                    @else
                                        <div class="inline px-3 py-1 text-xs font-normal rounded-full text-green-500 gap-x-2 bg-green-100/60">
                                            ATIVO
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    {{ $list->afiliados ? $list->afiliados : 'Não Vinculado'}}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($list->status === 1)
                                        <button onclick="desativarCupom({{$list->status}})" class="bg-red-500 hover:bg-red-600 px-2 py-1 rounded flex items-center text-xs text-white font-bold">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                                            </svg>                 
                                            <p class="ml-1">Desativar</p>
                                        </button>
                                    @else
                                        <button onclick="ativarCupom({{$list->status}})" class="bg-green-500 hover:bg-green-600 px-2 py-1 rounded flex items-center text-xs text-white font-bold">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1 0 12.728 0M12 3v9" />
                                            </svg>  
                                            <p class="ml-1">Ativar</p>
                                        </button>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <button class="bg-red-500 hover:bg-red-600 px-2 py-1 rounded">
                                        <i class="fa fa-trash text-white"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
