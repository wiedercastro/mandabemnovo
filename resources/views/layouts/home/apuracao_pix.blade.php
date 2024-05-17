<x-app-layout>
    <div class="sm:w-5/6 w-full ml-auto lg:px-12 p-4 flex justify-center">
        <div class="sm:w-3/4 w-full bg-white shadow rounded p-4">
            <h1 class="text-gray-500 font-bold text-2xl sm:text-4xl">Apuração PIX</h1>

            <div class="border p-4 rounded mt-6 w-3/5">            
                <form action="{{route('apuracao_pix')}}" method="GET" class="flex w-full" id="formGetValoresPix">   
                    <label for="date" class="sr-only">Search</label>
                    <div class="relative w-full">
                        <input type="text" id="date" name="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5" placeholder="00/00/0000" required />
                    </div>
                    <button id="buttonSearchApuracao" type="submit" class="inline-flex items-center py-1 px-2 ms-2 text-sm font-medium text-white bg-blue-700 rounded-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">
                        <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                        <span id="buttonText">Buscar</span>
                    </button>
                </form>

                <a href="{{route('apuracao_pix')}}" class="bg-red-500 hover:bg-red-600 px-2 py-1 rounded mt-2 text-xs inline-flex items-center text-white font-bold">
                    <i class="fa fa-trash"></i>
                    <p class="ml-1">Limpar</p>
                </a>
            
                <hr class="mt-8">
                <div class="rounded-lg w-full mt-6">
                    <hr>
                    <div class="p-4 flex justify-between text-3xl text-green-700">
                        <p>Total:</p>
                        <span class="font-bold" id="total">{{$total}}</span>
                    </div>
                    <hr>
                    <div class="p-4 flex justify-between">
                        <p class="text-xl text-gray-700">Pagos:</p>
                        <span class="font-bold" id="pagos">{{$pagos}}</span>
                    </div>
                    <hr>
                    <div class="p-4 flex justify-between">
                        <p class="text-xl text-gray-700">Aguardando Pgto:</p>
                        <span class="font-bold" id="aguardando">{{$aguardando}}</span>
                    </div>
                    <hr>
                    <div class="border-t border-gray-200 border-dotted p-4 flex justify-between items-center">
                        <div>
                            <h1 class="text-xl text-gray-700">* Deletados:</h1>
                            <p class="text-xs mt-2">* Manualmente e automaticamente</p>
                        </div>
                        <p class="font-bold" id="deletados">{{$deletados}}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>


<script>
document.getElementById('formGetValoresPix').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const dateValue = document.getElementById('date').value;
    let buttonText = document.getElementById('buttonText');
    buttonText.textContent = "Buscando...";

    let buttonSearchApuracao = document.getElementById('buttonSearchApuracao');
    buttonSearchApuracao.disabled = true;

    fetch(`apuracao_pix?date=${encodeURIComponent(dateValue)}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        let total = document.getElementById('total')
        let pagos = document.getElementById('pagos')
        let aguardando = document.getElementById('aguardando')
        let deletados = document.getElementById('deletados')

        const resultJson = data.data;

        total.textContent = resultJson.total
        pagos.textContent = resultJson.pagos
        aguardando.textContent = resultJson.aguardando
        deletados.textContent = resultJson.deletados
    })
    .catch(error => {
        console.error('There has been a problem with your fetch operation:', error);
    })
    .finally(() => {
        buttonText.textContent = "Buscar";
        buttonSearchApuracao.disabled = false;
    })

})

</script>