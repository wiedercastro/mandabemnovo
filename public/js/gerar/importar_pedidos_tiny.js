let modal_pedidos_tiny = document.getElementById('modal_pedidos_tiny');

const abreModalImportarTiny = () => {
  modal_pedidos_tiny.classList.remove('hidden'); 
  modal_pedidos_tiny.classList.add('flex');
}

const fechaModalImportarTiny = () => {
  modal_pedidos_tiny.classList.add('hidden');
}