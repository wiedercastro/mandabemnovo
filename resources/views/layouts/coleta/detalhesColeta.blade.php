<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


<div class="w-full"  style="border: 1px solid black;">
    <div class="w-full bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg" >
        <div class="w-full relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                style="border:1px solid red;">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Etiqueta
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Desconto
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Valor
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Destino
                        </th>
                        <th scope="col" class="px-6 py-3">
                            
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($envios as $envio)
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $envio->etiqueta_correios }}BR
                            </th>
                            <td class="px-6 py-4">
                                R$ {{ $envio->valor_desconto }}
                            <td class="px-6 py-4">
                                R$ {{ $envio->valor_total }} 
                            </td>
                            <td class="px-6 py-4">
                                {{ $envio->destinatario }} -  CEP {{ $envio->CEP }} <br>
                                <span class="text-blue-800 font-medium	"> Data: </span> {{date( 'd/m/Y H:m:s' , strtotime($envio->date_insert))}}
                            </td>
                            <td class="px-6 py-4">
                                
                            </td>
                            <td class="px-6 py-4 text-right">
                                
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


    </div>
</div>

<script>
    $(document).on("click", "#btnInfoCol", function() {
        var info = $(this).attr('data-id');
        $.ajax({
            url: "{{ route('etiqueta.show', ['id' => '6495700']) }}",

            success: function(data) {
                console.log(data.html);
            },
        });


    });
</script>
