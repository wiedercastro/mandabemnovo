<x-app-layout>

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

    <div class="py-10" style="border: 0px solid black; margin-left:270px;">
        <div class="max-w-full mx-auto sm:px-8 lg:px-12" style="border: 0px solid red;">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="table table-red" style="width:500px !important;">
                        <tr style="color:white;">
                            <td colspan="2">
                                <small class="d-inline-block" tabindex="0" data-toggle="tooltip" style="font-size: 12px;"> Você economizou até agora com a Manda Bem</small>
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

                    <div style="margin-left: 5px;">
                        <h2 style="color:#728189"><b>Buscar</b></h2>
                    </div>
                    <div class="flex">
                        <div class="relative max-w-md w-full" style="width:600px !important;">
                            <div class="absolute top-1 left-2 inline-flex items-center p-2">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input class="w-full h-10 pl-10 pr-4 py-1 text-base placeholder-gray-500 border rounded-full focus:shadow-outline" type="search" placeholder="Buscar por Nome, Destinatário, Etiqueta...">
                        </div>
                        <div class="flex">
                            <div class="relative max-w-md w-full" style="width:500px !important; margin-left:20px;">
                                <div class="absolute top-1 left-2 inline-flex items-center p-2">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input class="w-full h-10 pl-10 pr-4 py-1 text-base placeholder-gray-500 border rounded-full focus:shadow-outline" type="search" placeholder="Buscar...">
                            </div>
                            <div class="relative max-w-md w-full space-x-20" style="width:100px !important; border:0px solid red; margin-left:20px;">
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 border border-blue-700 rounded" style="background-color: #A01E26;">
                                    Buscar
                                </button>
                            </div>

                        </div>
                    </div>
                </div>



                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
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
                                <th scope="col" class="px-6 py-3">
                                    Impressão
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <span class="sr-only">Itens</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Apple MacBook Pro 17"
                                </th>
                                <td class="px-6 py-4">
                                    Silver
                                </td>
                                <td class="px-6 py-4">
                                    Laptop
                                </td>
                                <td class="px-6 py-4">
                                    $2999
                                </td>
                                <td class="px-6 py-4">
                                    $2999
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Itens</a>
                                </td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Microsoft Surface Pro
                                </th>
                                <td class="px-6 py-4">
                                    White
                                </td>
                                <td class="px-6 py-4">
                                    Laptop PC
                                </td>
                                <td class="px-6 py-4">
                                    $1999
                                </td>
                                <td class="px-6 py-4">
                                    $1999
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Itens</a>
                                </td>
                            </tr>
                            <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Magic Mouse 2
                                </th>
                                <td class="px-6 py-4">
                                    Black
                                </td>
                                <td class="px-6 py-4">
                                    Accessories
                                </td>
                                <td class="px-6 py-4">
                                    $99
                                </td>
                                <td class="px-6 py-4">
                                    $99
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Itens</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
</x-app-layout>