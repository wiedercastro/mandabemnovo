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

            if (linhaClicada.classList.contains('hidden')) {
                linhaClicada.classList.remove('hidden')
            }


            document.body.classList.add('loading');
            document.getElementById("preloader").classList.add('show');

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

             /* let html = ''
       
             const resJson = await response.json();
             for (const item of resJson.data) {
              
               
       
             }
             document.getElementById(`detalhes_${idEtiqueta}`).innerHTML = html; */

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
