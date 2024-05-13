let modal_atualiza_endereco_agencia = document.getElementById('modal_atualiza_endereco_agencia');

const abreModalAtualizaEndereco = () => {
    modal_atualiza_endereco_agencia.classList.remove('hidden');
    modal_atualiza_endereco_agencia.classList.add('flex');
}

const fechaModalAtualizaEndereco = () => {
    modal_atualiza_endereco_agencia.classList.add('hidden');
}