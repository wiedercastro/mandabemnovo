
let modal_deleta_faq = document.getElementById('modal_deleta_faq');

const deletaFaq = (faqId) => {
    console.log("deletar faq: " + faqId)
    
    modal_deleta_faq.classList.remove('hidden'); 
    modal_deleta_faq.classList.add('flex');
}

const cancelaDelecaoFaq = () => {
    modal_deleta_faq.classList.add('hidden');
}