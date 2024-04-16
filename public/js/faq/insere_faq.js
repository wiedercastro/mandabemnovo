
let modal_insere_faq = document.getElementById('modal_insere_faq');

const showModalFaq = (faqId = null) => {
    modal_insere_faq.classList.remove('hidden'); 
    modal_insere_faq.classList.add('flex');

    if (faqId) {
        handleFetchDadosFaq(faqId)
    } 
}

const fechaModalFaq = () => {
    modal_insere_faq.classList.add('hidden');
}


const handleFetchDadosFaq = (id) => {

    fetch(`http://localhost:8989/faq/${id}`, {
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
        /* usuarioId.value = data.user.id
        usuario.value = data.user.usuario
        ecommerce.value = data.user.name_ecommerce */
    })
    .catch(error => {
        console.error('Erro:', error);
    });

}

const submitNewFaq = () => {
    document.getElementById('submitFormFaq').addEventListener('submit', (e) => {
        e.preventDefault();

        let teste = document.getElementById('teste');
        let csrfToken = document.getElementById('csrfToken');
    
        let buttonCreateFaq = document.getElementById('buttonCreateFaq');
        buttonCreateFaq.innerHTML = "Criando...."
        buttonCreateFaq.disabled  = true;

    
        const formData = {
            teste             : teste.value,
            _token         : csrfToken.value,
       /*      usuario        : usuario.value,
            tipo_usuario   : tipo_usuario.value,
            ecommerce      : ecommerce.value, */
        };
    
        fetch(`http://localhost:8989/faq`, {
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
                buttonCreateFaq.innerHTML = "Salvar"
                buttonCreateFaq.disabled = false;
    
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
    
}



