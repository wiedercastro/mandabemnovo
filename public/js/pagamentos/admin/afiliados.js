let modal_afiliados = document.getElementById('modal_afiliados');

const abreModalAfiliados = () => {
  modal_afiliados.classList.remove('hidden'); 
  modal_afiliados.classList.add('flex');
}

const fechaModalAfiliados = () => {
  modal_afiliados.classList.add('hidden');
}