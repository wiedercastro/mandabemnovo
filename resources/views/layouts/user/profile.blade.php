<x-app-layout>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


    <div class="w-5/6 ml-auto lg:px-12" style="border: 0px solid red;">
        <div class="text-4xl" style="margin-top:-25px">
            <h1 style="color:#728189"><b>Meus Dados</b></h1>
        </div>
        <div class="w-full mx-auto bg-white p-8 border rounded-md mt-8">


                <div class="grid grid-cols-2 gap-4" s>
                    <!-- Coluna 1 -->
                    <div>

                        <h5 class="text-2xl font-semibold mb-6">Geral: </h5>
                        
                        <div class=" mb-4 mr-4">
                            <label  class="block text-gray-700 text-sm font-bold mb-2">{{ $fields_form['name']['label'] }}</label>
                            <input type="{{ $fields_form['name']['type'] }}" value="{{ $fields_form['name']['default_value'] }}" @if($fields_form['name']['disabled']) disabled @endif
                            class="w-64 bg-gray-200 text-gray-700 border border-gray-200 " style="width: 25rem !important;" >
                        </div>
                        <div class="w-full mb-4 mr-4">
                            <label  class="block text-gray-700 text-sm font-bold mb-2">{{ $fields_form['razao_social']['label'] }}</label>
                            <input type="{{ $fields_form['razao_social']['type'] }}" value="{{ $fields_form['razao_social']['default_value'] }}" @if($fields_form['razao_social']['disabled']) disabled @endif
                            class="w-64 bg-gray-200 text-gray-700 border border-gray-200 " style="width: 25rem !important;">

                        </div>
                        <div class="w-full mb-4 mr-4">    
                            <label  class="block text-gray-700 text-sm font-bold mb-2">{{ $fields_form['name_ecommerce']['label'] }}</label>
                            <input type="text" value="{{ $fields_form['name_ecommerce']['default_value'] }}" @if($fields_form['name_ecommerce']['disabled']) disabled @endif
                            class="w-64 bg-gray-200 text-gray-700 border border-gray-200 " style="width: 25rem !important;">
                        </div>
                        <hr>
                        <h5 class="text-2xl font-semibold mb-6">Meu Endereço padrão:</h5>
                            
                        <div class="w-full mb-4 mr-4">    
                            <label  class="block text-gray-700 text-sm font-bold mb-2">{{ $fields_form['CEP']['label'] }}</label>
                            <input type="text" value="{{ $fields_form['CEP']['default_value'] }}" @if($fields_form['CEP']['disabled']) disabled @endif 
                            class="w-64 bg-gray-200 text-gray-700 border border-gray-200 " style="width: 25rem !important;">
                        </div>
                        <div class="w-full mb-4 mr-4">    
                            <label  class="block text-gray-700 text-sm font-bold mb-2">{{ $fields_form['logradouro']['label'] }}</label>
                            <input type="text" value="{{ $fields_form['logradouro']['default_value'] }}" @if($fields_form['logradouro']['disabled']) disabled @endif
                            class="w-64 bg-gray-200 text-gray-700 border border-gray-200 " style="width: 25rem !important;">
                        </div>
                        <div class="w-full mb-4 mr-4">
                            <label  class="block text-gray-700 text-sm font-bold mb-2">{{ $fields_form['numero']['label'] }}</label>
                            <input type="text" value="{{ $fields_form['numero']['default_value'] }}" @if($fields_form['numero']['disabled']) disabled @endif
                            class="w-64 bg-gray-200 text-gray-700 border border-gray-200 " style="width: 25rem !important;">
                        </div>
                        <div class="w-full mb-4 mr-4">
                            <label  class="block text-gray-700 text-sm font-bold mb-2">{{ $fields_form['complemento']['label'] }}</label>
                            <input type="text" value="{{ $fields_form['complemento']['default_value'] }}" @if($fields_form['complemento']['disabled']) disabled @endif
                            class="w-64 bg-gray-200 text-gray-700 border border-gray-200 " style="width: 25rem !important;">
                        </div>
                        <div class="w-full mb-4 mr-4">
                            <label  class="block text-gray-700 text-sm font-bold mb-2">{{ $fields_form['bairro']['label'] }}</label>
                            <input type="text" value="{{ $fields_form['bairro']['default_value'] }}" @if($fields_form['bairro']['disabled']) disabled @endif
                            class="w-64 bg-gray-200 text-gray-700 border border-gray-200 " style="width: 25rem !important;">
                        </div>
                        <div class="w-full mb-4 mr-4">
                            <label  class="block text-gray-700 text-sm font-bold mb-2">{{ $fields_form['cidade']['label'] }}</label>
                            <input type="text" value="{{ $fields_form['cidade']['default_value'] }}" @if($fields_form['cidade']['disabled']) disabled @endif
                            class="w-64 bg-gray-200 text-gray-700 border border-gray-200 " style="width: 25rem !important;">
                        </div>
                        <div class="w-full mb-4 mr-4">
                            <label  class="block text-gray-700 text-sm font-bold mb-2">{{ $fields_form['uf']['label'] }}</label>
                            <input type="text" value="{{ $fields_form['uf']['default_value'] }}" @if($fields_form['uf']['disabled']) disabled @endif
                            class="w-64 bg-gray-200 text-gray-700 border border-gray-200 " style="width: 25rem !important;">
                        </div>
                        <p>
                            * Caso seja necessário alteração dos dados, por gentileza entre em contato com o nosso suporte pelo botão WhatsApp no canto inferior direito.
                        </p>
                    </div>
                    <div>
                        <h5 class="text-2xl font-semibold mb-6">
                            Meus endereços adicionais<br>
                            <small>
                                <i class="fa fa-info-circle"></i>
                                Endereços adicionais para envio a partir de outro Remetente aparecerão aqui.<br>
                                Se você precise incluir por gentileza solicite ao nosso Suporte
                            </small>
                        </h5>
                        @if (true)
                            <hr>
                            <div id="content_for_remetente"></div>
                        @endif
                    </div>
                </div>
        </div>
    </div>
</x-app-layout>
