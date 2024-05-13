<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Manda Bem</title>

        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/favicon.png') }}">

        <link rel="stylesheet" href="{{url('css/app.css')}}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="overlay"></div>

        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
            
        </div>


        <!-- ARQUIVOS JS -->
        <script src="{{ url('js/acompanhamentos/acomp_email.js') }}"></script>

        <!--- ADMIN --> 
        <script src="{{ url('js/etiquetas/admin/expande_itens_etiquetas_admin.js') }}"></script>
        <script src="{{ url('js/etiquetas/admin/auditor.js') }}"></script>
        <script src="{{ url('js/etiquetas/admin/manifestacao.js') }}"></script>
        <script src="{{ url('js/etiquetas/admin/cancelamento_objeto.js') }}"></script>
        <!--- ADMIN --> 


        <!--- CLIENTE --> 
        <script src="{{ url('js/etiquetas/clientes/expande_itens_etiquetas_cliente.js') }}"></script>
        <!--- CLIENTE --> 

        <!--- PAGAMENTOS --> 
        <script src="{{ url('js/pagamentos/admin/cobranca.js') }}"></script>
        <script src="{{ url('js/pagamentos/admin/afiliados.js') }}"></script>
        <script src="{{ url('js/pagamentos/admin/creditos.js') }}"></script>
        <script src="{{ url('js/pagamentos/admin/boletos.js') }}"></script>
        <script src="{{ url('js/pagamentos/admin/transferencias.js') }}"></script>
        <!--- PAGAMENTOS --> 

        <script src="{{ url('js/usuario/edita_usuario.js') }}"></script>


        <script src="{{ url('js/agencias/atualiza_endereco.js') }}"></script>

        <!--- FAQ --> 
        <script src="{{ url('js/faq/insere_faq.js') }}"></script>
        <script src="{{ url('js/faq/deleta_faq.js') }}"></script>
        <script src="{{ url('js/faq/fetch_dados_faq.js') }}"></script>
        <!--- FAQ --> 

        <script src="{{ url('js/helpers/busca_destinatarios.js') }}"></script>
    </body>
</html>
