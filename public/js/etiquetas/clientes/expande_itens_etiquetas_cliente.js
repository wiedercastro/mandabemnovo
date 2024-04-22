const expandeDetalhesEtiquetasCliente = async (idEtiqueta) => {

    const linhaClicada = document.getElementById(`detalhes_${idEtiqueta}`);
    const displayAtual = linhaClicada.style.display;
    const valorTotal = document.getElementById('valor').textContent;
    const desconto = document.getElementById('desconto').textContent;


    const toggleClass = (element, className, condition) => {
        element.classList.toggle(className, condition);
    };

    /*
    *Essa funcao toggleRowDisplay tem como responsabilidade 
    apenas estilizar as cores do head da tabela no click para expandir os dados
    */
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
                <div style="width: 103em;">
                    <tr>
                        <td>
                            <div class="bg-blue-100 p-4 flex flex-col"> 
                                <div class="flex justify-between">
                                    <div>
                                        <h4 class="font-bold text-gray-700">DESTINO</h4>
                                        <div class="text-sm mt-4">
                                            <p>${item.destinatario}</p>
                                            <p>CEP: ${item.CEP}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-700">ETIQUETA</h4>
                                        <div class="mt-4">
                                            <p class="text-sm font-bold">${item.etiqueta_correios ? item.etiqueta_correios : "Não registrada"}</p>
                                            <div class="bg-yellow-500 px-1 py-1 rounded flex items-center">
                                                <p class="text-xs text-white font-bold">Aguardando postagem</p>
                                                <i class="fa fa-search green ml-1"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-700">DESCONTO</h4>
                                        <div class="text-sm mt-4">
                                            <p>R$ ${desconto}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-700">VALOR</h4>
                                        <div class="text-sm mt-4">
                                            <p>${valorTotal}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-700">PAGAMENTO</h4>
                                        <div class="text-sm mt-4">
                                            <p class="text-gray-700 font-bold">Crédito</p>
                                        </div>
                                    </div>
                                        
                                </div>
                        
                                <div class="bg-gray-100 shadow mt-4 p-1 rounded">
                                    <div class="flex justify-between">
                                        <div class="flex flex-col w-full justify-center items-center">
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 stroke-blue-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" />
                                                </svg>                                              
                                                <p class="text-blue-500 font-bold">ENDEREÇO</p>
                                            </div>
                                            <span>
                                                ${item.logradouro} - ${item.numero} - ${item.CEP} - ${item.estado}
                                            </span>
                                        </div>
                                        <div class="w-full flex justify-center items-center">
                                            <div class="flex items-center flex-col">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 stroke-blue-500">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                                    </svg>                                              
                                                    <p class="text-blue-500 font-bold">E-MAIL</p>
                                                </div>
                                                <span>${item.email ? item.email : 'Não registrado'}</span>
                                            </div>
                                        </div>
                                        <div class="w-full flex justify-center items-center">
                                            <div class="flex items-center flex-col">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 stroke-blue-500">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                                                    </svg>                                               
                                                    <p class="text-blue-500 font-bold">TIPO ENVIO</p>
                                                </div>
                                                <span>${item.forma_envio}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="mt-4">
                                    <div class="flex justify-between p-4">
                                        <div class="flex flex-col">
                                            <div class="flex">
                                                <span class="text-blue-500 font-bold">AR:</span>
                                                <p class="ml-1 text-blue-400 italic">${item.AR ? item.AR : 'Não registrado'}</p>
                                            </div>
                                            <div class="flex">
                                                <span class="text-blue-500 font-bold">SEGURO:</span>
                                                <p class="ml-1 text-blue-400 italic">${item.seguro ? item.seguro : 'Não registrado'}</p>
                                            </div>
                                        </div>
                                        <div class="flex flex-col">
                                            <div class="flex">
                                                <span class="text-blue-500 font-bold">PESO:</span>
                                                <p class="ml-1 text-blue-400 italic">${item.peso} Kg</p>
                                            </div>
                                            <div class="flex">
                                                <span class="text-blue-500 font-bold">DIMENSÕES:</span>
                                                <p class="ml-1 text-blue-400 italic">10 (A) x 10 (L) x 10 (C)</p>
                                            </div>
                                        </div>
                                        <div class="flex flex-col">
                                            <div class="flex">
                                                <span class="text-blue-500 font-bold">PRAZO:</span>
                                                <p class="ml-1 text-blue-400 italic">${item.prazo ? item.prazo : 'Não registrado'} dias</p>
                                            </div>
                                            <div class="flex">
                                                <span class="text-blue-500 font-bold">NF:</span>
                                                <p class="ml-1 text-blue-400 italic">${item.nota_fiscal ? item.nota_fiscal : 'Não registrado'}</p>
                                            </div>
                                            <div class="flex">
                                                <span class="text-blue-500 font-bold">DATA POSTAGEM:</span>
                                                <p class="ml-1 text-blue-400 italic">${item.date_postagem ? item.date_postagem : 'Não registrado'}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-row-reverse mt-4">
                                    <button class="bg-red-700 hover:bg-red-800 text-white text-xs font-bold px-2 py-1 rounded flex items-center text-sm w-40 flex justify-center">    
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                        </svg>                                                                                    
                                        <p class="ml-1">Cancelar envio</p>
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                </div>
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



$(document).on("click", "#btnInfoColEtiquetas", function () {
    var info = $(this).attr('data-id');
    var rota = "{{ route('coleta.show', ['id' => ':idenvio']) }}"
    rota = rota.replace(":idenvio", info);
    $.ajax({
        url: rota,
        success: function (data) {
            $('#linha_' + info).css("background", "#2d6984");
            $('#linha_' + info).css("color", "white");
            $('#idenvio_' + info).css("color", "white");
            $('#detalhes_' + info).show();
            $('#detalhes_' + info).append(data.html);
            $('#detalhes_' + info).css("color", "white");
        },
    });
});
