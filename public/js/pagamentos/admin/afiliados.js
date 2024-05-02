let modal_afiliados = document.getElementById('modal_afiliados');

const abreModalAfiliados = () => {
  modal_afiliados.classList.remove('hidden'); 
  modal_afiliados.classList.add('flex');
}

const fechaModalAfiliados = () => {
  modal_afiliados.classList.add('hidden');

  document.getElementById('cliente_id_afiliados').value = "";
  document.getElementById('cliente_afiliados').value = "";
  document.getElementById('valor_afiliados').value = "";
}

document.getElementById('submitFormAfiliados').addEventListener('submit', (e) => {
    e.preventDefault();

    const buttonAfiliados = document.getElementById('buttonAfiliados');
    buttonAfiliados.innerHTML = "Gerando...."
    buttonAfiliados.disabled = true;

    const formData = {
        cliente_afiliados: document.getElementById('cliente_afiliados').value,
        cliente_id       : document.getElementById('cliente_id_afiliados').value,
        valor_afiliados  : document.getElementById('valor_afiliados').value,
        _token           : document.getElementById('_token').value,
    };

    fetch('afiliados-pagamentos', {
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

        buttonAfiliados.innerHTML = "Gerar crédito"
        buttonAfiliados.disabled = false;

        //fecha modal após o sucesso
        modal_afiliados.classList.add('hidden');
    })
    .catch(error => {
        console.error('Erro:', error);
    })
    .finally(() => {
        buttonAfiliados.innerHTML = "Gerar crédito"
        buttonAfiliados.disabled = false;
    })
});
