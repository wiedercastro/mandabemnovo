<x-app-layout>

  <div class="w-5/6 ml-auto lg:px-12">
    <x-card-pagamentos/>
    <div class="flex justify-between mt-6">
      <div class="text-4xl mt-6">
        <h1 class="text-gray-500 font-bold text-4xl text">Etiquetas</h1>
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
            <th scope="col" class="px-6 py-3">
                Pagto
            </th>
            <th scope="col" class="px-6 py-3">
                Desconto
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
          <tr 
            onclick="expandeDetalhesEtiquetas({{$envio->id}})"
            class="bg-white hover:bg-gray-100 border-b rounded-full font-light cursor-pointer" id="linha_{{ $envio->id }}">
            <th class="px-6 py-4 rounded-s-lg text-[#2d6984]" id="idenvio_{{ $envio->id }}">
              <button id="btnInfoCol" data-id ="{{ $envio->id }}">
                <div class="flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 mr-1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                  <p class="text-xs">24/12/2023</p>
                </div>
                MB{{ $envio->id }}
              </button>
            </th>
            <td class="px-6 py-4">
              Credito
            </td>
            <td id="desconto" lass="px-6 py-4">
              R$ {{ $envio->desconto }}
            </td>
            <td id="valor" class="px-6 py-4 font-medium text-green-950">
              R$ {{ $envio->total }}
            </td>
            <td class="px-2 py-2">
              @if ($envio->type == 'REVERSA')
                Aut. Postagem<br>
              @else
                <a href="#" class="tdType1 font-medium text-blue-600 hover:underline flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                  </svg>
                  4x4
                </a>
                <br>
                <a href="#" class="tdType2 font-medium text-blue-600 hover:underline flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                  </svg>
                  <p>Declaração</p>
                </a>
              @endif
            </td>
            <td class="px-2 py-2">
              @if ($envio->type == 'REVERSA')
                <button type="button" class="bg-yellow-700 text-xs hover:bg-yellow-800 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 mr-1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 0 0-3.7-3.7 48.678 48.678 0 0 0-7.324 0 4.006 4.006 0 0 0-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 0 0 3.7 3.7 48.656 48.656 0 0 0 7.324 0 4.006 4.006 0 0 0 3.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3-3 3" />
                  </svg>
                  Reversa                  
                </button>
              @else
                <button type="button" class="bg-cyan-700 text-xs hover:bg-cyan-800 text-white font-bold px-2 py-1 rounded ml-2 flex items-center">{{ $envio->qte }}
                  @if ($envio->qte > 1)
                      Itens
                  @else
                      Item
                  @endif
                </button>
              @endif
            </td>
          </tr>

          <div id="preloader"></div>

          <tr id="detalhes_{{ $envio->id }}" class="bg-gray-50 hover:bg-blue-50 border border-2">
          </tr>
        @endforeach
      </tbody>
    </table>
    <nav class="mt-2 pb-10">
      <ul class="inline-flex -space-x-px mt-2 text-xs">
        @if ($envios->currentPage() > 1)
          <li>
            <a href="?page={{ $envios->currentPage() - 1 }}" 
              class="py-2 px-3 ml-0 leading-tight text-gray-500 bg-white 
                rounded-l-lg border border-gray-300 hover:bg-gray-100 
                hover:text-gray-700">
              Anterior
            </a>
          </li>
        @endif
        @for ($i = 1; $i <= $envios->lastPage(); $i++)
          <li>
            <a href="?page={{ $i }}" 
                class="py-2 px-3 {{ $envios->currentPage() == $i ? 'text-blue-600 bg-blue-50' : 'text-gray-500 bg-white' }}
                border border-gray-300 hover:bg-gray-100 
                hover:text-gray-700">
                {{ $i }}
            </a>
          </li>
        @endfor
        @if ($envios->currentPage() < $envios->lastPage())
          <li>
            <a href="?page={{ $envios->currentPage() + 1 }}" 
              class="py-2 px-3 leading-tight text-gray-500 bg-white
              rounded-r-lg border border-gray-300 hover:bg-gray-100 
              hover:text-gray-700">
              Próxima
            </a>
          </li>
        @endif
      </ul>
    </nav>
  </div>
</x-app-layout>


<script>

const expandeDetalhesEtiquetas = async (idEtiqueta) => {

  const linhaClicada = document.getElementById(`detalhes_${idEtiqueta}`);
  const displayAtual = linhaClicada.style.display;
  const valorTotal = document.getElementById('valor').textContent;
  const desconto = document.getElementById('desconto').textContent;
  

  const toggleClass = (element, className, condition) => {
    element.classList.toggle(className, condition);
  };

  const toggleRowDisplay = (row, display) => {
    row.style.display = display;

    const linhaPrincipalClicada = document.getElementById(`linha_${idEtiqueta}`);
    toggleClass(linhaPrincipalClicada, 'bg-[#154864]', display === 'table-row');
    toggleClass(linhaPrincipalClicada, 'text-white', display === 'table-row');
    toggleClass(linhaPrincipalClicada, 'hover:bg-[#154864]', display === 'table-row');

    const thClicado = linhaPrincipalClicada.querySelector('th');
    toggleClass(thClicado, 'text-white', display === 'table-row');
    toggleClass(thClicado, 'font-bold', display === 'table-row');

    const tdValor = linhaPrincipalClicada.querySelector('#valor');
    toggleClass(tdValor, 'text-white', display === 'table-row');
    toggleClass(tdValor, 'font-bold', display === 'table-row');

    const tdType1 = linhaPrincipalClicada.querySelector('.tdType1');
    toggleClass(tdType1, 'text-blue-400', display === 'table-row');

    const tdType2 = linhaPrincipalClicada.querySelector('.tdType2');
    toggleClass(tdType2, 'text-blue-400', display === 'table-row');
  };

  try {
    if (displayAtual === 'none' || displayAtual === '') {

      document.getElementById("preloader").innerHTML = '<div class="loader flex justify-center text-white font-bold items-center text-xs"><img src="images/spinner.svg" class="h-10 w-10"><p class="mt-6 ml-3">Carregando..</p></div>';
      document.body.classList.add('loading');
      linhaClicada.innerHTML = '';
      toggleRowDisplay(linhaClicada, 'table-row');

      const response = await fetch(`http://localhost:8989/etiquetas/${idEtiqueta}`, {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json'
        },
      });
      
      if (!response.ok) {
        throw new Error('Erro ao fazer requisição');
      }

      let html = ''

      const resJson = await response.json();
      for (const item of resJson.data) {

        html = `
          <td colspan="6">
            <div class="p-4 flex space-x-28">
              <div>
                <div class="">
                  <p class="font-bold text-[#25688B]">Etiqueta</p>
                  <span>${item.etiqueta_correios}</span>
                </div>
                <div class="mt-4">
                  <p class="font-bold text-[#25688B]">Email</p>
                  <span>${item.email ? item.email : ''}</span>
                </div>
                <div class="mt-4">
                  <p class="font-bold text-[#25688B]">AR</p>
                  <span>${item.AR ? item.AR : ''}</span>
                </div>
                <div class="mt-4">
                  <p class="font-bold text-[#25688B]">Seguro</p>
                  <span>${item.seguro ? 'R$ ' + item.seguro : ''}</span>
                </div>
              </div>

              <div>
                <div>
                  <p class="font-bold text-[#25688B]">Valor</p>
                  <span>R$ ${valorTotal}</span>
                </div>
                <div class="mt-4">
                  <p class="font-bold text-[#25688B]">Desconto</p>
                  <span>R$ ${desconto}</span>
                </div>
                <div class="mt-4">
                  <p class="font-bold text-[#25688B]">Pagamento</p>
                  <span>Crédito</span>
                </div>
                <div class="mt-4">
                  <p class="font-bold text-[#25688B]">NF</p>
                  <span>${item.nota_fiscal ? item.nota_fiscal : ''}</span>
                </div>
              </div>

              <div>
                <div>
                  <p class="font-bold text-[#25688B]">Tipo de Envio</p>
                  <span>${item.forma_envio}</span>
                </div>
                <div class="mt-4">
                  <p class="font-bold text-[#25688B]">Peso</p>
                  <span>${item.peso}</span>
                </div>
                <div class="mt-4">
                  <p class="font-bold text-[#25688B]">Dimensões</p>
                  <span></span>
                </div>
              </div>

              <div>
                <div>
                  <p class="font-bold text-[#25688B]">Destino</p>
                  <span>${item.destinatario} ${item.cpf_destinatario ? ' - ' + item.cpf_destinatario : ''}</span>
                </div>
                <div class="mt-4">
                  <p class="font-bold text-[#25688B]">Endereço</p>
                  <span>${item.logradouro} - ${item.numero} - ${item.estado} - ${item.CEP}</span>
                </div>
                <div class="mt-4">
                  <p class="font-bold text-[#25688B]">Data</p>
                  <span>${item.date_postagem ? item.date_postagem : ''}</span>
                </div>

                <div class="mt-4">
                  <p class="font-bold text-[#25688B]">Prazo</p>
                  <span>${item.prazo ? item.prazo : ''} dias</span>
                </div>
              </div>
            </div>
            <div class="flex flex-row-reverse mr-4 pb-2">
              <button class="bg-red-700 hover:bg-red-800 text-white text-xs font-bold px-2 py-1 rounded flex items-center text-sm">    
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>                                                                                    
                <p class="ml-1">Cancelar envio</p>
              </button>
            </div>
          </td>
        `;

      }
      
      document.getElementById(`detalhes_${idEtiqueta}`).innerHTML = html;
    } else {
      toggleRowDisplay(linhaClicada, 'none');
    }
  } catch (error) {
    console.error('Erro ao expandir detalhes:', error.message);
  } finally {
    document.body.classList.remove('loading');
    document.getElementById("preloader").innerHTML = '';
  }
};



$(document).on("click", "#btnInfoCol", function() {
  var info = $(this).attr('data-id');
  var rota = "{{ route('coleta.show', ['id' => ':idenvio']) }}"
  rota = rota.replace(":idenvio", info);
  $.ajax({
    url: rota,
    success: function(data) {
      $('#linha_' + info).css("background", "#2d6984");
      $('#linha_' + info).css("color", "white");
      $('#idenvio_' + info).css("color", "white");
      $('#detalhes_' + info).show();
      $('#detalhes_' + info).append(data.html);
    },
  });
});


</script>

