<div class="flex flex-row-reverse">
    <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500 border-b border-gray-200">
        <li class="me-2">
            <a href="{{route('pagamentos.index')}}" aria-current="page" 
                @class(['inline-block p-2 rounded-t-lg hover:bg-white hover:text-blue-600 flex items-center', 'bg-white text-blue-600' => Route::is('pagamentos.index')])>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                </svg>    
                <p class="ml-1">Pagamentos</p>
            </a>
        </li>
        <li class="me-2">
            <a href="{{route('afiliados.pagamento')}}" aria-current="page" 
                @class(['inline-block p-2 rounded-t-lg hover:bg-white hover:text-blue-600 flex items-center', 'bg-white text-blue-600' => Route::is('afiliados.pagamento')])>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                </svg>
                <p class="ml-1">Afiliados</p>
            </a>
        </li>
        <li class="me-2">
            <a href="{{route('transferencia')}}"
            @class(['inline-block p-2 rounded-t-lg hover:bg-white hover:text-blue-600 flex items-center', 'bg-white text-blue-600' => Route::is('transferencia')])>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
                </svg>
                <p class="ml-1">Transferências</p>
            </a>
        </li>
        <li class="me-2">
            <a href="{{route('boleto.pagamento')}}"
                @class(['inline-block p-2 rounded-t-lg hover:bg-white hover:text-blue-600 flex items-center', 'bg-white text-blue-600' => Route::is('boleto.pagamento')])>
                <i class="fa fa-barcode"></i>
                <p class="ml-1">Boletos</p>
            </a>
        </li>
        <li class="me-2">
            <a href="{{route('creditos.pagamento')}}"
                @class(['inline-block p-2 rounded-t-lg hover:bg-white hover:text-blue-600 flex items-center', 'bg-white text-blue-600' => Route::is('creditos.pagamento')])>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <p class="ml-1">Cŕedito</p>
            </a>
        </li>
        <li class="me-2">
            <a href="{{route('cobranca.pagamento')}}"
                @class(['inline-block p-2 rounded-t-lg hover:bg-white hover:text-blue-600 flex items-center', 'bg-white text-blue-600' => Route::is('cobranca.pagamento')])>
                <i class="fa fa-minus-circle"></i>
                <p class="ml-1">Cobrança</p>
            </a>
        </li>
    </ul>
</div>