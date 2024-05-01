let modal_creditos = document.getElementById('modal_creditos');

const abreModalCreditos = () => {
  modal_creditos.classList.remove('hidden'); 
  modal_creditos.classList.add('flex');
}

const fechaModalCreditos = () => {
  modal_creditos.classList.add('hidden');
}

document.getElementById('submitFormCredito').addEventListener('submit', (e) => {
    e.preventDefault();

    const buttonFormCredito = document.getElementById('buttonFormCredito');
    buttonFormCredito.innerHTML = "Gerando...."
    buttonFormCredito.disabled = true;

    const formData = {
        destinatario_cliente: document.getElementById('destinatario_cliente').value,
        valor               : document.getElementById('valor_creditos').value,
        tipo                : document.getElementById('tipo').value,
        observacao          : document.getElementById('observacao_creditos').value,
        _token              : document.getElementById('_token').value,
    };

    fetch('creditos', {
        method: 'POST',
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

        buttonFormCredito.innerHTML = "Gerar crédito"
        buttonFormCredito.disabled = false;

        //fecha modal após o sucesso
        modal_creditos.classList.add('hidden');
    })
    .catch(error => {
        console.error('Erro:', error);
    })
    .finally(() => {
        buttonFormCredito.innerHTML = "Gerar crédito"
        buttonFormCredito.disabled = false;
    })
});
