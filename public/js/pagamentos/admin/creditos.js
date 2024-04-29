let modal_creditos = document.getElementById('modal_creditos');

const abreModalCreditos = () => {
  modal_creditos.classList.remove('hidden'); 
  modal_creditos.classList.add('flex');
}

const fechaModalCreditos = () => {
  modal_creditos.classList.add('hidden');
}