<x-app-layout>

    <div class="w-5/6 ml-auto lg:px-12 pb-10 animate__animated animate__fadeIn">
  
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
                class="px-1 py-1 w-40 border outline-none rounded bg-white border-gray-200 text-sm text-gray-500">
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
        text-sm text-left text-gray-500 border-collapse overflow-x-auto border-1 cursor-pointer">
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
                  Ações
              </th>
          </tr>
        </thead>
        <tbody>
          @foreach ($data->list as $user)
              <tr onclick="expandeDetalhesUsuario({{$user->id}})" class="bg-white hover:bg-gray-100 border-b rounded-full font-light" id="tr_linha_{{ $user->id }}">
                  <td data-tg="nivel_1-{{ $user->id }}" class="px-6 py-4">
                      {{ $user->id }}
                  </td>
                  <td class="px-6 py-4 flex flex-col text-xs">
                      <div class="flex">
                          <p class="text-gray-500 font-bold" id="nome">Nome:</p>
                          <strong class="ml-1">{{ $user->name }}</strong>
                      </div>
                      <div class="flex">
                          <p class="text-gray-500 font-bold" id="razao">Razão:</p>
                          <strong class="ml-1">{{ $user->razao_social }}</strong>
                      </div>
                      <div class="font-bold">
                          @if ($user->indicacao)
                              <span class="text-blue-600">Indicação:</span> <span class="text-green-700">{{ $user->indicacao }}</span>
                          @endif
                      </div>
                      <div>
                          @if ($user->plataforma_link || $user->plataforma)
                              <span class="text-blue-600 font-bold">Plataforma:</span> {{ $user->plataforma ? $user->plataforma : 'Não informado' }}
                              @if ($user->plataforma_link) 
                                  @php
                                  $plataforma_link = $user->plataforma_link;
                                  if (strlen($plataforma_link) > 40) {
                                      $plataforma_link = substr($plataforma_link, 0, 40) . '...';
                                  }
                                  @endphp
                              
                                  @if (preg_match('/http(.*?):\/\//', $user->plataforma_link))
                                      <div class="flex items-center">
                                          <p class="text-blue-600 font-bold">Link:</p>
                                          <a href="{{ $user->plataforma_link }}" title="{{ $user->plataforma_link }}" target="_blank" class="ml-1">{{ $plataforma_link }}</a>
                                      </div>
                                  @else
                                      (<span class="text-blue-600 font-bold">{{ $user->plataforma_link }}</span>)
                                  @endif
                              @endif
                          @endif
                      </div>
                      <div class="font-bold mt-2">
                          @if ($user->domain_nuvem_shop)
                              <a 
                                  class="text-blue-600"
                                  href="{{ (!preg_match('/^http/', $user->domain_nuvem_shop) ? 'https://' : '') . $user->domain_nuvem_shop }}" target="_blank">
                                  {{ $user->domain_nuvem_shop }}
                              </a>
                              ({{ $user->store_id_nuvem_shop }})    
                          @endif
                      
                          @if (isset($user->ref_indication))
                              <span class="flex items-center bg-cyan-600 text-white px-2 rounded w-full" style="width: 270px;">
                                  <p class="">Indicado por:</p>
                                  <span class="ml-1">{{ $user->afiliado }}</span>
                              </span>
                          @endif
                      </div>
                      
                  </td>
                  <td class="px-6 py-4 font-medium" data-tg="nivel_1-{{ $user->id }}">
                      @php
                          $status = '';
                          switch ($user->status) {
                              case 'ACTIVE':
                              case 'NUVEMSHOP':
                                  $status = '<span class="text-green-600"><i class="fa fa-check"></i> Ativo</span>';
                                  break;
                              case 'BLOCK':
                                  $status = '<i class="fa fa-ban"></i> Bloqueado';
                                  break;
                              case 'INACTIVE':
                                  $status = '<span class="text-yellow-600"><i class="fa fa-times"></i> Inativo</span>';
                                  break;
                              default:
                                  $status = '<span class="text-red-600"><i class="fa fa-hourglass-half"></i> Pendente</span>';
                          }
                      @endphp
                      {!! $status !!}
                  </td>
                  <td data-tg="nivel_1-{{ $user->id }}" class="px-2 py-2">
                      @if ($user->is_nuvem_shop)
                          <i class="fa fa-cloud text-blue-500"></i> Nuvem Shop
                      @elseif ($user->is_bling)
                          <img src="{{ asset('dist/img/bling-logo-300x207.png') }}" style="width: 80px;" class="thumbnail" alt="Integração Bling">
                      @elseif ($user->is_loja_integrada)
                          <img title="Cliente Loja Integrada" src="{{ asset('dist/img/loja-integrada-cubo.png') }}" />
                          Loja Integrada
                      @else
                          @switch($user->plataform_integration)
                              @case('Shopify')
                                  <img src="{{ asset('dist/img/shopify.png') }}" style="width: 80px;" class="thumbnail" alt="Integração Shopify">
                                  @break
                              @case('Wix')
                                  <img src="{{ asset('dist/img/wix.png') }}" style="width: 80px;" class="thumbnail" alt="Integração Wix">
                                  @break
                              @default
                                  Cadastro
                          @endswitch
                      @endif
                  
                      @if ($user->is_nuvem_shop && (Auth::user()->group_code == 'mandabem'))
                          <a href="#" class="btn-check-nuvem-instalation" data-path="{{ route('nuvemshop.check_instalation', $user->id) }}">
                              <i class="fa fa-cogs"></i>
                          </a>
                      @endif
                  </td>
                  <td data-tg="nivel_1-{{ $user->id }}" class="px-2 py-2">
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
                  <td class="px-2 py-2">
                      <div class="flex cursor-pointer">
                          <button onclick="abreModalEditaUsuario({{$user->id}})">
                              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                  stroke-width="1.5" stroke="currentColor"
                                  class="w-5 h-5 sm:w-5 sm:h-6 stroke-blue-500">
                                  <path stroke-linecap="round" stroke-linejoin="round"
                                      d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                              </svg>
                          </button>
  
                          <button>
                              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                  stroke-width="1.5" stroke="currentColor"
                                  class="w-4 h-4 sm:w-5 sm:h-6 stroke-red-600">
                                  <path stroke-linecap="round" stroke-linejoin="round"
                                      d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                              </svg>
                          </button>
                      </div>
                  </td>
              </tr>

             
            <tr id="linha_tabela_{{ $user->id }}" class="hidden"> 
                <td colspan="7" class="p-4 bg-gray-50 border">
                    <div class="flex justify-between">
                        <strong>
                            <i class="fa fa-calendar"></i>
                            Data Cadastro: {{ $user->date_insert }}
                        </strong>
    
                        <div class="space-x-2 flex items-center">
                            @if (!$user->enable_log)
                                <button class="text-sm bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded ml-2 flex items-center">
                                    <i class="fa fa-eye"></i>
                                    <span class="ml-1">Ativar LOGs</span>
                                </button>
                            @else
                                <button onclick="ver_log_user({{ $user->id }})" class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded ml-2 flex items-center">
                                    <i class="fa fa-eye"></i>
                                    <span class="ml-1">LOG Ativado, Ver Log</span>
                                </button>
                            @endif

                            <button class="text-sm bg-yellow-400 hover:bg-yellow-600 text-black px-2 py-1 rounded ml-2 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>                                  
                                <span class="ml-1">Ver Creditos Concedidos</span>                               
                            </button>
                            <button class="text-sm bg-red-700 hover:bg-red-800 text-white px-2 py-1 rounded ml-2 flex items-center">
                                <i class="fa fa-times"></i>
                                <span class="ml-1">Desativar Cadastro</span>                      
                            </button>
                        </div>
                    </div>
                    <div class="flex justify-between w-4/5 mt-4">
                        <div>
                            <strong><i class="fa fa-map-marker"></i> Endereço de Coleta</strong>
                            <div class="mt-6">
                                @if ($user->logradouro)
                                    <p>{{ $user->logradouro . ',' . $user->numero }}</p>
                                @endif

                                @if ($user->complemento)
                                    <p>{{ $user->complemento }}</p>
                                @endif

                                @if ($user->cidade)
                                    <p>{{ "$user->bairro $user->cidade" }}/ {{ $user->uf }}</p>
                                @endif

                                @if ($user->CEP)
                                    <div class="flex items-center">
                                        <p class="font-bold">CEP:</p>
                                        <span class="ml-1">{{ $user->CEP }}</span>
                                    </div>
                                @endif

                            </div>
                        </div>
                        <div>
                            <strong><i class="fa fa-map-marker"></i> Responsável</strong>
                            <div class="mt-6">
                                <div class="flex items-center">
                                    <i class="fa fa-user"></i>
                                    <p class="ml-1">Nome:</p>
                                    <span class="ml-1">{{ $user->name }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fa fa-envelope"></i>
                                    <p class="ml-1">E-mail:</p>
                                    <span class="ml-1">{{ $user->email }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fa fa-phone"></i>
                                    <p class="ml-1">Telefone:</p>
                                    <span class="ml-1">{{ $user->telefone }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fa fa-credit-card"></i>
                                    <p class="ml-1">CPF:</p>
                                    <span class="ml-1">{{ $user->cpf }}</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <strong><i class="fa fa-building"></i> Razão Social</strong>
                            <div class="mt-6">
                                <i class="fa fa-user"></i> {{ $user->razao_social }}<br>
                                <i class="fa fa-globe"></i> CNPJ: {{ $user->cnpj ? $user->cnpj : "Sem CNPJ registrado" }}<br>
                                @if (Auth::user()->group_code == 'mandabem')
                                    <i class="fa fa-map-marker"></i> Paypal
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-row-reverse">
                        <button class="text-sm bg-red-700 hover:bg-red-800 text-white px-2 py-1 rounded ml-2 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>    
                            <span class="ml-1">Bloquear</span>                      
                        </button>
                    </div>
                </td>                
            </tr>

          @endforeach
        </tbody>
      </table>

      <x-modal-edita-usuario />
      
    </div>
  </x-app-layout>

<script>


const expandeDetalhesUsuario = (idUsuario) => {
    // Obtém o elemento da tabela a ser expandido
    const expandeTabela = document.getElementById(`linha_tabela_${idUsuario}`);

    expandeTabela.classList.toggle("hidden");
    
    // Obtém a linha clicada
    const linhaClicada = document.getElementById(`tr_linha_${idUsuario}`);
    
    // Obtém os elementos de nome e razão dentro da linha clicada
    const nome = linhaClicada.querySelector('#nome');
    const razao = linhaClicada.querySelector('#razao');

    // Adiciona ou remove classes para mudar a cor de fundo da linha
    linhaClicada.classList.toggle('bg-[#154864]');
    linhaClicada.classList.toggle('text-white');
    linhaClicada.classList.toggle('hover:bg-[#154864]');

    // Altera a cor do texto para branco
    nome.classList.toggle('text-white');
    razao.classList.toggle('text-white');
};


</script>
  