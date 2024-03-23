let modal_pix = document.getElementById('modal_pix');

const abreModalParaPixPagamento = () => {
  modal_pix.classList.remove('hidden'); 
  modal_pix.classList.add('flex');
}

const fechaModalParaPixPagamento = () => {
  modal_pix.classList.add('hidden');
}
