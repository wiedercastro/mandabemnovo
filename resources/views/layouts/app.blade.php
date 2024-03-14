<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Manda Bem</title>

        <link rel="stylesheet" href="{{url('css/app.css')}}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
        <!-- Scripts -->
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
        <script src="{{ url('js/etiquetas/expande_itens_etiquetas.js') }}"></script>
        <script src="{{ url('js/declaracoes/como_funciona_modal_detalhes.js') }}"></script>
        <script src="{{ url('js/gerar/importar_bling.js') }}"></script>
        <script src="{{ url('js/gerar/importar_loja_integrada.js') }}"></script>
        <script src="{{ url('js/gerar/importar_nuvem.js') }}"></script>
        <script src="{{ url('js/gerar/importar_pedidos_tiny.js') }}"></script>
        <script src="{{ url('js/gerar/importar_yampi.js') }}"></script>
        <script src="{{ url('js/gerar/importar_shopify.js') }}"></script>
        <script src="{{ url('js/gerar/importar_fastCommerce.js') }}"></script>
    </body>
</html>
