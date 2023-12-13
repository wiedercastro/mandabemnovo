<div class="sm:flex sm:items-center sm:justify-between">
    <div>
        <div class="flex items-center gap-x-3">
            <h1 class="text-lg text-black-500">Fórum</h1>

            <span class="px-3 py-1 text-xs text-blue-600 bg-blue-100 rounded-full dark:bg-gray-800 dark:text-blue-400">{{ $supports->total() }} dúvidas</span>
        </div>
    </div>

    <div class="flex items-center mt-4 gap-x-3">

        <a href="{{ route('supports.create') }}" class="flex items-center justify-center w-1/2 px-5 py-2 text-sm text-gray-700 transition-colors duration-200 bg-white border rounded-lg gap-x-2 sm:w-auto dark:hover:bg-gray-800 dark:bg-gray-900 hover:bg-gray-100 dark:text-gray-200 dark:border-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>

            <span>Nova Dúvida</span>
        </a>
    </div>
</div>

<div class="mt-6 md:flex md:items-center md:justify-between">

    <div class="relative flex items-center mt-4 md:mt-0">
        <span class="absolute">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mx-3 text-gray-400 dark:text-gray-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
        </span>

        <form action="{{ route('supports.index') }}" method="get">
            <input name="filter" type="text" placeholder="Procurar" class="block w-full py-1.5 pr-5 text-gray-700 bg-white border border-gray-200 rounded-lg md:w-80 placeholder-gray-400/70 pl-11 rtl:pr-11 rtl:pl-5 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40" value="{{ $filters['filter'] ?? '' }}">
        </form>
    </div>
</div>

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
                position:relative;
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
            
            .borda-redonda{
                border-radius: 10px;
                display: block;
                background-color: #fff;
                color: #728189 ;
                height: 380px;
                width: 100%;
                padding:  15px;
                margin-left: -15px;
            }
 
            .borda-redonda-pagamentos{
                border-radius: 10px;
                background-color: #fff;
                color: #728189 ;
                height: 180px;
                width: 100%;
                padding:  10px;
                margin-left: -15px;
            }

            .logo-cli-xs img {
                width: 120px;
            }
            .btn-tamanho-img img{
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

            .table-red{
                background-color: #A01E26;
                text-decoration-color: white; 
                border-radius: 15px;
                /*display: block;*/
                width: 100%;
                /*text-align: center;*/
                /*margin:  30px;*/
                /*margin-left: 0px;*/
                padding:  10px;
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
            .btn-search-top, .btn-search-top:hover, .btn-show-itens, .btn-show-itens:hover{
                background-color: #25688B;
                font-weight: bold;
            }
            .line-table-color{
                background-color: #25688B !important;
                color:#fff!important;
            }
            .btn-color-danger{
                background-color: #A01E26;
                font-size: 13px;
                line-height: 15px;

            }
            .text-table-color{
                color: #25688B;
            }
            .text-color-gray{
                color: #728189;
            }
            .text-color-blue{
                color: #25688B;
            }
            .badge-danger{
                background-color: #f0e289;
                color: #25688B;
                line-height: 30px;
                font-size: 13px;
            }

            .filtro-top.shadow-sm {
                border-radius: 30px !important;
            }
            .filtro-top input[type=text], .filtro-top select{
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
            
            .form-control input{
                line-height: 2;
                border: 0px !important;
                
            }
            
            .form-control select{
                line-height: 2;
                border: 0px !important;
            }
            
            .form-control textarea{
                line-height: 2; 
                border: 0px !important;
            }
           
            
            .menu-consulta-user{
                border-radius: 15px;
                color: #728189 !important;
                padding:  10px;
                margin-top: 0px;
                vertical-align: middle;
            }
            @media screen and (max-width: 1000px){
                .div-web {display: none;} 
                #sidebarMenu {display: none !important;}
                .hide-mobile {display: none;}
                .content-main {margin-left: 0px;}
                .content-left {margin-left: 0px;}
            }
            
            @media screen and (min-width: 1000px){
                .div-mobile {display: none;} 
                .d-md-block {display: block!important;}
                .navbar-expand-md, .navbar-toggler {display: none !important;}
                 
            }
        </style>
