<x-app-layout>
    <div class="sm:w-5/6 w-full ml-auto lg:px-12 p-4 flex justify-center items-center flex-col">
        <div class="sm:w-3/4 w-full bg-white shadow rounded p-4">

            <div class="p-2 rounded">
                <div class="flex items-center text-xl sm:text-3xl text-gray-500 font-bold">
                    <i class="fa fa-users"></i>
                    <h5 class="ml-1">Ações/Usuários Cadastrados</h5>
                </div>
                <div class="flex flex-col mt-6">
                    <div class="-m-1.5 overflow-x-auto">
                        <div class="p-1.5 min-w-full inline-block align-middle">
                            <div class="border overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium uppercase">TAG</th>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium uppercase">Quantidade</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr>
                                            <td class="px-2 py-4 whitespace-nowrap text-xs sm:text-sm font-medium text-gray-800">
                                                <div
                                                    class="2xl:inline flex items-center px-2 py-1 text-xs rounded-full text-white font-bold gap-x-2 bg-red-500">
                                                    <i class="fa fa-tag"></i>
                                                    <span class="text-xs">alcance_facebook</span>
                                                </div>
                                            </td>
                                            <td class="px-2 py-4 whitespace-nowrap text-xs sm:text-sm text-gray-800">
                                                <b class="font-bold">48717</b> Cadastro
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="sm:w-3/4 w-full bg-white shadow rounded p-4 mt-4 overflow-x-auto">
            <div class="border rounded p-2">
                <div class="flex flex-col">
                    <div class="-m-1.5 overflow-x-auto">
                        <div class="p-1.5 min-w-full inline-block align-middle">
                            <div class="border overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium uppercase">TAG</th>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium uppercase">Envios Por Campanhas</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr>
                                            <td class="px-2 py-4 whitespace-nowrap text-xs sm:text-sm font-medium text-gray-800">
                                                <div
                                                    class="2xl:inline flex items-center px-2 py-1 text-xs rounded-full text-white font-bold gap-x-2 bg-red-500">
                                                    <i class="fa fa-tag"></i>
                                                    <span class="text-xs">alcance_facebook</span>
                                                </div>
                                            </td>
                                            <td class="px-2 py-4 whitespace-nowrap text-xs sm:text-sm text-gray-800">
                                                <b class="font-bold">48717</b> Envios
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="mt-8">
                <div class="flex items-center text-xl sm:text-3xl text-gray-500 font-bold mt-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 sm:w-10 sm:h-10">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                    </svg>

                    <h5 class="ml-1">Cadastros Por dia</h5>
                </div>
                <div>
                    GRAFICO AQUI
                </div>
            </div>
        </div>
        <div class="sm:w-3/4 w-full bg-white shadow rounded p-4 mt-4 overflow-x-auto">
            <div class="p-4 border rounded">
                <div class="border rounded mt-4 p-4 mt-8">
                    <form action="#" method="GET">
                        <div class="flex sm:flex-row flex-col sm:space-x-8 space-x-0">
                            <div class="flex flex-col w-full mt-4">
                                <label for="filter_month" class="text-sm text-gray-700">Filtrar Mês</label>
                                <select id="filter_month" name="filter_month"
                                    class="px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                                    @foreach ($list_month as $months)
                                        <option value="{{ $months }}">{{ $months }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <button type="submit" id="buttonCreateManifestacao"
                            class="sm:text-sm text-xs bg-blue-600 hover:bg-blue-700 text-white font-bold px-2 py-1 mt-2 rounded flex items-center">
                            <i class="fa fa-search"></i>
                            <p class="ml-1">Filtrar</p>
                        </button>
                    </form>
                </div>
                <div class="flex flex-col sm:flex-row justify-between mt-6 space-x-0 sm:space-x-8">
                    <div class="border rounded w-full p-2">
                        <h1 class="text-gray-600 font-light text-xl">Cadastros por Campanha <b
                                class="font-bold">(9824*)</b></h1>
                        <h1 class="text-gray-600 font-light text-xl">Envios por Campanha <b
                                class="font-bold">(188918*)</b></h1>
                        <div class="mt-6 flex flex-col text-gray-600">
                            <span class="text-xs">*Usuários cadastrados no período selecionado que fizeram postagens <b
                                    class="font-bold">( 1661* )</b></span>
                            <span class="text-xs">*Envios efetuados por usuários cadastrados neste período <b
                                    class="font-bold">( 188918* )</b></span>
                        </div>
                        <hr class="mt-2">
                        <div class="overflow-x-auto">
                            <table>
                                <thead class="border-b text-xs sm:text-sm font-normal">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Cliente
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            TAG
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Postagens
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs sm:text-sm border-b">
                                    <tr>
                                        <td class="px-6 py-4">
                                            <span class="text-gray-600 font-bold">ELA UP - Acessórios em Aço Inoxidável
                                                (Luisa Muniz Santos Sampaio)</span>
                                            <div class="flex items-center font-light mt-2">
                                                <p>Cadastro:</p>
                                                <span class="ml-1">19/12/2021</span>
                                            </div>
                                            <div class="flex flex-col font-light">
                                                <p> Primeira Postagem:</p>
                                                <span>19/12/2021</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div
                                                class="2xl:inline flex items-center px-2 py-1 text-xs rounded-full text-white font-bold gap-x-2 bg-red-500">
                                                <span class="text-xs">googleads_Institucional_v2</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            31771
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="border rounded w-full p-2 sm:mt-0 mt-8">
                        <h1 class="text-gray-600 font-light text-xl">Cadastros organicos <b class="font-bold">(20)</b>
                        </h1>
                        <div class="mt-6 flex flex-col text-gray-600">
                            <span class="text-xs">*Apenas com postagens efetuadas</span>
                        </div>
                        <hr class="mt-2">
                        <div class="overflow-x-auto">
                            <table class="text-left">
                                <thead class="border-b text-xs sm:text-sm font-normal">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Cliente
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Postagens
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs sm:text-sm border-b">
                                    <tr>
                                        <td class="px-6 py-4">
                                            <span class="text-gray-600 font-bold">ELA UP - Acessórios em Aço Inoxidável
                                                (Luisa Muniz Santos Sampaio)</span>
                                            <div class="flex items-center font-light mt-2">
                                                <p>Cadastro:</p>
                                                <span class="ml-1">19/12/2021</span>
                                            </div>
                                            <div class="flex flex-col font-light">
                                                <p> Primeira Postagem:</p>
                                                <span>19/12/2021</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            31771
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
