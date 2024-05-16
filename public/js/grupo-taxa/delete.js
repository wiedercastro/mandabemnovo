let modal_deleta_grupo_taxa = document.getElementById('modal_deleta_grupo_taxa');
let idGrupoTaxa = document.getElementById('idGrupoTaxa');


const modalDeletaGrupoTaxa = (id) => {
    idGrupoTaxa.value = id;

    modal_deleta_grupo_taxa.classList.remove('hidden'); 
    modal_deleta_grupo_taxa.classList.add('flex');
}

const deletaGrupoTaxa = () => {
    let csrfToken = document.getElementById('csrfTokenGrupoTaxa');
    let buttonDeleteGrupoFaixa = document.getElementById('buttonDeleteGrupoFaixa');
    buttonDeleteGrupoFaixa.innerHTML = "Deletando...."
    buttonDeleteGrupoFaixa.disabled  = true;

    const GRUPO_FAIXA_ID = idGrupoTaxa.value

    if (GRUPO_FAIXA_ID) {
        fetch(`/deleta-grupo-taxa/${GRUPO_FAIXA_ID}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.value 
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro ao fazer requisição');
            }
            return response.json();
        })
        .then(data => {
            cancelaDeletaGrupoTaxa()
            if (data.success) {
                Swal.fire({
                    title: 'Sucesso',
                    text: data.success,
                    icon: 'success',
                    customClass: {
                        confirmButton: 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline-blue active:bg-blue-800',
                    },
                    buttonsStyling: false,
                    confirmButtonText: 'OK',
                }).then(function () {
                    location.reload();
                });
            }
        })
        .catch(error => {
            console.error('Erro:', error);
        })
        .finally(() => {
            buttonDeleteGrupoFaixa.innerHTML = "Sim, tenho certeza"
            buttonDeleteGrupoFaixa.disabled = false;
        })
    }
}

const cancelaDeletaGrupoTaxa = () => {
    modal_deleta_grupo_taxa.classList.add('hidden');
}