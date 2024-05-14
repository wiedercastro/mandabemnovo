<x-app-layout>
    <div class="sm:w-5/6 w-full ml-auto lg:px-12 p-4">
        <div class="sm:w-1/2 w-full bg-white shadow rounded p-4 mt-4">
            <div class="flex items-center p-4 mb-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50" role="alert">
                <i class="fa fa-check text-xl"></i>
                <span class="sr-only">Info</span>
                <div>
                    <span class="font-medium ml-1">Successo!</span> Limpeza efetuada com sucesso.
                </div>
            </div>
            <form action="#" method="GET" class="border p-4 rounded">
                <div class="flex items-center text-gray-500 font-bold text-3xl">
                    <i class="fa fa-filter"></i>
                    <h1 class="ml-1">Limpar cache correios</h1>
                </div>
                <hr class="mt-2">

                <div class="flex flex-col w-full mt-4">
                    <label for="origem" class="text-sm text-gray-700">Origem</label>
                    <input required type="text" id="origem" name="origem" placeholder="CEP origem" class="cliente px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                </div>

                <div class="flex flex-col w-full mt-4">
                    <label for="destino" class="text-sm text-gray-700">Destino</label>
                    <input required type="text" id="destino" name="destino" placeholder="CEP destino" class="cliente px-1 py-2 w-full border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                </div>

                <hr class="mt-6">

                <div>
                    <button
                        type="submit"
                        id="buttonCreateManifestacao"
                        class="sm:text-sm text-xs bg-blue-600 hover:bg-blue-700 text-white font-bold px-2 py-1 mt-6 rounded flex items-center"> 
                        Limpar
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
