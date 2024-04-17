
let modal_insere_faq = document.getElementById('modal_insere_faq');
let mandabemChecked = false;
let ecommerceChecked = false;

const showModalFaq = (faqId = null) => {
    modal_insere_faq.classList.remove('hidden'); 
    modal_insere_faq.classList.add('flex');

    if (faqId) {
        handleFetchDadosFaq(faqId)
    } 
}

const clearFormFields = () => {
    document.getElementById('idFaqEdit').value = '';
    document.getElementById('categoria').value = '';
    document.getElementById('pergunta').value = '';
    document.getElementById('resposta').value = '';
    document.getElementById('visivel_mandabem').checked = false;
    document.getElementById('visivel_ecommerce').checked = false;    
}

const checkboxMandabem = document.getElementById('visivel_mandabem');
const checkboxEcommerce = document.getElementById('visivel_ecommerce');

checkboxMandabem.addEventListener('change', function() {
    mandabemChecked = this.checked;
});

checkboxEcommerce.addEventListener('change', function() {
    ecommerceChecked = this.checked;
});

const fechaModalFaq = () => {
    clearFormFields()
    modal_insere_faq.classList.add('hidden');
}

document.getElementById('submitFormFaq').addEventListener('submit', (e) => {
    e.preventDefault();

    let check_box_visivel_mandabem = false
    let check_box_visivel_ecommerce = false 
    let idFaqEdit = document.getElementById('idFaqEdit'); 
    let urlRota = '' 
    let typeMethod = '' 

    if (idFaqEdit.value !== "") {
        urlRota = `http://localhost:8989/faq/${idFaqEdit.value}`
        typeMethod = 'PUT'
        check_box_visivel_mandabem = document.getElementById('visivel_mandabem').checked,
        check_box_visivel_ecommerce = document.getElementById('visivel_ecommerce').checked
    } else {
        urlRota = `http://localhost:8989/faq`
        typeMethod = 'POST'
        check_box_visivel_mandabem = mandabemChecked,
        check_box_visivel_ecommerce =  ecommerceChecked
    }

    let categoria = document.getElementById('categoria');
    let pergunta = document.getElementById('pergunta');
    let resposta = document.getElementById('resposta');
    let csrfToken = document.getElementById('csrfToken');
    
    let buttonCreateFaq = document.getElementById('buttonCreateFaq')
    buttonCreateFaq.innerHTML = "Salvando...."
    buttonCreateFaq.disabled = true;

    const formData = {
        categoria        : categoria.value,
        pergunta         : pergunta.value,
        resposta         : resposta.value,
        visivel_mandabem : check_box_visivel_mandabem,
        visivel_ecommerce: check_box_visivel_ecommerce,
        _token           : csrfToken.value
    };

    fetch(urlRota, {
        method: typeMethod,
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
                location.reload();
            });
        }
    })
    .catch(error => {
        console.error('Erro:', error);
    })
    .finally(() => {
        buttonCreateFaq.innerHTML = "Salvar"
        buttonCreateFaq.disabled = false;
    })
}); 




