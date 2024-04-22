<x-app-layout>

    <div class="w-5/6 lg:px-12 sm:ml-6 lg:ml-80">

        <div class="flex justify-center sm:hidden ml-10 sm:ml-0">
            <img src="{{ asset('images/logo_mandabem_az.png') }}" alt="" class="w-32" />
        </div>

        <div class="mt-4 w-full ml-4 sm:ml-0">
            <x-card-pagamentos :totalEconomia="$totalEconomia" :totalEconomiaDoMes="$totalEconomiaDoMes" :valorTotal="$valorTotal" :totalDivergencia="$totalDivergencia" :totalSaldo="$totalSaldo"
                :mesAtual="$mesAtual" :anoAtual="$anoAtual" />
        </div>
        <div class="flex flex-col sm:flex-row sm:justify-between mt-20 sm:ml-0 ml-4 sm:w-full w-[22rem]">
            <div class="text-4xl mt-6">
                <h1 class="text-gray-500 font-bold text-4xl text">Etiquetas</h1>
            </div>
            <div class="flex flex-row-reverse">
                <form action="#"
                    class="w-full mt-1 flex flex-col sm:flex-row space-x-1 p-4 items-center sm:items-end border rounded bg-white">
                    <div class="flex flex-col">
                        <input type="text"
                            class="px-1 py-1 w-72 border outline-none rounded bg-white border-gray-200 text-sm"
                            placeholder="Buscar por Nome, Destinatário, Etiqueta...">
                    </div>

                    <div class="flex flex-col mt-2">
                        <select required
                            class="px-1 py-1 w-72 border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
                            <option value="" disabled selected class="text-sm">Situação Postagem</option>
                            <option value="postados">Postados</option>
                            <option value="pendentes">Pendentes</option>
                        </select>
                    </div>

                    <button type="submit"
                        class="text-white font-bold text-xs w-full
              hover:bg-gray-700 rounded border mt-2
              border-gray-500 bg-gray-500 px-2 py-1.5">
                        Buscar
                    </button>
                </form>
            </div>
        </div>

        <div class="sm:overflow-hidden overflow-x-auto sm:w-full w-[22rem] sm:ml-0 ml-4">
            <table
                class="mt-2 min-w-full table-auto ml-auto bg-white font-normal rounded shadow-lg text-sm text-left text-gray-500 border-collapse border-1">
                <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Id
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Ecommerce
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Pagto
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Cliente
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Desconto
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Correios
                        </th>
                        <th scope="col" class="px-6 py-3">
                            PayPal
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Mandabem
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Valor
                        </th>
                        <th scope="col" class="px-1 py-3">
                            Impressão
                        </th>
                        <th scope="col" class="px-1 py-3">
                            {{-- Itens --}}
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($envios as $envio)
                        <tr onclick="expandeDetalhesEtiquetasAdmin({{ $envio->id }})"
                            class="bg-white hover:bg-gray-100 border-b rounded-full font-light cursor-pointer"
                            id="linha_{{ $envio->id }}">
                            <th class="px-6 py-4 rounded-s-lg text-[#2d6984]" id="idenvio_{{ $envio->id }}">
                                <button id="btnInfoCol" data-id ="{{ $envio->id }}">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-3 h-3 mr-1">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                        <p class="text-xs">24/12/2023</p>
                                    </div>
                                    MB{{ $envio->id }}
                                </button>
                            </th>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    @switch($envio->is_nuvem_shop)
                                        @case(1)
                                            <i class="fa fa-cloud text-blue-700" title="Cliente Nuvem Shop"></i>
                                        @break

                                        @case(0)
                                            @switch($envio->plataform_integration)
                                                @case('WOOCOMMERCE')
                                                    <img title="Cliente WooCommerce" src="{{ asset('icone/wordpress.png') }}" />
                                                @break

                                                @case('loja_integrada')
                                                    <img title="Cliente Loja Integrada"
                                                        src="{{ asset('icone/loja-integrada-cubo.png') }}" />
                                                @break
                                            @endswitch
                                        @break

                                    @endswitch
                                    @if ($envio->data_cliente_cadastro >= now()->format('Y') . '-01-01 00:00:00')
                                        <span class="font-bold">{{ $envio->razao_social }}</span>
                                    @else
                                        <span>{{ $envio->razao_social }}</span>
                                    @endif
                                    <i class="fa fa-eye text-blue-500"></i>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if (!$envio->payment_id)
                                    <span class="d-inline-block text-blue-700" title="Pagamento por Crédito">
                                        Crédito
                                    </span>
                                @else
                                    <span class="d-inline-block" title="Id do pagamento: {{ $envio->payment_id }}">
                                        PayPal
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                teste
                            </td>
                            <td id="desconto" lass="px-6 py-4">
                                R$ {{ $envio->desconto }}
                            </td>

                            <td class="px-6 py-4">
                                @if ($envio->total_correios)
                                    {{ $envio->total_correios }}
                                @else
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4 stroke-yellow-600">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                                        </svg>
                                        <small class="text-yellow-600 ml-1">Aguardando</small>
                                    </div>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                @if ($envio->total_paypal > 0)
                                    <span>R$ {{ number_format($envio->total_paypal, 2) }} </span>
                                @else
                                    <span>R$ 0,00</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if ($envio->total_correios)
                                    {{ number_format($envio->total_correios, 2) }}
                                @else
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor"
                                            class="w-4 h-4 stroke-yellow-600">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                                        </svg>
                                        <small class="text-yellow-600 ml-1">Aguardando</small>
                                    </div>
                                @endif
                            </td>
                            <td id="valor" class="px-6 py-4 font-medium text-green-950">
                                R$ {{ $envio->total }}
                            </td>
                            <td class="px-2 py-2">
                                @if ($envio->type == 'REVERSA')
                                    Aut. Postagem<br>
                                @else
                                    <a href="#"
                                        class="tdType1 font-medium text-blue-600 hover:underline flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                                        </svg>
                                        4x4
                                    </a>
                                    <br>
                                    <a href="#"
                                        class="tdType2 font-medium text-blue-600 hover:underline flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                                        </svg>
                                        <p>Declaração</p>
                                    </a>
                                @endif
                            </td>
                            <td class="px-2 py-2">
                                @if ($envio->type == 'REVERSA')
                                    <button type="button"
                                        class="bg-yellow-700 text-xs hover:bg-yellow-800 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-3 h-3 mr-1">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 0 0-3.7-3.7 48.678 48.678 0 0 0-7.324 0 4.006 4.006 0 0 0-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 0 0 3.7 3.7 48.656 48.656 0 0 0 7.324 0 4.006 4.006 0 0 0 3.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3-3 3" />
                                        </svg>
                                        Reversa
                                    </button>
                                @else
                                    <button type="button"
                                        class="bg-cyan-700 text-xs hover:bg-cyan-800 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">{{ $envio->qte }}
                                        @if ($envio->qte > 1)
                                            Itens
                                        @else
                                            Item
                                        @endif
                                    </button>
                                @endif
                            </td>
                        </tr>

                        <div id="preloader" class="overlay">
                            <div class="loader flex justify-center text-white font-bold items-center text-xs">
                                <img src="images/spinner.svg" class="h-10 w-10">
                                <p class="mt-6 ml-3">Carregando..</p>
                            </div>
                        </div>

                        <tr>
                            <td colspan="10">
                              <table class="mt-2 min-w-full table-auto ml-auto bg-white font-normal rounded shadow-lg
                              text-sm text-left text-gray-500 border-collapse overflow-x-auto border-1">
                                <tbody id="detalhes_admin_{{ $envio->id }}" class="bg-gray-50">
                                </tbody>
                              </table>
                            </td>
                        </tr>

                    @endforeach
                </tbody>
            </table>
        </div>

        <x-modal-add-resumo-auditor/>
        <x-modal-manifestacao />
        <div class="sm:ml-0 ml-4">
            <x-pagination :paginator="$envios" />
        </div>
    </div>
</x-app-layout>
