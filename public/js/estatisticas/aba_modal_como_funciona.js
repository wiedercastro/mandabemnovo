let modal_aba_estatistica = document.getElementById('modal_aba_estatistica');

const abreModalAbaEstatistica = () => {
  modal_aba_estatistica.classList.remove('hidden'); 
  modal_aba_estatistica.classList.add('flex');
}

const fechaModalAbaEstatistica = () => {
  modal_aba_estatistica.classList.add('hidden');
}