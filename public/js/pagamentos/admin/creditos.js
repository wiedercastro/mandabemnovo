let modal_creditos = document.getElementById('modal_creditos');
let creditos_cliente = document.getElementById('creditos_cliente');
let cliente_id = document.getElementById('cliente_id_credito');
let valor = document.getElementById('valor_creditos');
let tipo = document.getElementById('tipo');
let observacao = document.getElementById('observacao_creditos');
let token = document.getElementById('_token');

const abreModalCreditos = () => {
  modal_creditos.classList.remove('hidden'); 
  modal_creditos.classList.add('flex');
}

const fechaModalCreditos = () => {
    modal_creditos.classList.add('hidden');

    creditos_cliente.value = ""
    cliente_id.value = ""
    valor.value = ""
    tipo.value = ""
    observacao.value = ""
}

document.getElementById('submitFormCredito').addEventListener('submit', (e) => {
    e.preventDefault();

    const buttonFormCredito = document.getElementById('buttonFormCredito');
    buttonFormCredito.innerHTML = "Gerando...."
    buttonFormCredito.disabled = true;

    const formData = {
        creditos_cliente: creditos_cliente.value,
        cliente_id      : cliente_id.value,
        valor           : valor.value,
        tipo            : tipo.value,
        observacao      : observacao.value,
        _token          : token.value,
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
