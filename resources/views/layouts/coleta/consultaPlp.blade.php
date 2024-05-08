<x-app-layout>
    <div class="sm:w-5/6 w-full ml-auto lg:px-12 p-4">
        <div class="sm:w-1/2 w-full bg-white shadow rounded p-4 mt-4">
            <form action="#" method="GET" class="border p-4 rounded">
                <div class="flex items-center text-gray-500 font-bold text-3xl">
                    <i class="fa fa-filter"></i>
                    <h1 class="ml-1">Consultar PLP (correios)</h1>
                </div>
                <hr class="mt-2">

                <div class="flex flex-col w-full mt-4">
                    <label for="filter_numero_plp" class="text-sm text-gray-700">Número</label>
                    <input required type="text" id="filter_numero_plp" name="filter_numero_plp" placeholder="Número interno ou número PLP" class="cliente px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                </div>

                <div class="flex flex-col w-full mt-4">
                    <label for="filter_xml" class="text-sm text-gray-700">XML</label>
                    <input required type="text" id="filter_xml" name="filter_xml" class="cliente px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                </div>

                <hr class="mt-6">

                <div>
                    <button
                        type="submit"
                        id="buttonCreateManifestacao"
                        class="sm:text-sm text-xs bg-green-600 hover:bg-green-700 text-white font-bold px-2 py-1 mt-6 rounded flex items-center"> 
                        <i class="fa fa-search"></i>                   
                        <p class="ml-1">Consultar</p>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
