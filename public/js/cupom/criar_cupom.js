
let modal_criar_cupom = document.getElementById('modal_criar_cupom');
let modal_ativar_desativar_cupom = document.getElementById('modal_ativar_desativar_cupom');

const fecharModalCriarCupom = () => {
    modal_criar_cupom.classList.add('hidden');
}

const abreModalParaCriarCupom = () => {
  modal_criar_cupom.classList.remove('hidden'); 
  modal_criar_cupom.classList.add('flex');
}

document.getElementById('submitFormCredito').addEventListener('submit', (e) => {
    e.preventDefault();

    let tipo_cupom = document.getElementById('tipo_cupom');
    let nome_ativacao = document.getElementById('nome_ativacao');
    let valor = document.getElementById('valor');
    let tempo_duracao_dias = document.getElementById('tempo_duracao_dias');
    let qtd_envios = document.getElementById('qtd_envios');
    let vincular_afiliado = document.getElementById('vincular_afiliado');
    let csrfToken = document.getElementById('_token');
    
    let buttonFormCredito = document.getElementById('buttonFormCredito')
    buttonFormCredito.innerHTML = "Criando...."
    buttonFormCredito.disabled = true;

    const formData = {
        tipo_cupom        : tipo_cupom.value,
        nome_ativacao     : nome_ativacao.value,
        valor             : valor.value,  
        tempo_duracao_dias: tempo_duracao_dias.value,
        qtd_envios        : qtd_envios.value,
        vincular_afiliado : vincular_afiliado.value,
        _token            : csrfToken.value
    };

    fetch('/cupom', {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro ao fazer requisição');
        }
        return response.json();
    })
    .then(data => {

        if (data.status === 1) {
            buttonFormCredito.innerHTML = "Criar cupom"
            buttonFormCredito.disabled = false;

            Swal.fire({
                title: 'Sucesso!',
                text: data.msg,
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
        buttonFormCredito.innerHTML = "Criar cupom"
        buttonFormCredito.disabled = false;
    })
}); 




