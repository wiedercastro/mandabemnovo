let modal_bling = document.getElementById('modal_bling');

const abreModalBling = () => {
  modal_bling.classList.remove('hidden'); 
  modal_bling.classList.add('flex');
}

const fechaModalBling = () => {
  modal_bling.classList.add('hidden');
}