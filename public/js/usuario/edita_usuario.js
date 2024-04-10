let modal_edit_usuario = document.getElementById('modal_edit_usuario');

/*
*Dados Gerais
*/
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

    fetch(`http://localhost:8989/users/${idUsuario}`, {
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

  
const fechaModalEditaUsuario = () => {
    modal_edit_usuario.classList.add('hidden');
}


