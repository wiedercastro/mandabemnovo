let modal_cancelamento= document.getElementById('modal-cancelamento');
let cep_cancelamento = document.getElementById('cep_cancelamento');
let destinatario_cancelamento = document.getElementById('destinatario_cancelamento');
let etiqueta_correios_cancelamento = document.getElementById('etiqueta_correios_cancelamento')
let idEtiquetaCancelamento = document.getElementById('idEtiquetaCancelamento')

const abreModalCancelamentoObjeto = (idEtiqueta) => {
    modal_cancelamento.classList.remove('hidden'); 
    modal_cancelamento.classList.add('flex');

    fetch(`etiquetas/cancelamento/${idEtiqueta}`, {
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
        console.log(data)
        idEtiquetaCancelamento.innerHTML = data.cancelamento.id
        etiqueta_correios_cancelamento.innerHTML = data.cancelamento.etiqueta_correios ? data.cancelamento.etiqueta_correios : "Etiqueta não registrada"
        cep_cancelamento.innerHTML = data.cancelamento.CEP
        destinatario_cancelamento.innerHTML = data.cancelamento.destinatario
    })
    .catch(error => {
        console.error('Erro:', error);
    });
}

const fechaModalCancelamentoObjeto = () => {
  modal_cancelamento.classList.add('hidden');
}

document.getElementById('submitFormCancelamentoObjeto').addEventListener('submit', (e) => {
    e.preventDefault();

    let buttonCancelamentoObjeto = document.getElementById('buttonCancelamentoObjeto')
    let csrfToken = document.getElementById('csrfToken');
    
    buttonCancelamentoObjeto.innerHTML = "Cancelando...."
    buttonCancelamentoObjeto.disabled = true;

    const formData = {
        etiqueta_correios_cancelamento: etiqueta_correios_cancelamento.textContent,
        destinatario_cancelamento     : destinatario_cancelamento.textContent,
        cep_cancelamento              : cep_cancelamento.textContent,
        idEtiquetaCancelamento        : idEtiquetaCancelamento.textContent,
        _token                        : csrfToken.value

    };

    fetch('/etiquetas/cancelamento', {
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
        console.log(data);
        
        if (data.success === true) {
            buttonCancelamentoObjeto.innerHTML = "Sim"
            buttonCancelamentoObjeto.disabled = false;

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
        buttonCancelamentoObjeto.innerHTML = "Sim"
        buttonCancelamentoObjeto.disabled = false;
    })
}); 

