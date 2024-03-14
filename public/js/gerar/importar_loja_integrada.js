let modal_loja_integrada = document.getElementById('modal_loja_integrada');

const abreModalLojaIntegrada = () => {
  modal_loja_integrada.classList.remove('hidden'); 
  modal_loja_integrada.classList.add('flex');
}

const fechaModalLojaIntegrada = () => {
  modal_loja_integrada.classList.add('hidden');
}