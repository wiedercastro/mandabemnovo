let modal_edit_usuario = document.getElementById('modal_edit_usuario');

/*
*Dados Gerais
*/
let usuarioId = document.getElementById('usuarioId');
let csrfToken = document.getElementById('csrfToken');
let usuario = document.getElementById('usuario');
let tipo_usuario = document.getElementById('tipo_usuario');
let ecommerce = document.getElementById('ecommerce');
let status_usuario = document.getElementById('status');
let cep = document.getElementById('cep');
let logradouro = document.getElementById('logradouro');
let numero = document.getElementById('numero');
let complemento = document.getElementById('complemento');
let estado = document.getElementById('estado');
let bairro = document.getElementById('bairro');
let cidade = document.getElementById('cidade');

/*
*Responsavel
*/
let nome_usuario = document.getElementById('nome_usuario');
let email_usuario = document.getElementById('email_usuario');
let telefone = document.getElementById('telefone');
let tipo_emissao = document.getElementById('tipo_emissao');
let cpf = document.getElementById('cpf');
let cnpj = document.getElementById('cnpj');
let razao_social = document.getElementById('razao_social');
let grupo_taxa = document.getElementById('grupo_taxa');
let grupo_taxa_mini = document.getElementById('grupo_taxa_mini');
let link_indicacao = document.getElementById('link_indicacao');


const abreModalEditaUsuario = (idUsuario) => {
    modal_edit_usuario.classList.remove('hidden');
    modal_edit_usuario.classList.add('block');

    fetch(`http://localhost:8989/usuarios/${idUsuario}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        },
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro ao fazer requisição');
        }
        return response.json();
    })
    .then(data => {
        /*
        *Preenchendo os dados Gerais
        */
        usuarioId.value = data.user.id
        usuario.value = data.user.usuario
        ecommerce.value = data.user.name_ecommerce
        cep.value = data.user.cep
        numero.value = data.user.numero
        logradouro.value = data.user.logradouro
        complemento.value = data.user.complemento
        bairro.value = data.user.bairro
        estado.value = data.user.uf
        cidade.value = data.user.cidade

        /*
        *Preenchendo os dados do responsavel
        */
        nome_usuario.value = data.user.name
        email_usuario.value = data.user.email
        telefone.value = data.user.telefone
        tipo_emissao.value = data.user.tipo_emissao
        cpf.value = data.user.cpf
        cnpj.value = data.user.cnpj
        razao_social.value = data.user.razao_social
        grupo_taxa.value = data.user.grupo_taxa
        grupo_taxa_mini.value = data.user.grupo_taxa_pacmini
        link_indicacao.value = data.user.link_indicacao

    })
    .catch(error => {
        console.error('Erro:', error);
    });

}

/*
* Fazendo a requisição para atualizar os dados do usuario
*/
document.getElementById('submitFormEditaUsuario').addEventListener('submit', (e) => {
    e.preventDefault();

    let submitButtonEditUser       = document.getElementById('submitButtonEditUser');
    submitButtonEditUser.innerHTML = "Atualizando dados...."
    submitButtonEditUser.disabled  = true;

    const formData = {
        id             : usuarioId.value,
        _token         : csrfToken.value,
        usuario        : usuario.value,
        tipo_usuario   : tipo_usuario.value,
        ecommerce      : ecommerce.value,
        status_usuario : status_usuario.value,
        cep            : cep.value,
        logradouro     : logradouro.value,
        numero         : numero.value,
        complemento    : complemento.value,
        estado         : estado.value,
        bairro         : bairro.value,
        cidade         : cidade.value,
        nome_usuario   : nome_usuario.value,
        email_usuario  : email_usuario.value,
        telefone       : telefone.value,
        tipo_emissao   : tipo_emissao.value,
        cpf            : cpf.value,
        cnpj           : cnpj.value,
        razao_social   : razao_social.value,
        grupo_taxa     : grupo_taxa.value,
        grupo_taxa_mini: grupo_taxa_mini.value,
        link_indicacao : link_indicacao.value,
    };

    fetch(`http://localhost:8989/usuarios`, {
        method: 'POST',
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
        if (data.success === true) {
            submitButtonEditUser.innerHTML = "Salvar"
            submitButtonEditUser.disabled = false;

            Swal.fire({
                title: 'Sucesso!',
                text: data.message,
                icon: 'success',
                customClass: {
                    confirmButton: 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline-blue active:bg-blue-800',
                },
                buttonsStyling: false,
                confirmButtonText: 'OK',
            }).then(function () {
                window.location.href = 'http://localhost:8989/usuarios';
            });
        }
    })
    .catch(error => {
        console.error('Erro:', error);
    })
}); 

const fechaModalEditaUsuario = () => {
    modal_edit_usuario.classList.add('hidden');
}




