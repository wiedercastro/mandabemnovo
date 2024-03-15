 let modal_status_postagem = document.getElementById('modal_status_postagem');

const buscaPeloStatusDaPostagem = (idEtiqueta) => {
  modal_status_postagem.classList.remove('hidden'); 
  modal_status_postagem.classList.add('flex');

  /* agora aqui mandar a requisição para o backend com o que é necessario para enviar para api do correios */
}

const fechaModalPostagem = () => {
  modal_status_postagem.classList.add('hidden');
}