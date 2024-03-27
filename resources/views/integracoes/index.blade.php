<x-app-layout>
  <div class="sm:w-5/6 w-full ml-auto lg:px-12 pb-10 p-4">
    <div class="flex justify-center sm:hidden">
      <img src="{{asset('images/logo_mandabem_az.png')}}" alt="" class="w-32"/>
    </div>
    <div class="sm:w-4/5 w-full bg-white shadow-md rounded p-6 mt-10 sm:mt-0">
      {{--  INICIO FORM Configurações Gerais --}}
      <div class="border border-1 rounded p-2">
        <h1 class="font-bold text-xl text-gray-700">Configurações Gerais</h1>
        <p class="text-sm italic text-gray-600">
          Obs: Configurações aplicadas apenas às nossas integrações 
          <b class="underline">Loja Integrada</b>,
          <b class="underline">Nuvemshop</b> e
          <b class="underline">Woocommerce</b>
        </p>
        <form action="">
          <div class="mt-2">
            <div class="flex items-center">
              <label for="prazo_extra" class="block text-gray-500 font-bold text-sm">Prazo Extra</label>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-1 w-5 h-5 stroke-blue-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
              </svg>            
            </div>
            <input placeholder="Digite o cliente que devolverá..." type="text" name="prazo_extra" id="prazo_extra" class="px-1 py-2 sm:sm:w-96 w-full w-full border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600 mt-1">
          </div>
          <div class="mt-4">
            <div class="flex items-center">
              <label for="formato_valor_adicional" class="block text-gray-500 font-bold text-sm">Formato Valor Adicional</label>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-1 w-5 h-5 stroke-blue-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
              </svg>            
            </div>
            <select name="formato_valor_adicional" id="formato_valor_adicional" class="px-1 py-2 sm:w-96 w-full border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600 mt-1">
              <option value="fixo">Fixo</option>
              <option value="percentual">Percentual %</option>
            </select>
          </div>
          <div class="mt-4">
            <div class="flex items-center">
              <label for="valor_adicional" class="block text-gray-500 font-bold text-sm">Valor Adicional</label>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-1 w-5 h-5 stroke-blue-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
              </svg>            
            </div>
            <input placeholder="Valor Adicional" type="text" name="valor_adicional" id="valor_adicional" class="px-1 py-2 sm:w-96 w-full border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600 mt-1">
          </div>
          <div class="mt-4">
            <div class="flex items-center">
              <label for="desconto_carrinho" class="block text-gray-500 font-bold text-sm">Permitir Desconto Carrinho</label>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-1 w-5 h-5 stroke-blue-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
              </svg>            
            </div>
            <select name="desconto_carrinho" id="desconto_carrinho" class="px-1 py-2 sm:w-96 w-full border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600 mt-1">
              <option value="sim">Sim</option>
              <option value="nao">Não</option>
            </select>
          </div>
          <div class="mt-4">
            <div class="flex items-center">
              <label for="seguro" class="block text-gray-500 font-bold text-sm">Habilitar Seguro</label>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-1 w-5 h-5 stroke-blue-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
              </svg>            
            </div>
            <select name="seguro" id="seguro" class="px-1 py-2 sm:w-96 w-full border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600 mt-1">
              <option value="sim">Sim</option>
              <option value="nao">Não</option>
            </select>
          </div>
          <button
            class="mt-2 bg-red-500 hover:bg-red-700 text-white font-bold px-2 py-1 rounded flex items-center text-sm">    
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
            </svg>                                                   
            <p class="ml-1">Salvar</p>
          </button>
        </form>
      </div>
      {{--  FINAL FORM Configurações Gerais --}}

      <p class="w-full bg-gray-300 p-1 mt-4"></p>

     {{--  INICIO FORM SHOPIFY --}}
      <div class="border border-1 rounded p-2 mt-4">
        <img src="images/shopify.jpg" class="h-20 w-20">
        <div class="flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 stroke-blue-500">
            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
          </svg>  
          <p class="text-sm italic text-gray-600">
            Aqui você deverá preencher, em centimetros, a média de cubagem da embalagem de seus produtos
            vendidos dentro da sua plataforma Shopify
          </p>
        </div>
        <hr class="mt-2">
        <form action="" class="ml-0 sm:ml-4 mt-4">
          <div class="mt-2">
            <div class="flex items-center">
              <label for="altura_media" class="block text-gray-500 font-bold text-sm">Altura Média</label>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-1 w-5 h-5 stroke-blue-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
              </svg>            
            </div>
            <input type="text" name="altura_media" id="altura_media" class="px-1 py-2 sm:w-96 w-full border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600 mt-1">
          </div>
          <div class="mt-2">
            <div class="flex items-center">
              <label for="largura_media" class="block text-gray-500 font-bold text-sm">Largura Média</label>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-1 w-5 h-5 stroke-blue-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
              </svg>            
            </div>
            <input type="text" name="largura_media" id="largura_media" class="px-1 py-2 sm:w-96 w-full border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600 mt-1">
          </div>
          <div class="mt-2">
            <div class="flex items-center">
              <label for="comprimento_medio" class="block text-gray-500 font-bold text-sm">Comprimento Médio</label>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-1 w-5 h-5 stroke-blue-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
              </svg>            
            </div>
            <input type="text" name="comprimento_medio" id="comprimento_medio" class="px-1 py-2 sm:w-96 w-full border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600 mt-1">
          </div>
          <button
            class="mt-2 bg-gray-400 hover:bg-gray-500 text-white font-bold px-2 py-1 rounded flex items-center text-sm">    
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
            </svg>                                                   
            <p class="ml-1">Salvar Shopify</p>
          </button>
        </form>
      </div>
      {{--  FINAL FORM SHOPIFY --}}

      <p class="w-full bg-gray-300 p-1 mt-4"></p>

      {{--  INICIO FORM WIX --}}
      <div class="border border-1 rounded p-2 mt-4">
        <img src="images/wix.png" class="w-20">
        <div class="flex items-center mt-6">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 stroke-blue-500">
            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
          </svg>  
          <p class="text-sm italic text-gray-600">
            Aqui você deverá preencher, em centimetros, a média de cubagem da embalagem de seus produtos
            vendidos dentro da sua plataforma Wix
          </p>
        </div>
        <hr class="mt-2">
        <form action="" class="ml-4 mt-4">
          <div class="mt-2">
            <div class="flex items-center">
              <label for="altura_media" class="block text-gray-500 font-bold text-sm">Altura Média</label>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-1 w-5 h-5 stroke-blue-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
              </svg>            
            </div>
            <input placeholder="Altura Média" type="text" name="altura_media" id="altura_media" class="px-1 py-2 sm:w-96 w-full border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600 mt-1">
          </div>
          <div class="mt-2">
            <div class="flex items-center">
              <label for="largura_media" class="block text-gray-500 font-bold text-sm">Largura Média</label>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-1 w-5 h-5 stroke-blue-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
              </svg>            
            </div>
            <input placeholder="Largura Média" type="text" name="largura_media" id="largura_media" class="px-1 py-2 sm:w-96 w-full border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600 mt-1">
          </div>
          <div class="mt-2">
            <div class="flex items-center">
              <label for="comprimento_medio" class="block text-gray-500 font-bold text-sm">Comprimento Médio</label>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-1 w-5 h-5 stroke-blue-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
              </svg>            
            </div>
            <input placeholder="Comprimento Médio" type="text" name="comprimento_medio" id="comprimento_medio" class="px-1 py-2 sm:w-96 w-full border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600 mt-1">
          </div>
          <button
            class="mt-2 bg-gray-700 hover:bg-gray-800 text-white font-bold px-2 py-1 rounded flex items-center text-sm">    
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
            </svg>                                                   
            <p class="ml-1">Salvar WIX</p>
          </button>
        </form>
      </div>
      {{--  FINAL FORM WIX --}}

      <p class="w-full bg-gray-300 p-1 mt-4"></p>

      {{--  INICIO FORM BLING --}}
      <div class="border border-1 rounded p-2 mt-4">
        <div class="bg-[#34AD70] w-28 p-1">
          <img src="images/logo_bling.png" class="w-28">
        </div>
        <div class="flex items-center mt-4">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 stroke-blue-500">
            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
          </svg>  
          <p class="text-sm italic text-gray-600">
            Se você usa a Bling, é possível inserir a chave da sua API para integrar os seus pedidos à nossa Plataforma,<br>
            Dessa forma você pode importar os pedidos de forma automática, sem necessidade de digitação.
          </p>
        </div>
        <hr class="mt-2">
        <form action="" class="ml-4 mt-4">
          <div class="mt-2">
            <div class="flex items-center">
              <label for="altura_media" class="block text-gray-500 font-bold text-sm">API Key</label>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-1 w-5 h-5 stroke-blue-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
              </svg>            
            </div>
            <input value="" type="text" name="altura_media" id="altura_media" class="px-1 py-2 w-1/2 border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600 mt-1">
          </div>
          <p class="mt-6 text-xs">Dúvidas de como integrar? 
            <a 
              class="text-blue-500 underline"
              href="https://ajuda.bling.com.br/hc/pt-br/articles/360046391854-Integra%C3%A7%C3%A3o-com-a-Manda-Bem" 
              target="_blank">acesse nosso manual
            </a>.
          </p>
          <button
            class="mt-2 bg-[#34AD70] hover:bg-green-700 text-white font-bold px-2 py-1 rounded flex items-center text-sm">    
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
            </svg>                                                   
            <p class="ml-1">Salvar Bling</p>
          </button>
        </form>
      </div>
      {{--  FINAL FORM BLING --}}
      
      <p class="w-full bg-gray-300 p-1 mt-4"></p>

      {{--  INICIO FORM TINY --}}
      <div class="border border-1 rounded p-2 mt-4">
        <img src="images/logo-tiny.png" class="w-28 ml-2">
        <div class="flex items-center mt-4">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 stroke-blue-500">
            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
          </svg>  
          <p class="text-sm italic text-gray-600">
            Se você usa a Tiny, é possível inserir a chave da sua API para integrar os seus pedidos à nossa Plataforma,<br>
            Dessa forma você pode importar os pedidos de forma automática, sem necessidade de digitação.
          </p>
        </div>
        <hr class="mt-2">
        <form action="" class="ml-4 mt-4">
          <div class="mt-2">
            <div class="flex items-center">
              <label for="altura_media" class="block text-gray-500 font-bold text-sm">API Key</label>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-1 w-5 h-5 stroke-blue-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
              </svg>            
            </div>
            <input value="" type="text" name="altura_media" id="altura_media" class="px-1 py-2 w-1/2 border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600 mt-1">
          </div>
          <p class="mt-6 text-xs">Dúvidas de como integrar? 
            <a 
              class="text-blue-500 underline"
              href="https://ajuda.bling.com.br/hc/pt-br/articles/360046391854-Integra%C3%A7%C3%A3o-com-a-Manda-Bem" 
              target="_blank">acesse nosso manual
            </a>.
          </p>
          <button
            class="mt-2 bg-blue-500 hover:bg-blue-700 text-white font-bold px-2 py-1 rounded flex items-center text-sm">    
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
            </svg>                                                   
            <p class="ml-1">Salvar Tiny</p>
          </button>
        </form>
      </div>
      {{--  FINAL FORM TINY --}}

      <p class="w-full bg-gray-300 p-1 mt-4"></p>

      {{--  INICIO FORM LINX --}}
      <div class="border border-1 rounded p-2 mt-4">
        <img src="images/linx.jpg" class="w-28 ml-2">
        <div class="flex items-center mt-4">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 stroke-blue-500">
            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
          </svg>  
          <p class="text-sm italic text-gray-600">
            Se você usa a Linx, é possível inserir a chave da sua URL para integrar os seus pedidos à nossa Plataforma,<br>
            Dessa forma você pode importar os pedidos, sem necessidade de digitação.
          </p>
        </div>
        <hr class="mt-2">
        <form action="" class="ml-4 mt-4">
          <div class="mt-2">
            <div class="flex items-center">
              <label for="url" class="block text-gray-500 font-bold text-sm">URL</label>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-1 w-5 h-5 stroke-blue-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
              </svg>            
            </div>
            <input value="https://linxcommerce.layer.core.dcg.com.br" type="text" name="url" id="url" class="px-1 py-2 w-1/2 border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600 mt-1">
          </div>
          <p class="mt-6 text-xs">Dúvidas de como integrar? 
            <a 
              class="text-blue-500 underline"
              href="https://ajuda.bling.com.br/hc/pt-br/articles/360046391854-Integra%C3%A7%C3%A3o-com-a-Manda-Bem" 
              target="_blank">acesse nosso manual
            </a>.
          </p>
          <button
            class="mt-2 bg-orange-500 hover:bg-orange-700 text-white font-bold px-2 py-1 rounded flex items-center text-sm">    
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
            </svg>                                                   
            <p class="ml-1">Salvar Linx</p>
          </button>
        </form>
      </div>
      {{--  FINAL FORM LINX --}}

      <p class="w-full bg-gray-300 p-1 mt-4"></p>

      {{--  INICIO FORM YAMPI --}}
      <div class="border border-1 rounded p-2 mt-4">
        <img src="images/logo-yampi.png" class="w-28 ml-2">
        <div class="flex items-center mt-4">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 stroke-blue-500">
            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
          </svg>  
          <p class="text-sm italic text-gray-600">
            Se você usa a Yampi, é possível inserir a chave da sua URL para integrar os seus pedidos à nossa Plataforma,<br>
            Dessa forma você pode importar os pedidos de forma automática, sem necessidade de digitação.
          </p>
        </div>
        <hr class="mt-2">
        <form action="" class="ml-4 mt-4">
          <div class="mt-2">
            <div class="flex items-center">
              <label for="alias" class="block text-gray-500 font-bold text-sm">Alias</label>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-1 w-5 h-5 stroke-blue-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
              </svg>            
            </div>
            <input value="seabird" type="text" name="alias" id="alias" class="px-1 py-2 w-1/2 border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600 mt-1">
          </div>
          <div class="mt-2">
            <div class="flex items-center">
              <label for="token" class="block text-gray-500 font-bold text-sm">Token</label>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-1 w-5 h-5 stroke-blue-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
              </svg>            
            </div>
            <input value="" type="text" name="token" id="token" class="px-1 py-2 w-1/2 border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600 mt-1">
          </div>
          <p class="mt-6 text-xs">Dúvidas de como integrar? 
            <a 
              class="text-blue-500 underline"
              href="https://ajuda.bling.com.br/hc/pt-br/articles/360046391854-Integra%C3%A7%C3%A3o-com-a-Manda-Bem" 
              target="_blank">acesse nosso manual
            </a>.
          </p>
          <button
            class="mt-2 bg-indigo-500 hover:bg-indigo-700 text-white font-bold px-2 py-1 rounded flex items-center text-sm">    
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
            </svg>                                                   
            <p class="ml-1">Salvar Yampi</p>
          </button>
        </form>
      </div>
      {{--  FINAL FORM YAMPI --}}

      <p class="w-full bg-gray-300 p-1 mt-4"></p>

      {{--  INICIO FORM FASTCOMMERCE --}}
      <div class="border border-1 rounded p-2 mt-4">
        <img src="images/logo_fastcommerce.jpeg" class="w-28 ml-2">
        <div class="flex items-center mt-4">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 stroke-blue-500">
            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
          </svg>  
          <p class="text-sm italic text-gray-600">
            Se você usa a FastCommerce, é possível inserir o seu StoreName, StoreID, Username e Senha para integrar os seus pedidos à nossa Plataforma,<br>
            Dessa forma você pode importar os pedidos de forma automática, sem necessidade de digitação.
          </p>
        </div>
        <hr class="mt-2">
        <form action="" class="ml-4 mt-4">
          <div class="mt-2">
            <div class="flex items-center">
              <label for="store_name" class="block text-gray-500 font-bold text-sm">StoreName</label>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-1 w-5 h-5 stroke-blue-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
              </svg>            
            </div>
            <input value="Manda Bem" type="text" name="store_name" id="store_name" class="px-1 py-2 w-1/2 border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600 mt-1">
          </div>
          <div class="mt-2">
            <div class="flex items-center">
              <label for=store_id" class="block text-gray-500 font-bold text-sm">StoreID</label>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-1 w-5 h-5 stroke-blue-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
              </svg>            
            </div>
            <input value="" type="text" name=store_id" id=store_id" class="px-1 py-2 w-1/2 border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600 mt-1">
          </div>
          <div class="mt-2">
            <div class="flex items-center">
              <label for="user_name" class="block text-gray-500 font-bold text-sm">UserName</label>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-1 w-5 h-5 stroke-blue-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
              </svg>            
            </div>
            <input value="" type="text" name="user_name" id="user_name" class="px-1 py-2 w-1/2 border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600 mt-1">
          </div>
          <div class="mt-2">
            <div class="flex items-center">
              <label for="password" class="block text-gray-500 font-bold text-sm">Password</label>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-1 w-5 h-5 stroke-blue-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
              </svg>            
            </div>
            <input value="" type="text" name="password" id="password" class="px-1 py-2 w-1/2 border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600 mt-1">
          </div>
          <p class="mt-6 text-xs">Dúvidas de como integrar? 
            <a 
              class="text-blue-500 underline"
              href="https://ajuda.bling.com.br/hc/pt-br/articles/360046391854-Integra%C3%A7%C3%A3o-com-a-Manda-Bem" 
              target="_blank">acesse nosso manual
            </a>.
          </p>
          <button
            class="mt-2 bg-cyan-500 hover:bg-cyan-700 text-white font-bold px-2 py-1 rounded flex items-center text-sm">    
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
            </svg>                                                   
            <p class="ml-1">Salvar FastCommerce</p>
          </button>
        </form>
      </div>
      {{--  FINAL FORM FASTCOMMERCE --}}

      <p class="w-full bg-gray-300 p-1 mt-4"></p>

      {{--  INICIO FORM PLUGG-TO --}}
      <div class="border border-1 rounded p-2 mt-4">
        <img src="images/logo_plugg.png" class="w-28 ml-2">
        <div class="flex items-center mt-4">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 stroke-blue-500">
            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
          </svg>  
          <p class="text-sm italic text-gray-600">
            Se você usa a Plugg, é possível inserir o seu Client ID, Client Secret, API User e API Secret para integrar os seus pedidos à nossa Plataforma,<br>
            Dessa forma você pode importar os pedidos de forma automática, sem necessidade de digitação.
          </p>
        </div>
        <hr class="mt-2">
        <form action="" class="ml-4 mt-4">
          <div class="mt-2">
            <div class="flex items-center">
              <label for="client_id" class="block text-gray-500 font-bold text-sm">Client ID</label>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-1 w-5 h-5 stroke-blue-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
              </svg>            
            </div>
            <input value="" type="text" name="client_id" id="client_id" class="px-1 py-2 w-full border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600 mt-1">
          </div>
          <div class="mt-2">
            <div class="flex items-center">
              <label for=store_id" class="block text-gray-500 font-bold text-sm">StoreID</label>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-1 w-5 h-5 stroke-blue-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
              </svg>            
            </div>
            <input value="" type="text" name="store_id" id="store_id" class="px-1 py-2 w-full border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600 mt-1">
          </div>
          <div class="mt-2">
            <div class="flex items-center">
              <label for="user_name" class="block text-gray-500 font-bold text-sm">UserName</label>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-1 w-5 h-5 stroke-blue-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
              </svg>            
            </div>
            <input value="" type="text" name="user_name" id="user_name" class="px-1 py-2 w-full border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600 mt-1">
          </div>
          <div class="mt-2">
            <div class="flex items-center">
              <label for="password" class="block text-gray-500 font-bold text-sm">Password</label>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-1 w-5 h-5 stroke-blue-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
              </svg>            
            </div>
            <input value="" type="text" name="password" id="password" class="px-1 py-2 w-full border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600 mt-1">
          </div>
          <p class="mt-6 text-xs">Dúvidas de como integrar? 
            <a 
              class="text-blue-500 underline"
              href="https://ajuda.bling.com.br/hc/pt-br/articles/360046391854-Integra%C3%A7%C3%A3o-com-a-Manda-Bem" 
              target="_blank">acesse nosso manual
            </a>.
          </p>
          <button
            class="mt-2 bg-green-500 hover:bg-green-700 text-white font-bold px-2 py-1 rounded flex items-center text-sm">    
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
            </svg>                                                   
            <p class="ml-1">Salvar Plugg</p>
          </button>
        </form>
      </div>
      {{--  FINAL FORM PLUGG-TO --}}

      <p class="w-full bg-gray-300 p-1 mt-4"></p>

      {{--  INICIO FORM LOJA INTEGRADA --}}
      <div class="border border-1 rounded p-2 mt-4">
        <img src="images/loja-integrada-logo-v2.png" class="w-28 ml-2">
        <div class="flex items-center mt-4">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 stroke-blue-500">
            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
          </svg>  
          <p class="text-sm italic text-gray-600">
            Se você usa a Loja Integrada, é possível inserir a chave da sua API para integrar os seus pedidos à nossa Plataforma,<br>
            Dessa forma você pode importar os pedidos de forma automática, sem necessidade de digitação.
          </p>
        </div>
        <hr class="mt-2">
        <form action="" class="ml-4 mt-4">
          <div class="mt-2">
            <div class="flex items-center">
              <label for="altura_media" class="block text-gray-500 font-bold text-sm">API Key</label>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-1 w-5 h-5 stroke-blue-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
              </svg>            
            </div>
            <input value="" type="text" name="altura_media" id="altura_media" class="px-1 py-2 w-1/2 border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600 mt-1">
          </div>
          <p class="mt-6 text-xs">Dúvidas de como integrar? 
            <a 
              class="text-blue-500 underline"
              href="https://ajuda.bling.com.br/hc/pt-br/articles/360046391854-Integra%C3%A7%C3%A3o-com-a-Manda-Bem" 
              target="_blank">acesse nosso manual
            </a>.
          </p>
          <button
            class="mt-2 bg-[#25688B] text-white font-bold px-2 py-1 rounded flex items-center text-sm">    
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
            </svg>                                                   
            <p class="ml-1">Salvar Loja Integrada</p>
          </button>
        </form>
      </div>
      {{--  FINAL FORM LOJA INTEGRADA --}}

      <p class="w-full bg-gray-300 p-1 mt-4"></p>

      <div class="border border-1 rounded p-2 mt-4">
        <div class="flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8">
            <path fill-rule="evenodd" d="M11.078 2.25c-.917 0-1.699.663-1.85 1.567L9.05 4.889c-.02.12-.115.26-.297.348a7.493 7.493 0 0 0-.986.57c-.166.115-.334.126-.45.083L6.3 5.508a1.875 1.875 0 0 0-2.282.819l-.922 1.597a1.875 1.875 0 0 0 .432 2.385l.84.692c.095.078.17.229.154.43a7.598 7.598 0 0 0 0 1.139c.015.2-.059.352-.153.43l-.841.692a1.875 1.875 0 0 0-.432 2.385l.922 1.597a1.875 1.875 0 0 0 2.282.818l1.019-.382c.115-.043.283-.031.45.082.312.214.641.405.985.57.182.088.277.228.297.35l.178 1.071c.151.904.933 1.567 1.85 1.567h1.844c.916 0 1.699-.663 1.85-1.567l.178-1.072c.02-.12.114-.26.297-.349.344-.165.673-.356.985-.57.167-.114.335-.125.45-.082l1.02.382a1.875 1.875 0 0 0 2.28-.819l.923-1.597a1.875 1.875 0 0 0-.432-2.385l-.84-.692c-.095-.078-.17-.229-.154-.43a7.614 7.614 0 0 0 0-1.139c-.016-.2.059-.352.153-.43l.84-.692c.708-.582.891-1.59.433-2.385l-.922-1.597a1.875 1.875 0 0 0-2.282-.818l-1.02.382c-.114.043-.282.031-.449-.083a7.49 7.49 0 0 0-.985-.57c-.183-.087-.277-.227-.297-.348l-.179-1.072a1.875 1.875 0 0 0-1.85-1.567h-1.843ZM12 15.75a3.75 3.75 0 1 0 0-7.5 3.75 3.75 0 0 0 0 7.5Z" clip-rule="evenodd" />
          </svg>                   
          <h1 class="text-xl text-gray-700 font-bold ml-1">Web Service/Woocommerce/WBuy/E-com-plus</h1>
        </div>
        <p>Se você usa Woocommerce, Wbuy ou nossa API gere suas credencias aqui</p>
        <div class="mt-4 text-sm text-gray-600">
          <p>Dúvidas de como ao Woocommerce? <a href="" class="underline text-blue-500">acesse o manual de integração Woocommerce</a>.</p>
          <p>Dúvidas de como à E-com-plus? <a href="" class="underline text-blue-500">acesse o manual de integração.</a></p>
        </div>

        <hr class="mt-2">
        <div class="flex items-center p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-100 mt-4" role="alert">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="flex-shrink-0 inline w-5 h-5 me-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
          </svg>                
          <span class="sr-only">Info</span>
          <div>
            <span class="font-medium">ATENÇÃO!</span> Usuário ainda não possui chaves de acesso ao WEB Service Manda Bem
          </div>
        </div>

        <h2 class="text-gray-800">Plataforma</h2>

        <form action="">
          <div class="mt-2">
            <div class="flex items-center">
              <label for="client_id" class="block text-sm text-gray-800 font-bold">Informe em qual plataforma as credenciais serão usadas</label>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-1 w-5 h-5 stroke-blue-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
              </svg>            
            </div>
            <select name="plataforma" id="plataforma" class="px-1 py-2 w-1/2 border outline-none shadow rounded bg-white border-gray-200 text-sm text-gray-600 mt-1">
              <option value="" disabled selected class="text-sm">Selecione:</option>
              <option value="loja_ntegrada">Loja Integrada</option>
              <option value="woocommerce">Woocommerce</option>
              <option value="wbuy">Wbuy</option>
              <option value="opencart">Opencart</option>
              <option value="dooca_commerce">Dooca Commerce</option>
              <option value="e_com_plus">E-com-plus</option>
              <option value="outros">Outros</option>
            </select>
          </div>
          <button
            class="mt-4 bg-blue-500 hover:bg-blue-600 text-white font-bold px-2 py-1 rounded flex items-center text-sm">    
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
              <path fill-rule="evenodd" d="M11.078 2.25c-.917 0-1.699.663-1.85 1.567L9.05 4.889c-.02.12-.115.26-.297.348a7.493 7.493 0 0 0-.986.57c-.166.115-.334.126-.45.083L6.3 5.508a1.875 1.875 0 0 0-2.282.819l-.922 1.597a1.875 1.875 0 0 0 .432 2.385l.84.692c.095.078.17.229.154.43a7.598 7.598 0 0 0 0 1.139c.015.2-.059.352-.153.43l-.841.692a1.875 1.875 0 0 0-.432 2.385l.922 1.597a1.875 1.875 0 0 0 2.282.818l1.019-.382c.115-.043.283-.031.45.082.312.214.641.405.985.57.182.088.277.228.297.35l.178 1.071c.151.904.933 1.567 1.85 1.567h1.844c.916 0 1.699-.663 1.85-1.567l.178-1.072c.02-.12.114-.26.297-.349.344-.165.673-.356.985-.57.167-.114.335-.125.45-.082l1.02.382a1.875 1.875 0 0 0 2.28-.819l.923-1.597a1.875 1.875 0 0 0-.432-2.385l-.84-.692c-.095-.078-.17-.229-.154-.43a7.614 7.614 0 0 0 0-1.139c-.016-.2.059-.352.153-.43l.84-.692c.708-.582.891-1.59.433-2.385l-.922-1.597a1.875 1.875 0 0 0-2.282-.818l-1.02.382c-.114.043-.282.031-.449-.083a7.49 7.49 0 0 0-.985-.57c-.183-.087-.277-.227-.297-.348l-.179-1.072a1.875 1.875 0 0 0-1.85-1.567h-1.843ZM12 15.75a3.75 3.75 0 1 0 0-7.5 3.75 3.75 0 0 0 0 7.5Z" clip-rule="evenodd" />
            </svg>                                              
            <p class="ml-1">Ativar Web Service</p>
          </button>
        </form>
      </div>
    </div>
  </div>
</x-app-layout>
