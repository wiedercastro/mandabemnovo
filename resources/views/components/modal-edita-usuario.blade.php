<div class="justify-center items-center hidden" id="modal_edit_usuario">
    <div class="fixed inset-0 px-2 z-10 flex items-start justify-center animate__animated animate__fadeIn pb-16">
        <div class="absolute inset-0 bg-gray-800 bg-opacity-75 transition-opacity"></div>

        <!-- Modal Content -->
        <div
            class="ml-56 inline-block align-bottom bg-white
          rounded-lg text-left shadow-xl mt-10 overflow-y-auto h-full
          transform transition-all sm:my-8 sm:align-middle sm:w-1/2">
            <!-- Modal Header -->
            <div class="text-white px-4 py-4 flex flex-row-reverse">
                <svg onclick="fechaModalEditaUsuario()" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                    class="w-8 h-8 stroke-gray-600 cursor-pointer">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </div>
            
            <div>
                <ul class="flex items-center border-b cursor-pointer ml-4">

                    <li class="menu-item text-blue-500 hover:border-r hover:border-l hover:border-t px-4 py-2 rounded" data-target="dados_usuario">
                        Dados do usuário
                    </li>
                    <li class="menu-item text-blue-500 hover:border-r hover:border-l hover:border-t px-4 py-2 rounded" data-target="integracao_webservice">
                        Integração Webservice
                    </li>
                    <li class="menu-item text-blue-500 hover:border-r hover:border-l hover:border-t px-4 py-2 rounded" data-target="configuracoes">
                        Configurações
                    </li>
                    <li class="menu-item text-blue-500 hover:border-r hover:border-l hover:border-t px-4 py-2 rounded" data-target="incluir_remetente">
                        Incluir remetente
                    </li>
                </ul>
            </div>

            <div id="conteudo_principal">

                {{-- Dados do usuário --}}
                <div id="dados_usuario" class="content">
                    <form action="#" method="POST" class="mt-2" id="submitFormEditaUsuario" class="">
                        @csrf
                        <input type="hidden" name="usuarioId" id="usuarioId">
                        <input type="hidden" name="csrfToken" value="{{ csrf_token() }}" id="csrfToken">
            
            
                        <div class="p-8 flex justify-between space-x-28">
                            {{--  DADOS GERAIS --}}
                            <div class="w-full">
                                <h1 class="text-gray-500 font-bold text-4xl text-2xl">Dados Gerais</h1>
            
                                <div class="mt-8">
                                    <label for="tipo_usuario" class="block text-gray-500 text-sm font-bold">Tipo de Usuário</label>
                                    <select name="tipo_usuario" id="tipo_usuario" class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                        <option value="cliente_com_contrato">Cliente com Contrato</option>
                                        <option value="cliente_sem_contrato">Cliente sem Contrato</option>
                                        <option value="cliente_franquia">Cliente franquia</option>
                                        <option value="auditor">Auditor</option>
                                        <option value="agencias">Agências</option>
                                    </select>
                                </div>
            
                                <div class="mt-2">
                                    <label for="usuario" class="block text-gray-500 text-sm font-bold">Usuário</label>
                                    <input type="text" name="usuario" id="usuario"
                                        class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                </div>
            
                                <div class="mt-2">
                                    <label for="senha" class="block text-gray-500 text-sm font-bold">Senha</label>
                                    <input type="password" name="senha" id="senha"
                                        class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                </div>
            
                                <div class="mt-2">
                                    <label for="ecommerce" class="block text-gray-500 text-sm font-bold">Ecommerce</label>
                                    <input type="text" name="ecommerce" id="ecommerce"
                                        class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                </div>
            
                                <div class="mt-2">
                                    <label for="status" class="block text-gray-500 text-sm font-bold">Status</label>
                                    <select name="status" id="status" class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                        <option value="ativo">ATIVO</option>
                                        <option value="bloqueado">BLOQUEADO</option>
                                    </select>
                                </div>
            
                                <div class="mt-2">
                                    <label for="cep" class="block text-gray-500 text-sm font-bold">CEP</label>
                                    <input type="text" name="cep" id="cep"
                                        class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                </div>
            
                                <div class="mt-2">
                                    <label for="logradouro" class="block text-gray-500 text-sm font-bold">Logradouro</label>
                                    <input type="text" name="logradouro" id="logradouro"
                                        class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                </div>
            
                                <div class="mt-2">
                                    <label for="numero" class="block text-gray-500 text-sm font-bold">Número</label>
                                    <input type="text" name="numero" id="numero"
                                        class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                </div>
            
                                <div class="mt-2">
                                    <label for="complemento" class="block text-gray-500 text-sm font-bold">Complemento</label>
                                    <input type="text" name="complemento" id="complemento"
                                        class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                </div>
            
                                <div class="mt-2">
                                    <label for="bairro" class="block text-gray-500 text-sm font-bold">Bairro</label>
                                    <input type="text" name="bairro" id="bairro"
                                        class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                </div>
            
                                <div class="mt-2">
                                    <label for="cidade" class="block text-gray-500 text-sm font-bold">Cidade</label>
                                    <input type="text" name="cidade" id="cidade"
                                        class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                </div>
            
                                <div class="mt-2">
                                    <label for="estado" class="block text-gray-500 text-sm font-bold">Estado</label>
                                    <input type="text" name="estado" id="estado"
                                        class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                </div>
            
                                <button
                                    class="text-sm mt-6 bg-yellow-500 hover:bg-yellow-600 text-white font-bold px-2 py-1 rounded flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                                    </svg>
                                    <p class="ml-1">Vincular afiliado</p>
                                </button>
                            </div>
            
                            {{-- RESPONSAVEL --}}
                            <div class="w-full">
                                <h1 class="text-gray-500 font-bold text-4xl text-2xl">Responsável</h1>
            
                                <div class="mt-6">
                                    <label for="nome_usuario" class="block text-gray-500 text-sm font-bold">Nome</label>
                                    <input type="text" name="nome_usuario" id="nome_usuario"
                                        class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                </div>
            
                                <div class="mt-2">
                                    <label for="email_usuario" class="block text-gray-500 text-sm font-bold">E-mail</label>
                                    <input type="email_usuario" name="email_usuario" id="email_usuario"
                                        class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                </div>
            
                                <div class="mt-2">
                                    <label for="telefone" class="block text-gray-500 text-sm font-bold">Telefone</label>
                                    <input type="text" name="telefone" id="telefone"
                                        class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                </div>
            
                                <div class="mt-2">
                                    <label for="tipo_emissao" class="block text-gray-500 text-sm font-bold">Tipo Emissão</label>
                                    <input type="text" name="tipo_emissao" id="tipo_emissao"
                                        class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                </div>
                                <div class="mt-2">
                                    <label for="cpf" class="block text-gray-500 text-sm font-bold">CPF</label>
                                    <input type="text" name="cpf" id="cpf"
                                        class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                </div>
            
                                <div class="mt-2">
                                    <label for="cnpj" class="block text-gray-500 text-sm font-bold">CNPJ</label>
                                    <input type="text" name="cnpj" id="cnpj"
                                        class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                </div>
            
                                <div class="mt-2">
                                    <label for="razao_social" class="block text-gray-500 text-sm font-bold">Razão Social</label>
                                    <input type="text" name="razao_social" id="razao_social"
                                        class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                </div>
            
                                <div class="mt-4">
                                    <label for="grupo_taxa" class="block text-gray-500 text-sm font-bold">Grupo Taxa (PAC & SEDEX)</label>
                                    <input type="text" name="grupo_taxa" id="grupo_taxa"
                                        class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                </div>
            
                                <div class="mt-2">
                                    <label for="grupo_taxa_mini" class="block text-gray-500 text-sm font-bold">Grupo Taxa PAC Mini</label>
                                    <input type="text" name="grupo_taxa_mini" id="grupo_taxa_mini"
                                        class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                </div>
            
                                <hr class="mt-4">
            
                                <div class="mt-4">
                                    <label for="link_indicacao" class="block text-gray-500 text-sm font-bold">Link de Indicação</label>
                                    <input type="text" name="link_indicacao" id="link_indicacao"
                                        class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                </div>
            
                                <hr class="mt-4">
            
                                <div class="mt-8">
                                    <label for="numero" class="block text-gray-500 text-sm font-bold">Plataforma</label>
                                    <input type="text" name="numero" id="numero"
                                        class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                </div>
            
                                <div class="mt-2">
                                    <label for="complemento" class="block text-gray-500 text-sm font-bold">Volume médio Envios</label>
                                    <input type="text" name="complemento" id="complemento"
                                        class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                </div>
            
                            </div>
                        </div>
            
                        <div class="flex flex-row-reverse mt-8 p-2">
                            <button type="button" onclick="fechaModalEditaUsuario()"
                                class="text-sm bg-red-500 hover:bg-red-600 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                                <p>Fechar</p>
                            </button>
            
                            <button
                                id="submitButtonEditUser"
                                class="text-sm bg-blue-500 hover:bg-blue-600 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>                      
                                <p class="ml-1">Salvar</p>
                            </button>
                        </div>
                    </form>
                </div>
    
                <div id="subMenu" class="hidden">
                    {{-- Integração Webservice --}}
                    <div id="integracao_webservice" class="content p-6">
                        <div class="shadow p-6 border rounded">
                            <div class="flex items-center p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-100" role="alert">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="flex-shrink-0 inline w-5 h-5 me-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                                </svg>                
                                <span class="sr-only">Info</span>
                                <div>
                                    <span class="font-medium">ATENÇÃO!</span> Usuário ainda não possui chaves de acesso ao WEB Service Manda Bem
                                </div>
                            </div>

                            <div class="flex flex-row-reverse mt-8">
                                <button onclick="fechaModalEditaUsuario()" type="button"
                                    class="text-sm bg-red-500 hover:bg-red-600 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                    <p>Fechar</p>
                                </button>
                
                                <button
                                    class="text-sm bg-blue-500 hover:bg-blue-600 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                    </svg>                      
                                    <p class="ml-1">Salvar</p>
                                </button>
                            </div>
                        </div>
                    </div>
    
                    {{-- Configurações --}}
                    <div id="configuracoes" class="content p-8">
                        <div class="shadow p-6 border rounded">
                            <form action="#" method="POST" class="mt-2" id="submitAddNovoRemetente">
                                @csrf
                                <input type="hidden" name="csrfToken" value="{{ csrf_token() }}" id="csrfToken">

                                <div class="flex justify-between space-x-28">

                                    <div class="flex flex-col w-full">
                                        {{-- GERAL --}}
                                        <div>
                                            <h1 class="text-2xl text-gray-500 font-bold">Geral</h1>
                                            <div class="mt-8">
                                                <label for="remetente" class="block text-gray-500 text-sm font-bold">Remetente</label>
                                                <input type="text" name="remetente" id="remetente" placeholder="Rremetente"
                                                    class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                            </div>
                        
                                            <div class="mt-2">
                                                <label for="cep" class="block text-gray-500 text-sm font-bold">Habilitar PLP</label>
                                                <select name="chave_pix" id="chave_pix" class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                                    <option value="sim">Sim</option>
                                                    <option value="nao">Não</option>
                                                </select>
                                            </div>
                        
                                            <div class="mt-2">
                                                <label for="logradouro" class="block text-gray-500 text-sm font-bold">Habilitar Seguro</label>
                                                <select name="chave_pix" id="chave_pix" class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                                    <option value="sim">Sim</option>
                                                    <option value="nao">Não</option>
                                                </select>
                                            </div>
                        
                                            <div class="mt-2">
                                                <label for="numero" class="block text-gray-500 text-sm font-bold">Etiquetas por Folha</label>
                                                <select name="chave_pix" id="chave_pix" class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                                    <option value="1">1 por folha</option>
                                                    <option value="2">2 por folha</option>
                                                    <option value="4">4 por folha</option>
                                                </select>
                                            </div>

                                            <div class="mt-2">
                                                <label for="numero" class="block text-gray-500 text-sm font-bold">Qtde Boletos pendentes permitidos</label>
                                                <select name="chave_pix" id="chave_pix" class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                </select>
                                            </div>

                                            <div class="mt-2">
                                                <label for="numero" class="block text-gray-500 text-sm font-bold">Hablitar visualização NFSe</label>
                                                <select name="chave_pix" id="chave_pix" class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                                    <option value="sim">Sim</option>
                                                    <option value="nao">Não</option>
                                                </select>
                                            </div>
                                        </div>

                                       <hr class="mt-8">

                                        {{-- Métodos de Envio --}}
                                        <div class="mt-10">
                                            <h1 class="text-2xl text-gray-500 font-bold">Métodos de Envio</h1>
                                            <div class="mt-2">
                                                <label for="habilitar_sedex" class="block text-gray-500 text-sm font-bold">Habilitar SEDEX</label>
                                                <select name="habilitar_sedex" id="habilitar_sedex" class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                                    <option value="sim">Sim</option>
                                                    <option value="nao">Não</option>
                                                </select>
                                            </div>
                        
                                            <div class="mt-2">
                                                <label for="habilitar_pac" class="block text-gray-500 text-sm font-bold">Habilitar PAC</label>
                                                <select name="habilitar_pac" id="habilitar_pac" class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                                    <option value="sim">Sim</option>
                                                    <option value="nao">Não</option>
                                                </select>
                                            </div>
                        
                                            <div class="mt-2">
                                                <label for="habilitar_pacmini" class="block text-gray-500 text-sm font-bold">Habilitar PACMINI</label>
                                                <select name="habilitar_pacmini" id="habilitar_pacmini" class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                                    <option value="sim">Sim</option>
                                                    <option value="nao">Não</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Afiliados --}}
                                    <div class="w-full">
                                        <h1 class="text-2xl  text-gray-500 font-bold">Afiliados</h1>
                                        <div class="mt-8">
                                            <label for="habilitar_afiliado" class="block text-gray-500 text-sm font-bold">Hablitar Aba Afiliado</label>
                                            <select name="habilitar_afiliado" id="habilitar_afiliado" class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                                <option value="sim">Sim</option>
                                                <option value="nao">Não</option>
                                            </select>
                                        </div>
                    
                                        <div class="mt-2">
                                            <label for="chave_pix" class="block text-gray-500 text-sm font-bold">Chave PIX</label>
                                            <select name="chave_pix" id="chave_pix" class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                                <option value="cpf">CPF</option>
                                                <option value="email">E-mail</option>
                                                <option value="telefone">Telefone</option>
                                            </select>
                                        </div>
                    
                                        <div class="mt-2">
                                            <label for="numero" class="block text-gray-500 text-sm font-bold">Número</label>
                                            <input type="text" name="numero" id="numero" placeholder="Número"
                                                class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-row-reverse mt-8">
                                    <button onclick="fechaModalEditaUsuario()" type="button"
                                        class="text-sm bg-red-500 hover:bg-red-600 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                        </svg>
                                        <p>Fechar</p>
                                    </button>
                    
                                    <button
                                        class="text-sm bg-blue-500 hover:bg-blue-600 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                        </svg>                      
                                        <p class="ml-1">Salvar</p>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
    
                    {{-- Incluir Remetente --}}
                    <div id="incluir_remetente" class="content p-8">
                       <div class="shadow p-6 border rounded">
                            <form action="#" method="POST" class="mt-2" id="submitAddNovoRemetente">
                                @csrf
                                <input type="hidden" name="csrfToken" value="{{ csrf_token() }}" id="csrfToken">
                
                               <div class="flex items-center text-gray-500 font-bold">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                                    </svg>
                                    <h1 class="text-4xl">Adicionar novo Remetente</h1>
                               </div>
                                
                                <div class="mt-8">
                                    <label for="remetente" class="block text-gray-500 text-sm font-bold">Remetente</label>
                                    <input type="text" name="remetente" id="remetente" placeholder="Rremetente"
                                        class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                </div>
            
                                <div class="mt-2">
                                    <label for="cep" class="block text-gray-500 text-sm font-bold">CEP</label>
                                    <input type="text" name="cep" id="cep" placeholder="CEP"
                                        class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                </div>
            
                                <div class="mt-2">
                                    <label for="logradouro" class="block text-gray-500 text-sm font-bold">Logradouro</label>
                                    <input type="text" name="logradouro" id="logradouro" placeholder="Logradouro"
                                        class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                </div>
            
                                <div class="mt-2">
                                    <label for="numero" class="block text-gray-500 text-sm font-bold">Número</label>
                                    <input type="text" name="numero" id="numero" placeholder="Número"
                                        class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                </div>
            
                                <div class="mt-2">
                                    <label for="complemento" class="block text-gray-500 text-sm font-bold">Complemento</label>
                                    <input type="text" name="complemento" id="complemento" placeholder="Complemento"
                                        class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                </div>
            
                                <div class="mt-2">
                                    <label for="bairro" class="block text-gray-500 text-sm font-bold">Bairro</label>
                                    <input type="text" name="bairro" id="bairro" placeholder="Bairro"
                                        class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                </div>
            
                                <div class="mt-2">
                                    <label for="cidade" class="block text-gray-500 text-sm font-bold">Cidade</label>
                                    <input type="text" name="cidade" id="cidade" placeholder="Cidade"
                                        class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                </div>
            
                                <div class="mt-2">
                                    <label for="estado" class="block text-gray-500 text-sm font-bold">Estado</label>
                                    <input type="text" name="estado" id="estado" placeholder="Estado"
                                        class="text-gray-700 shadow p-1 w-full border-1 rounded outline-none border-gray-200 focus:border-blue-500">
                                </div>

                                <div class="flex flex-row-reverse mt-8">
                                    <button onclick="fechaModalEditaUsuario()" type="button"
                                        class="text-sm bg-red-500 hover:bg-red-600 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                        </svg>
                                        <p>Fechar</p>
                                    </button>
                    
                                    <button
                                        class="text-sm bg-blue-500 hover:bg-blue-600 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                        </svg>                      
                                        <p class="ml-1">Salvar</p>
                                    </button>
                                </div>
                            </form>
                       </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>

document.addEventListener('DOMContentLoaded', function () {
    const menuItems = document.querySelectorAll('.menu-item');
    const contents = document.querySelectorAll('.content');

    // add o evento de clique em cada item do menu
    menuItems.forEach(item => {
        item.addEventListener('click', function () {
            const target = this.getAttribute('data-target');
            let subMenu = document.getElementById('subMenu')

            if (target == 'integracao_webservice' || target == 'incluir_remetente' || target == 'configuracoes') {
                subMenu.classList.remove('hidden');
                
            }

            // oculta todos os conteúdos
            contents.forEach(content => {
                content.style.display = 'none';
            });

            // mostra o conteúdo correspondente ao item do menu clicado
            document.getElementById(target).style.display = 'block';

            menuItems.forEach(item => {
                item.classList.remove('selected');
            });

            this.classList.add('selected');
        });
    });
});


</script>