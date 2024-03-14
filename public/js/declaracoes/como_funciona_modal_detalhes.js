let modal_info_detalhes = document.getElementById('modal-info-detalhes');

const exibiModalComInformacoesDetalhes = () => {
  modal_info_detalhes.classList.remove('hidden'); 
  modal_info_detalhes.classList.add('flex');
}

const fechaModalComInformacoesDetalhes = () => {
  modal_info_detalhes.classList.add('hidden');
}