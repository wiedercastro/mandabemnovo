
let modal_insere_faq = document.getElementById('modal_insere_faq');

const showModalFaq = (faqId = null) => {
    if (faqId) {
        console.log('esta editando')
    } else {
        console.log('esta criando')
    }
    modal_insere_faq.classList.remove('hidden'); 
    modal_insere_faq.classList.add('flex');
}

const fechaModalFaq = () => {
    modal_insere_faq.classList.add('hidden');
}