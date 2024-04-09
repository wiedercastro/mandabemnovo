<x-app-layout>

  <div class="w-5/6 ml-auto lg:px-12">
    <div class="flex justify-between mt-6">
      <div class="text-4xl mt-6">
        <h1 class="text-gray-500 font-bold text-4xl text">Usuários</h1>
      </div>
      <div class="flex flex-row-reverse">
        <form action="#" class="mt-1 flex space-x-1 p-4 items-end border rounded bg-white">
          <div class="flex flex-col">
            <input type="text" class="px-1 py-1 w-72 border outline-none rounded bg-white border-gray-200 text-sm" placeholder="Buscar por Nome, Destinatário, Etiqueta...">
          </div>

          <div class="flex flex-col">
            <select 
              required
              class="px-1 py-1 w-40 border outline-none rounded bg-white border-gray-200 text-sm text-gray-600">
              <option value="" disabled selected class="text-sm">Situação Postagem</option>
              <option value="postados">Postados</option>
              <option value="pendentes">Pendentes</option>
            </select>
          </div>

          <button 
            type="submit"
            class="text-white font-bold text-xs
            hover:bg-gray-700 rounded border 
            border-gray-500 bg-gray-500 px-2 py-1.5">
            Buscar
          </button>
        </form>
      </div>
    </div>
    <table
      class="mt-2 min-w-full table-auto ml-auto bg-white font-normal rounded shadow-lg
      text-sm text-left text-gray-500 border-collapse overflow-x-auto border-1">
      <thead class="text-xs text-gray-700 uppercase bg-gray-200">
        <tr>
            <th scope="col" class="px-6 py-3">
                Id
            </th>
            <th scope="col" class="px-9 py-3">
                Nome
            </th>
            <th scope="col" class="px-3 py-3">
                Situação
            </th>
            <th scope="col" class="px-6 py-3">
                Integração
            </th>
            <th scope="col" class="px-1 py-3">
                Tipo
            </th>
            <th scope="col" class="px-1 py-3">
                Postagem
            </th>
            <th scope="col" class="px-1 py-3">
                Ações
            </th>
        </tr>
      </thead>
      <tbody>
        @foreach ($data->list as $user)
        
            @php
            $status = '';
            if ($user->status == 'ACTIVE' || $user->status == 'NUVEMSHOP') {
                $status = '<span class="green"><i class="fa fa-check"></i> Ativo</span>';
            }
            if ($user->status == 'BLOCK') {
                $status = '<i class="fa fa-ban"></i> Bloqueado';
            }
            if ($user->status == 'INACTIVE') {
                $status = '<span class="red"><i class="fa fa-times"></i> Inativo</span>';
            }
            if ($user->status == '') {
                $status = '<span class="red"><i class="fa fa-hourglass-half"></i> Pendente</span>';
            }
            @endphp

            <tr style="cursor: pointer;">
                <td data-tg="nivel_1-{{ $user->id }}" class="table-date row_click">
                    {{ $user->id }}
                </td>
                <td data-tg="nivel_1-{{ $user->id }}" class="table-date row_click">

                    @if ($user->origem_plataforma_indica)
                        <span class="badge badge-warning spn-etiqueta">
                            Ação: {{ ucfirst($user->origem_plataforma_indica) }}
                        </span>    <br>
                    @endif
                    @if ($user->url_referer)
                        <span class="badge badge-warning spn-etiqueta">
                            Ação: {{ ucfirst($user->url_referer) }}
                        </span>    <br>
                    @endif

                    <strong><small>Nome:</small></strong> {{ $user->name }}<br>
                    <strong><small>Razão:</small></strong> {{ $user->razao_social }}

                    @if ($user->indicacao)
                        <br><strong><span style="color: blue;">Indicação:</span> <span style="color: green;">{{ $user->indicacao }}</span></strong>
                    @endif
                    @if ($user->plataforma_link || $user->plataforma)
                        <br><strong><span style="color: blue;">Plataforma:</span> {{ $user->plataforma ? $user->plataforma : 'Não informado' }}
                            @if ($user->plataforma_link) 

                                @php
                                $plataforma_link = $user->plataforma_link;
                                if (strlen($plataforma_link) > 40) {
                                    $plataforma_link = substr($plataforma_link, 0, 40) . '...';
                                }
                                @endphp
                                <br>
                                @if (preg_match('/http(.*?):\/\//', $user->plataforma_link))
                                    (<a href="{{ $user->plataforma_link }}" title="{{ $user->plataforma_link }}" target="_blank">{{ $plataforma_link }}</a>)</strong>
                            @else
                                (<span style="color: blue;">{{ $user->plataforma_link }}</span>)</strong>
                            @endif
                        @endif
                    @endif

                    @if ($user->domain_nuvem_shop)
                        <br><a href="{{ (!preg_match('/^http/', $user->domain_nuvem_shop) ? 'https://' : '') . $user->domain_nuvem_shop }}" target="_blank">{{ $user->domain_nuvem_shop }}</a> ({{ $user->store_id_nuvem_shop }})    
                    @endif
                    {!! isset($user->ref_indication) ? '<br><span class="badge badge-warning spn-etiqueta-pink">Indicado por: '.$user->afiliado.' </span>' : '' !!}

                </td>
                <td data-tg="nivel_1-{{ $user->id }}" class="table-date row_click">
                    {!! $status !!}
                    
                </td>
                <td data-tg="nivel_1-{{ $user->id }}" class="table-date row_click">
                    @if ($user->is_nuvem_shop)
                        <i class="fa fa-cloud blue"></i> Nuvem Shop
                    @elseif ($user->is_bling)
                        <img src="{{ asset('dist/img/bling-logo-300x207.png') }}" style="width: 80px;" class="thumbnail" alt="Integração Bling">
                    @elseif ($user->is_loja_integrada)
                        <img title="Cliente Loja Integrada" src="{{ asset('dist/img/loja-integrada-cubo.png') }}" />
                        Loja Integrada
                    @else
                        @if ($user->plataform_integration == 'Shopify')
                            <img src="{{ asset('dist/img/shopify.png') }}" style="width: 80px;" class="thumbnail" alt="Integração Shopify">
                        @elseif ($user->plataform_integration == 'Wix')
                            <img src="{{ asset('dist/img/wix.png') }}" style="width: 80px;" class="thumbnail" alt="Integração Wix">
                        @else
                            Cadastro
                        @endif
                    @endif

                    @if ($user->is_nuvem_shop && (Auth::user()->group_code == 'mandabem'))
                        <a href="#" class="btn-check-nuvem-instalation" data-path="{{ route('nuvemshop.check_instalation', $user->id) }}">
                            <i class="fa fa-cogs"></i>
                        </a>
                    @endif
                </td>
                <td data-tg="nivel_1-{{ $user->id }}" class="table-date row_click">
                    {{ $user->group_name }}
                </td>
                @if (Auth::user()->group_code == 'mandabem')
                <td class="text-center">
                        @if ($user->has_postagem)
                        <i class="fa fa-check"></i>
                        @endif
                    </td>
                    <td>
                        <div class="text-right" style="min-width: 100px;">
                            <button title="Editar usuário" class="btn-sm btn btn-primary btn-round btn-edit-user" data-path="{{ route('user.get', $user->id) }}">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button title="Excluir usuário" class="btn-sm btn btn-danger btn-round btn-remove-user" data-path="{{ route('user.remove', $user->id) }}">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </td>
                @endif
            </tr>

            <tr id="nivel_1-{{ $user->id }}" style="display: none;">
                <td colspan="7" >
                    <div class="envios-box panel-heading" style="margin-bottom: 5px; ">
                        <div class="row">
                            <div class="col-md-12 table-etiqueta">
                                <strong>
                                    <i class="fa fa-calendar"></i>
                                    Data Cadastro: {{ $user->date_insert }}
                                </strong>
                                <div class="text-right" style="margin-bottom: 5px;">
                                    @if (false)
                                        <a target="_blank" class=" btn btn-warning btn-sm" href="{{ route('total_credito_desconto', ['user_id' => $user->id]) }}">
                                            <i class="fa fa-search"></i>
                                            Buscar Pedido
                                        </a>
                                    @endif
                                    @if (true)
                                        @if (!$user->enable_log)
                                            <a target="_blank" class=" btn btn-success btn-sm btn-user-active-log" >
                                                <i class="fa fa-eye"></i>
                                                Ativar LOGs
                                            </a>
                                        @else
                                            <button class="btn btn-sm btn-success" onclick="ver_log_user({{ $user->id }})"><i class="fa fa-check"></i>  LOG Ativado, Ver Log</button>
                                        @endif
                                    @endif
                                    <a target="_blank" class=" btn btn-warning btn-sm" >
                                        <i class="fa fa-dollar-sign"></i>
                                        Ver Creditos Concedidos
                                    </a>
                                    <button class=" btn btn-danger btn-sm" id="btn-inativar-user" >
                                        <i class="fa fa-times"></i>
                                        Desativar Cadastro
                                    </button>
                                </div>
                                <div class="clear"></div>
                                <table class="table-blue" style="width: 100%;">
                                    <tr>
                                        <td width="30%"><strong><i class="fa fa-map-marker"></i> Endereço de Coleta</strong></td>
                                        <td width="40%"><strong><i class="fa fa-map-marker"></i> Resposável</strong></td>
                                        <td width="30%"><strong><i class="fa fa-building"></i> Razão Social</strong></td>

                                    </tr>
                                    <tr>
                                        <td>
                                            @if ($user->logradouro)
                                                {{ $user->logradouro . ',' . $user->numero }}<br>
                                            @endif
                                            {{ "CEP: " . $user->CEP . "<br>" }} 

                                            @if ($user->complemento)
                                                {{ $user->complemento }}<br>
                                            @endif

                                            {{ $user->bairro }} 
                                            @if ($user->cidade)
                                                {{ $user->cidade }}/ {{ $user->uf }}
                                            @endif

                                        </td>
                                        <td>
                                            <i class="fa fa-user"></i> Nome:
                                            {{ $user->name }}<br>

                                            <i class="fa fa-envelope"></i> E-mail:
                                            {{ $user->email }}<br>

                                            <i class="fa fa-phone"></i> Telefone:
                                            {{ $user->telefone }}<br>

                                            <i class="fa fa-credit-card"></i> CPF:
                                            {{ $user->cpf }}<br>
                                        </td>
                                        <td>
                                            <i class="fa fa-user"></i> {{ $user->razao_social }}<br>

                                            <i class="fa fa-globe"></i> CNPJ:
                                            {{ $user->cnpj }}<br>
                                            @if (Auth::user()->group_code == 'mandabem')
                                                <i class="fa fa-map-marker"></i> Paypal

                                                <button type="button" class="btn btn-danger">Bloquear</button>
                                            @endif
            <!--<button type="button" class="btn btn-warning"><i class="fa fa-ban"></i> Congelar Cobranças</button>-->

                                        </td>

                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
      </tbody>
    </table>
    
  </div>
</x-app-layout>


<script>





// $(document).on("click", "#btnInfoColUsuarios", function() {
//   var info = $(this).attr('data-id');
//   var rota = "{{ route('coleta.show', ['id' => ':idenvio']) }}"
//   rota = rota.replace(":idenvio", info);
//   $.ajax({
//     url: rota,
//     success: function(data) {
//       $('#linha_' + info).css("background", "#2d6984");
//       $('#linha_' + info).css("color", "white");
//       $('#idenvio_' + info).css("color", "white");
//       $('#detalhes_' + info).show();
//       $('#detalhes_' + info).append(data.html);
//       $('#detalhes_' + info).css("color", "white");
//     },
//   });
// });


</script>

