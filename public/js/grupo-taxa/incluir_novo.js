let modal_grupo_taxas = document.getElementById('modal_grupo_taxas');


const modalIncluirNovoGrupoTaxa = () => {
    modal_grupo_taxas.classList.remove('hidden');
    modal_grupo_taxas.classList.add('flex');
}

const fechaIncluirNovoGrupoTaxa = () => {
    modal_grupo_taxas.classList.add('hidden');
    document.querySelector('.alert').classList.add('hidden');
    limparValoresInputs();

}

function toggleTipoDesconto() {
    let typeSelect = document.getElementById('type');
    let inputPercentual = document.getElementById('inputPercentual');
    let customFieldsTable = document.getElementById('exibeTableParaCustomizado');

    if (typeSelect.value === 'fixos') {
        customFieldsTable.style.display = 'block';
        inputPercentual.style.display = 'none';
    } else if (typeSelect.value === 'percentual') {
        customFieldsTable.style.display = 'none';
        inputPercentual.style.display = 'block';
    }
}

document.getElementById('submitFormIncluirNovoGrupoTaxa').addEventListener('submit', (e) => {
    e.preventDefault();


    let buttonIncluirNovoGrupoTaxa = document.getElementById('buttonIncluirNovoGrupoTaxa')
    buttonIncluirNovoGrupoTaxa.innerHTML = "Salvando...."
    buttonIncluirNovoGrupoTaxa.disabled = true;

    let csrfToken = document.getElementById('_token');
    let name = document.getElementById('name').value;
    let application = document.getElementById('application').value;
    let type = document.getElementById('type').value;
    let situacao = document.getElementById('situacao').value;
    let tabela = document.getElementById('tabela').value;
    let percentual = document.getElementById('percentual').value;

    const formData = {
        name: name,
        application: application,
        type: type,
        situacao: situacao,
        tabela: tabela,
        percentual: percentual,
        _token: csrfToken.value,
        taxas: [],
        faixa_init: [],
        faixa_end: []
    };

    document.querySelectorAll('input[name^="taxa"]').forEach(input => {
        formData.taxas.push(input.value);
    });
    
    document.querySelectorAll('input[name^="faixa_init"]').forEach(input => {
        formData.faixa_init.push(input.value);
    });
    
    document.querySelectorAll('input[name^="faixa_end"]').forEach(input => {
        formData.faixa_end.push(input.value);
    });

    fetch('/grupo_taxa_incluir', {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(response => {
        return response.json();
    })
    .then(data => {
        if (data.errors) {
            const errorsList = document.getElementById('resultErros');
            errorsList.innerHTML = ''; 
    
            Object.values(data.errors).forEach(errorMessages => {
                errorMessages.forEach(errorMessage => {
                    const listItem = document.createElement('li');
                    listItem.textContent = errorMessage;
                    errorsList.appendChild(listItem);
                });
            });

            document.querySelector('.alert').classList.remove('hidden');
        } else {
            document.querySelector('.alert').classList.add('hidden');
        }

        if (data.status === 1) {
            buttonIncluirNovoGrupoTaxa.innerHTML = "Salvar"
            buttonIncluirNovoGrupoTaxa.disabled = false;

            Swal.fire({
                title: 'Sucesso!',
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
        console.log(error);
    })
    .finally(() => {
        buttonIncluirNovoGrupoTaxa.innerHTML = "Salvar"
        buttonIncluirNovoGrupoTaxa.disabled = false;
    })
}); 


const limparValoresInputs = () => {
    document.getElementById('name').value = '';
    document.getElementById('application').value = '';
    document.getElementById('type').value = '';
    document.getElementById('situacao').value = '';
    document.getElementById('tabela').value = '';
    document.getElementById('percentual').value = '';

    document.querySelectorAll('input[name^="taxa"]').forEach(input => {
        input.value = '';
    });

    document.querySelectorAll('input[name^="faixa_init"]').forEach(input => {
        input.value = '';
    });

    document.querySelectorAll('input[name^="faixa_end"]').forEach(input => {
        input.value = '';
    });
};