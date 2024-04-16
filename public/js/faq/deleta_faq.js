
let modal_deleta_faq = document.getElementById('modal_deleta_faq');
let faqIdValue = document.getElementById('idFaq');


const modalDeletaFaq = (faqId) => {
    faqIdValue.value = faqId;

    modal_deleta_faq.classList.remove('hidden'); 
    modal_deleta_faq.classList.add('flex');
}

const deletaFaq = () => {
    let csrfToken = document.getElementById('csrfTokenFaq');
    let buttonDelete = document.getElementById('buttonDelete');
    buttonDelete.innerHTML = "Deletando...."
    buttonDelete.disabled  = true;

    const FAQ_ID = faqIdValue.value
    
    if (FAQ_ID) {
        fetch(`http://localhost:8989/faq/${FAQ_ID}`, {
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
            if (data.success) {
                Swal.fire({
                    title: 'Sucesso',
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
    }
}


const cancelaDelecaoFaq = () => {
    modal_deleta_faq.classList.add('hidden');
}