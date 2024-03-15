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

      document.body.classList.add('loading');
      document.getElementById("preloader").classList.add('show');

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
       
        html += `
        <div style="width: 103em;" class="border border-2">
          <tr>
            <td>
              <div class="p-4 bg-gray-50 hover:bg-blue-50">
                <div class="flex justify-between">
                  <div>
                    <div class="">
                      <p class="font-bold text-[#25688B] text-lg">Etiqueta</p>
                      <span>${item.etiqueta_correios}</span>
                    </div>
                    <div class="flex items-center bg-yellow-600 text-white font-bold px-1 py-1 rounded mt-2">
                      <p class="text-xs">Aguardando postagem</p>
                      <svg 
                        onclick="buscaPeloStatusDaPostagem('${item.etiqueta_correios}')"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="cursor-pointer w-4 h-4 stroke-white ml-1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                      </svg>
                    </div>
                    <div class="mt-4">
                      <p class="font-bold text-[#25688B] text-lg">Email</p>
                      <span>${item.email ? item.email : ''}</span>
                    </div>
                    <div class="mt-4">
                      <p class="font-bold text-[#25688B] text-lg">AR</p>
                      <span>${item.AR ? item.AR : ''}</span>
                    </div>
                    <div class="mt-4">
                      <p class="font-bold text-[#25688B] text-lg">Seguro</p>
                      <span>${item.seguro ? 'R$ ' + item.seguro : ''}</span>
                    </div>
                  </div>

                  <div>
                    <div class="">
                      <p class="font-bold text-[#25688B] text-lg">Desconto</p>
                      <span>R$ ${desconto}</span>
                    </div>
                    <div class="mt-4">
                      <p class="font-bold text-[#25688B] text-lg">Pagamento</p>
                      <span>Crédito</span>
                    </div>
                    <div class="mt-4">
                      <p class="font-bold text-[#25688B] text-lg">NF</p>
                      <span>${item.nota_fiscal ? item.nota_fiscal : ''}</span>
                    </div>
                  </div>

                  <div>
                    <div>
                      <p class="font-bold text-[#25688B] text-lg">Valor</p>
                      <span>R$ ${valorTotal}</span>
                    </div>
                  </div>

                  <div>
                    <div>
                      <p class="font-bold text-[#25688B] text-lg">Tipo de Envio</p>
                      <span>${item.forma_envio}</span>
                    </div>
                    <div class="mt-4">
                      <p class="font-bold text-[#25688B] text-lg">Peso</p>
                      <span>${item.peso}</span>
                    </div>
                    <div class="mt-4">
                      <p class="font-bold text-[#25688B] text-lg">Dimensões</p>
                      <span></span>
                    </div>
                  </div>

                  <div>
                    <div>
                      <p class="font-bold text-[#25688B] text-lg">Destino</p>
                      <span>${item.destinatario} ${item.cpf_destinatario ? ' - ' + item.cpf_destinatario : ''}</span>
                    </div>
                    <div class="mt-4">
                      <p class="font-bold text-[#25688B] text-lg">Endereço</p>
                      <span>${item.logradouro} - ${item.numero} - ${item.estado} - ${item.CEP}</span>
                    </div>
                    <div class="mt-4">
                      <p class="font-bold text-[#25688B] text-lg">Data</p>
                      <span>${item.date_postagem ? item.date_postagem : ''}</span>
                    </div>

                    <div class="mt-4">
                      <p class="font-bold text-[#25688B] text-lg">Prazo</p>
                      <span>${item.prazo ? item.prazo : ''} dias</span>
                    </div>
                  </div>
                </div>
                <div class="flex flex-row-reverse pb-2">
                  <button class="bg-red-700 hover:bg-red-800 text-white text-xs font-bold px-2 py-1 rounded flex items-center text-sm">    
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>                                                                                    
                    <p>Cancelar envio</p>
                  </button>
                </div>
              </div>
            <td>
          <tr>
        <div>  
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
    document.getElementById("preloader").classList.remove('show');
  }
};



$(document).on("click", "#btnInfoColEtiquetas", function() {
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
      $('#detalhes_' + info).css("color", "white");
    },
  });
});
