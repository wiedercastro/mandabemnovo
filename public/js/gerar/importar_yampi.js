let modal_importar_yampi = document.getElementById('modal_importar_yampi');

const abreModalImportarYampi = () => {
  modal_importar_yampi.classList.remove('hidden'); 
  modal_importar_yampi.classList.add('flex');
}

const fechaModalImportarYampi = () => {
  modal_importar_yampi.classList.add('hidden');
}