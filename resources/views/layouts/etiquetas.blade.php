<x-app-layout>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        /* Nws Add */

        body {
            background-color: #F0F1F3;
        }

        #sidebarMenu {
            /*border-radius: 20px;*/
            /*border-top-left-radius: 20px;*/
            /*border-bottom-left-radius: 20px;*/

            border-top-right-radius: 20px;
            border-bottom-right-radius: 20px;
            height: 1000px;
            position: relative;
        }

        #sidebarMenu a {
            color: #728189;
            font-weight: bold;
        }

        .container-menu-lateral {
            padding-left: 2px;
        }



        .nav-link.selected {
            color: #25688B !important;
        }

        .navbar-toggler {
            padding: 0px;
        }

        .btn-expand-top-menu {
            width: 45px;
            height: 40px;
            border-color: transparent;
            background-color: #FFF;
            border-radius: 10px;
            float: left;
        }

        .btn-expand-top-menu .line {
            width: 100%;
            float: left;
            height: 2px;
            background-color: #728189;
            margin-bottom: 4px;
        }

        .logo-cli-xs {
            /*float: left;*/
            /*margin-left: 50%;*/
            /*width: 80%;*/
            /*text-align: center;*/
            /*border: solid 1px;*/
        }

        .borda-redonda {
            border-radius: 10px;
            display: block;
            background-color: #fff;
            color: #728189;
            height: 380px;
            width: 100%;
            padding: 15px;
            margin-left: -15px;
        }

        .borda-redonda-pagamentos {
            border-radius: 10px;
            background-color: #fff;
            color: #728189;
            height: 180px;
            width: 100%;
            padding: 10px;
            margin-left: -15px;
        }

        .logo-cli-xs img {
            width: 120px;
        }

        .btn-tamanho-img img {
            width: 20px;
        }

        .clear-both {
            clear: both;
        }

        .menu-user-xs {
            float: right;
            /*border: solid 1px;*/
        }

        .menu-user-xs img {
            width: 20px;
            margin-top: 10px;
        }

        .icon-menu {
            width: 22px;
            float: left;
        }

        .title-page {
            color: #142D3A;
        }

        .content-main {
            float: left;
            margin-top: 10px;
            /*margin-left: 265px;*/
            /*width: max-content;*/
        }

        .content-left {
            margin-left: 265px;

        }

        .main-content {
            height: 2000px;
        }

        .red-box {
            margin-top: 10px;
            /*width: 400px;*/
            /*position: relative;*/
            padding: 5px;
        }

        table {
            display: table;
            border-collapse: separate;
            box-sizing: border-box;
            text-indent: initial;
            border-spacing: 2px;
            border-color: gray;
        }

        .table-red {
            background-color: #A01E26;
            text-decoration-color: white;
            border-radius: 15px;
            /*display: block;*/
            width: 100%;
            /*text-align: center;*/
            /*margin:  30px;*/
            /*margin-left: 0px;*/
            padding: 10px;
            margin-top: 0px;
            /*margin-top: -25px;*/
            vertical-align: middle;

        }

        .table-red tbody {

            padding: 5px;
        }

        .table-red td {
            border: none;
            line-height: 20px;
        }

        .btn-search-top,
        .btn-search-top:hover,
        .btn-show-itens,
        .btn-show-itens:hover {
            background-color: #25688B;
            font-weight: bold;
        }

        .line-table-color {
            background-color: #25688B !important;
            color: #fff !important;
        }

        .btn-color-danger {
            background-color: #A01E26;
            font-size: 13px;
            line-height: 15px;

        }

        .text-table-color {
            color: #25688B;
        }

        .text-color-gray {
            color: #728189;
        }

        .text-color-blue {
            color: #25688B;
        }

        .badge-danger {
            background-color: #f0e289;
            color: #25688B;
            line-height: 30px;
            font-size: 13px;
        }

        .filtro-top.shadow-sm {
            border-radius: 30px !important;
        }

        .filtro-top input[type=text],
        .filtro-top select {
            background-color: #F0F1F3;
        }

        .div-table-general {
            border-radius: 10px;
            background-color: #FFF;
        }

        .table-general thead th {
            font-weight: normal;
            color: #728189 !important;
            font-weight: bold;
        }

        .form-control input {
            line-height: 2;
            border: 0px !important;

        }

        .form-control select {
            line-height: 2;
            border: 0px !important;
        }

        .form-control textarea {
            line-height: 2;
            border: 0px !important;
        }


        .menu-consulta-user {
            border-radius: 15px;
            color: #728189 !important;
            padding: 10px;
            margin-top: 0px;
            vertical-align: middle;
        }

        @media screen and (max-width: 1000px) {
            .div-web {
                display: none;
            }

            #sidebarMenu {
                display: none !important;
            }

            .hide-mobile {
                display: none;
            }

            .content-main {
                margin-left: 0px;
            }

            .content-left {
                margin-left: 0px;
            }
        }

        @media screen and (min-width: 1000px) {
            .div-mobile {
                display: none;
            }

            .d-md-block {
                display: block !important;
            }

            .navbar-expand-md,
            .navbar-toggler {
                display: none !important;
            }

        }

        body {
            padding-top: 0px !important;
        }

        .total-data {

            padding: 20px 5px !important;

        }

        .table-total {
            margin-bottom: 0px !important;
        }

        .table-total td {
            border: none !important;
            border-left: solid 1px #eee !important;
            padding: 2px !important;
        }

        .title-form-header {
            border-bottom: solid 1px #ddd;
            font-weight: bold;
            padding-bottom: 5px;
        }

        .btn-round {
            border-radius: 20px;
        }

        .sup_required {
            color: red;
        }

        .table-date {
            font-size: 12px;
            font-weight: bold;
        }

        .table-id {
            font-weight: bold;
            color: blue;
        }

        .table-blue {
            background-color: #008adb !important;
            color: #fff;
        }

        .table-white {
            background-color: #fff !important;
            color: #008adb !important;
        }

        .destination-name {
            font-weight: bold;
            color: #fff;
        }

        .envios-box,
        .envios-box a {
            color: #fff;
        }

        .pagination {
            margin: 2px 0px;
        }

        .box-radius {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 10px;
        }

        .clear {
            clear: both;
        }

        .valor-final-coletas {
            font-weight: bold;
        }

        .total-data small {
            color: #fff;
        }

        .filtro-top.shadow-sm {
            border-radius: 30px !important;
        }

        .filtro-top input[type=text],
        .filtro-top select {
            background-color: #F0F1F3;
        }
    </style>
         
        <div class="w-5/6 ml-auto lg:px-12" style="border: 0px solid red;">
            <div class="text-4xl" style="margin-top:-25px">
                <h1 style="color:#728189"><b>Etiquetas</b></h1>
            </div>
            <br>
            <div class=" w-full dark:bg-gray-800" style="border: 1px solid black;">
                <div class="w-full text-gray-900 dark:text-gray-100">
                    {{-- <table class="table table-red" style="width:500px !important;">
                        <tr style="color:white;">
                            <td colspan="2">
                                <small class="d-inline-block" tabindex="0" data-toggle="tooltip"
                                    style="font-size: 12px;"> Você economizou até agora com a Manda Bem</small>
                                <br>
                                <strong>
                                    <?php
                                    $total_economia = 28;
                                    ?>
                                    R$ 28,00
                                </strong>
                            </td>
                        </tr>

                        <tr style="color:white;">
                            <td>
                                <strong>
                                    <small style="font-size: 30px;">
                                        Total
                                    </small>
                                </strong>
                                <br>
                                <small class="" tabindex="0" style="font-size: 12px;">

                                </small>
                            </td>
                            <td class="bar">


                                <span class="valor-final-coletas">
                                    R$ 100,00
                                </span>

                            </td>



                        </tr>
                        <tr style="color:white;">
                            <td><strong> Economia do Mês </strong></td>
                            <td class="bar text-right">
                                <span class="valor-final-coletas">

                                    <span title="Teste">
                                        R$ 10,00
                                    </span>


                                </span>
                            </td>
                        </tr>
                        <tr style="color:white;">
                            <td><strong> Saldo</strong></td>
                            <td class="bar text-right"> R$ 152,00</td>
                        </tr>
                        <tr style="color:white;">
                            <td><strong> Divergências</strong></td>
                            <td class="bar text-right"> R$ 00,00 </td>
                        </tr>
                    </table>
                    <br> --}}
                    {{-- <div class="text-4xl" style="margin-left: 5px;">
                        <h2 style="color:#728189"><b>Buscar</b></h2>
                    </div> --}}
                    <div class="w-full m-auto h-20 bg-white shadow-xl" style="border: 0px solid red">
                        <div class="w-11/12 flex m-auto" style="border:0px solid red">
                            <div class="w-full mt-3.5 pr-4">
                                <input
                                    class="w-full m-auto pl-10 text-base placeholder-gray-500 border rounded-full focus:shadow-outline"
                                    type="search" placeholder="Buscar por Nome, Destinatário, Etiqueta...">
                            </div>
                            <div class="w-full mt-3.5 pr-4">
                                <input
                                    class="w-11/12 m-auto pl-10 text-base placeholder-gray-500 border rounded-full focus:shadow-outline"
                                    type="search" placeholder="Buscar...">

                            </div>
                            <div class="w-3/12 mt-3.5">
                                <button class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4"
                                    style="background-color: #2d6984;">
                                    <i class="fa fa-search" aria-hidden="true" style="border:0px solid red"></i>
                                    Buscar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                {{-- <div class="text-4xl" style="margin-left: 5px;">
                    <h1 style="color:#728189"><b>Etiquetas</b></h1>
                </div> --}}

                <div  class="mx-auto overflow-x-auto">
                <table
                    class="min-w-full table-auto ml-auto bg-white text-sm text-left text-gray-500 dark:text-gray-400 border-collapse">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Id
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Pagto
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Desconto
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Valor
                            </th>
                            <th scope="col" class="px-1 py-3">
                                Impressão
                            </th>
                            <th scope="col" class="px-1 py-3">
                                <span class="sr-only">Itens</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($envios as $envio)
                            <tr class="bg-white border-b dark:bg-blue-800 dark:border-gray-700 hover:bg-blue-100 dark:hover:bg-blue-100 rounded-full"
                                id="linha_{{ $envio->id }}">

                                <th class="px-6 py-4 rounded-s-lg" style="color:#2d6984"
                                    id="idenvio_{{ $envio->id }}">
                                    <button id="btnInfoCol" data-id ="{{ $envio->id }}">
                                        <i class="fa fa-clock-ow" aria-hidden="true"></i> 24/12/2023 <br>
                                        MB{{ $envio->id }}
                                    </button>
                                </th>
                                <td class="px-6 py-4">
                                    Credito
                                </td>
                                <td class="px-6 py-4">
                                    R$ {{ $envio->desconto }}
                                </td>
                                <td class="px-6 py-4 font-medium text-green-950">
                                    R$ {{ $envio->total }}
                                </td>
                                <td class="px-2 py-2">
                                    @if ($envio->type=='REVERSA') 
                                        Aut. Postagem<br>
                                        
                                    @else
                                    <a href="#"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline"><i class="fa fa-print"></i> 4x4</a><br>
                                        <a href="#"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline"><i class="fa fa-print"></i> Declaração</a>
                                    @endif
                                </td>
                                <td dir="rtl" class="px-2 py-2 text-right rounded-s-lg">
                                    @if ($envio->type=='REVERSA') 
                                    <button dir="ltr" type="button" class="w-1/2 text-neutral-950 hover:text-white bg-amber-200 hover:bg-amber-800 focus:ring-4 focus:ring-amber-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-amber-600 dark:hover:bg-amber-700 focus:outline-none dark:focus:ring-amber-800">Reversa </button>
                                    @else
                                        
                                        {{-- <button dir="auto" href="#"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline"> <span>{{ $envio->qte }}</span> Itens</button> --}}
                                        
                                        <button dir="ltr" type="button" class="w-1/2 text-white bg-cyan-700 hover:bg-cyan-800 focus:ring-4 focus:ring-cyan-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-cyan-600 dark:hover:bg-cyan-700 focus:outline-none dark:focus:ring-cyan-800">{{ $envio->qte }} 
                                            @if ($envio->qte>1) 
                                                Itens
                                            @else
                                                Item
                                            @endif
                                    </button>
                                    @endif
                                    
                                </td>

                            </tr>

                            <tr>
                                <td colspan="6">
                                    <div id="detalhes_{{ $envio->id }}" style="display: none">

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="w-full m-auto py-4">
                    {{ $envios->links() }}
                </div>
            </div>


            </div>
        </div>
    
</x-app-layout>

<script>
    $(document).on("click", "#btnInfoCol", function() {
        var info = $(this).attr('data-id');
        var rota = "{{ route('coleta.show', ['id' => ':idenvio']) }}"
        rota = rota.replace(":idenvio", info);
        $.ajax({
            url: rota,

            success: function(data) {
                $('#linha_' + info).css("background", "#2d6984");
                $('#linha_' + info).css("color", "white");
                $('#idenvio_' + info).css("color", "white");
                $('#detalhes_' + info).show();
                $('#detalhes_' + info).append(data.html);
            },
        });


    });
</script>
