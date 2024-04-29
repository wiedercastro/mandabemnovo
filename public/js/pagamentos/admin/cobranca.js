let modal_cobranca = document.getElementById('modal_cobranca');

const abreModalCobranca = () => {
  modal_cobranca.classList.remove('hidden'); 
  modal_cobranca.classList.add('flex');
}

const fechaModalCobranca = () => {
  modal_cobranca.classList.add('hidden');
}