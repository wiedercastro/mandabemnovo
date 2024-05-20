<x-app-layout>

    <div class="w-full md:w-full lg:w-4/5 2xl:w-5/6 ml-auto lg:px-12 p-4 animate__animated animate__fadeIn">

        <div class="flex sm:flex-row justify-between flex-col mt-6">
            <div class="flex justify-center sm:hidden">
                <img src="{{ asset('images/logo_mandabem_az.png') }}" alt="" class="w-32" />
            </div>
            <div class="mt-6 flex justify-center sm:justify-start">
                <h1 class="text-gray-500 font-bold sm:text-4xl text-2xl">FAQ - Perguntas e Respostas</h1>
            </div>
        </div>

   
        <div class="flex sm:flex-row justify-between flex-col items-start sm:items-end mt-8 sm:mt-0">
           
            <form action="#" method="GET" class="mt-6 flex items-center w-full">
                <input type="text" id="filter" name="filter"
                    class="px-2 py-2 bg-white outline-none rounded-l w-full sm:w-2/5 bg-white border-gray-200 text-sm"
                    placeholder="Buscar por perguntas ou respostas...">
                <button class="bg-gray-500 text-sm text-white font-bold rounded-r-lg px-2 py-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <p class="ml-1">Buscar</p>
                </button>
            </form>
            <button 
                onclick="showModalFaq()"
                class="bg-green-600 hover:bg-green-700 text-sm text-white font-bold rounded px-2 py-1 flex items-center mt-8 sm:mt-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <p class="ml-1">Inserir</p>
            </button>
        </div>

        @if ($faqs->total() > 0)
            <div class="overflow-x-auto">
                <table
                    class="mt-4 overflow-x-automin-w-full ml-auto bg-white font-normal rounded shadow-lg
                    text-sm text-left text-gray-500 border-collapse border-1 cursor-pointer">
                    <thead class="text-xs text-white uppercase bg-blue-500 font-bold">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                ID
                            </th>
                            <th scope="col" class="px-9 py-3">
                                Categoria
                            </th>
                            <th scope="col" class="px-3 py-3">
                                Data de Criação
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Pergunta
                            </th>
                            <th scope="col" class="px-1 py-3">
                                Resposta
                            </th>
                            <th scope="col" class="px-1 py-3">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    @foreach ($faqs as $faq) 
                        <tbody>
                            <tr class="bg-white hover:bg-gray-50 border-b rounded-full font-light text-gray-700">
                                <td class="px-6 py-4">
                                    {{ $faq->id }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $faq->categorie->name }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $faq->date_insert }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $faq->question }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $faq->answer }}
                                </td>
                                <td class="x-6 py-4">
                                    <div class="flex cursor-pointer">
                                        <button onclick="showModalFaq({{$faq->id}})">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor"
                                                class="w-4 h-4 sm:w-5 sm:h-6 stroke-blue-600">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg>
                                        </button>
                                        
                                        <button onclick="modalDeletaFaq({{$faq->id}})">
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
            <nav class="mt-2">
                <ul class="inline-flex -space-x-px mt-2 text-xs">
                    @if ($faqs->currentPage() > 1)
                        <li>
                            <a href="?page={{ $faqs->currentPage() - 1 }}"
                                class="py-2 px-3 ml-0 leading-tight text-gray-500 bg-white 
                                rounded-l-lg border border-gray-300 hover:bg-gray-100 
                                hover:text-gray-700">
                                Anterior
                            </a>
                        </li>
                    @endif
                    @for ($i = 1; $i <= $faqs->lastPage(); $i++)
                        <li>
                            <a href="?page={{ $i }}"
                                class="py-2 px-3 {{ $faqs->currentPage() == $i ? 'text-blue-600 bg-blue-50' : 'text-gray-500 bg-white' }}
                                border border-gray-300 hover:bg-gray-100 
                                hover:text-gray-700">
                                {{ $i }}
                            </a>
                        </li>
                    @endfor
                    @if ($faqs->currentPage() < $faqs->lastPage())
                        <li>
                            <a href="?page={{ $faqs->currentPage() + 1 }}"
                                class="py-2 px-3 leading-tight text-gray-500 bg-white
                                rounded-r-lg border border-gray-300 hover:bg-gray-100 
                                hover:text-gray-700">
                                Próxima
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
        @else
            <div class="shadow rounded-md p-4 bg-white text-sm text-gray-600 mt-4 w-full">
                <h1>Nenhum registro encontrado!</h1>
                <a href="{{route('faq.index')}}" class="text-sm text-blue-500 flex items-center mt-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    <p class="ml-1">Voltar</p>
                </a>
            </div>
        @endif

        <x-modal-inserir-faq :categories="$categories"/>
        <x-modal-deletar-faq />

    </div>
</x-app-layout>

