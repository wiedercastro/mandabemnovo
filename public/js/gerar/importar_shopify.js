let modal_importar_shopify = document.getElementById('modal_importar_shopify');

const abreModalImportarShopify = () => {
  modal_importar_shopify.classList.remove('hidden'); 
  modal_importar_shopify.classList.add('flex');
}

const fechaModalImportarShopify = () => {
  modal_importar_shopify.classList.add('hidden');
}