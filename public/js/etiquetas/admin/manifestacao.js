let modal_manifestacao = document.getElementById('modal-manifestacao');
let cep = document.getElementById('cep_manifestacao');
let destinatario_manifestacao = document.getElementById('destinatario_manifestacao');
let etiqueta_correios_manifestacao = document.getElementById('etiqueta_correios_manifestacao')
let tipo_remessa = document.getElementById('tipo_remessa')
let idEtiquetasManifestacao = document.getElementById('idEtiquetasManifestacao')

const abreModalManifestacaoObjeto = (idEtiqueta) => {
    modal_manifestacao.classList.remove('hidden'); 
    modal_manifestacao.classList.add('flex');

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
 
        idEtiquetasManifestacao.value = data.manifestacao.id
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

document.getElementById('submitFormManifestacaoObjeto').addEventListener('submit', (e) => {
    e.preventDefault();

    let buttonCreateManifestacao = document.getElementById('buttonCreateManifestacao')
    let csrfToken = document.getElementById('csrfToken');
    
    buttonCreateManifestacao.innerHTML = "Processando..."
    buttonCreateManifestacao.disabled = true;

    const formData = {
        etiqueta_correios_manifestacao: etiqueta_correios_manifestacao.textContent,
        destinatario_manifestacao     : destinatario_manifestacao.textContent,
        cep                           : cep.textContent,
        idEtiquetasManifestacao       : idEtiquetasManifestacao.value,
        tipo_remessa                  : tipo_remessa.value,
        _token                        : csrfToken.value
    };

    fetch('/etiquetas/manifestacao', {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro ao fazer requisição');
        }
        return response.json();
    })
    .then(data => {
        
        if (data.status === 1) {
            buttonCreateManifestacao.innerHTML = "Sim"
            buttonCreateManifestacao.disabled = false;

            Swal.fire({
                title: 'Sucesso!',
                text: data.message,
                icon: 'success',
                customClass: {
                    confirmButton: 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline-blue active:bg-blue-800',
                },
                buttonsStyling: false,
                confirmButtonText: 'OK',
            }).then(function () {
                location.reload();
            });
        }
    })
    .catch(error => {
        console.error('Erro:', error);
    })
    .finally(() => {
        buttonCreateManifestacao.innerHTML = "Sim"
        buttonCreateManifestacao.disabled = false;
    })
}); 

