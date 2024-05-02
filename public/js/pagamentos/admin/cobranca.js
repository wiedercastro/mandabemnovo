let modal_cobranca = document.getElementById('modal_cobranca');

const abreModalCobranca = () => {
    modal_cobranca.classList.remove('hidden');
    modal_cobranca.classList.add('flex');
}

const fechaModalCobranca = () => {
    modal_cobranca.classList.add('hidden');

    document.getElementById('cobranca_cliente').value = "";
    document.getElementById('cobranca_id_credito').value = "";
    document.getElementById('valor').value = "";
    document.getElementById('forma_cobranca').value = "";
    document.getElementById('descricao').value = "";
    document.getElementById('observacao').value = "";
}

document.getElementById('submitFormCobranca').addEventListener('submit', (e) => {
    e.preventDefault();

    const buttonFormCobranca = document.getElementById('buttonFormCobranca');
    buttonFormCobranca.innerHTML = "Gerando...."
    buttonFormCobranca.disabled = true;

    const formData = {
        cobranca_cliente: document.getElementById('cobranca_cliente').value,
        cliente_id      : document.getElementById('cobranca_id_credito').value,
        valor           : document.getElementById('valor').value,
        forma_cobranca  : document.getElementById('forma_cobranca').value,
        descricao       : document.getElementById('descricao').value,
        observacao      : document.getElementById('observacao').value,
        _token          : document.getElementById('_token').value,
    };

    fetch('cobranca', {
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

        buttonFormCobranca.innerHTML = "Gerar crédito"
        buttonFormCobranca.disabled = false;

        //fecha modal após o sucesso
        modal_cobranca.classList.add('hidden');
    })
    .catch(error => {
        console.error('Erro:', error);
    })
    .finally(() => {
        buttonFormCobranca.innerHTML = "Gerar crédito"
        buttonFormCobranca.disabled = false;
    })
});


