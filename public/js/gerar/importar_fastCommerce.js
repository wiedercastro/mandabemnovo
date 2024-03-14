let modal_importar_fastcommerce = document.getElementById('modal_importar_fastcommerce');

const abreModalImportarFastCommerce = () => {
  modal_importar_fastcommerce.classList.remove('hidden'); 
  modal_importar_fastcommerce.classList.add('flex');
}

const fechaModalImportarFastCommerce = () => {
  modal_importar_fastcommerce.classList.add('hidden');
}