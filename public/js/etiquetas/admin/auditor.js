let modal_add_resumo_auditor = document.getElementById('modal_add_resumo_auditor');
let cidade = document.getElementById('cidade');
let destinatario = document.getElementById('destinatario');
let etiqueta_correios = document.getElementById('etiqueta_correios');
let resumo = document.getElementById('resumo');
let idEtiquetaAuditor = document.getElementById('idEtiquetaAuditor');

const clearFormFieldsAuditor = () => {
    cidade.textContent = '';
    destinatario.textContent = '';
    etiqueta_correios.textContent = '';
    resumo.value = '';
}

const auditorModal = (idEtiqueta) => {
    modal_add_resumo_auditor.classList.remove('hidden'); 
    modal_add_resumo_auditor.classList.add('flex');

    fetch(`etiquetas/send_auditor/${idEtiqueta}`, {
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
        etiqueta_correios.innerHTML = data.auditor.etiqueta_correios ? data.auditor.etiqueta_correios : "Não registrado"
        cidade.innerHTML = `${data.auditor.cidade}/${data.auditor.estado}`
        destinatario.innerHTML = data.auditor.destinatario
        idEtiquetaAuditor.value = data.auditor.id
        console.log(data)

    })
    .catch(error => {
        console.error('Erro:', error);
    });
}

const fechaModalAutidor = () => {
    clearFormFieldsAuditor()
    modal_add_resumo_auditor.classList.add('hidden');
}

document.getElementById('submitFormAuditor').addEventListener('submit', (e) => {
    e.preventDefault();

    let resumo = document.getElementById('resumo');
    let csrfToken = document.getElementById('csrfToken');

    let buttonSendAuditor = document.getElementById('buttonSendAuditor')
    buttonSendAuditor.innerHTML = "Enviando...."
    buttonSendAuditor.disabled = true;

    const formData = {
        idEtiquetaAuditor: idEtiquetaAuditor.value,
        etiqueta_correios: etiqueta_correios.textContent,
        cidade           : cidade.textContent,
        destinatario     : destinatario.textContent,
        resumo           : resumo.value,
        _token           : csrfToken.value
    };

    fetch('/etiquetas/send_auditor', {
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
            buttonSendAuditor.innerHTML = "Salvar"
            buttonSendAuditor.disabled = false;

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
        buttonSendAuditor.innerHTML = "Enviar"
        buttonSendAuditor.disabled = false;
    })
}); 
