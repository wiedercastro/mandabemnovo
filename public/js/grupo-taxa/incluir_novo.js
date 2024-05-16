let modal_grupo_taxas = document.getElementById('modal_grupo_taxas');

const modalIncluirNovoGrupoTaxa = () => {
    modal_grupo_taxas.classList.remove('hidden');
    modal_grupo_taxas.classList.add('flex');
}

const fechaIncluirNovoGrupoTaxa = () => {
    modal_grupo_taxas.classList.add('hidden');
}