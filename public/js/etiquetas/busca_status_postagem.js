const buscaPeloStatusDaPostagem = () => {
  alert("OPA")
}

let modal_status_postagem = document.getElementById('modal_status_postagem');

const abreModalPostagem = () => {
  modal_status_postagem.classList.remove('hidden'); 
  modal_status_postagem.classList.add('flex');
}

const fechaModalPostagem = () => {
  modal_status_postagem.classList.add('hidden');
}