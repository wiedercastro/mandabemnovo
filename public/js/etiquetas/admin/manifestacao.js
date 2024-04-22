let modal_manifestacao = document.getElementById('modal-manifestacao');
/* let cep = document.getElementById('cep');
let destinatario = document.getElementById('destinatario');
let etiqueta_correios = document.getElementById('etiqueta_correios'); */

const abreModalManifestacaoObjeto = (idEtiqueta) => {
    modal_manifestacao.classList.remove('hidden'); 
    modal_manifestacao.classList.add('flex');

   /*  console.log(idEtiqueta) */
    let cep = document.getElementById('cep');
    let destinatario_manifestacao = document.getElementById('destinatario_manifestacao');
    let etiqueta_correios_manifestacao = document.getElementById('etiqueta_correios_manifestacao');

    fetch(`etiquetas/manifestacao/${idEtiqueta}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        },
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro ao fazer requisição');
        }
        return response.json();
    })
    .then(data => {
        etiqueta_correios_manifestacao.innerHTML = data.manifestacao.etiqueta_correios ? data.manifestacao.etiqueta_correios : "Etiqueta não registrada"
        cep.innerHTML = data.manifestacao.CEP
        destinatario_manifestacao.innerHTML = data.manifestacao.destinatario
    })
    .catch(error => {
        console.error('Erro:', error);
    });
}

const fechaModalManifestacaoObjeto = () => {
  modal_manifestacao.classList.add('hidden');
}