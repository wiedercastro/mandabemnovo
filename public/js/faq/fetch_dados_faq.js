const handleFetchDadosFaq = (id) => {
    
    let idFaqEdit = document.getElementById('idFaqEdit');
    let categoria = document.getElementById('categoria');
    let pergunta = document.getElementById('pergunta');
    let resposta = document.getElementById('resposta');
    let visivel_mandabem = document.getElementById('visivel_mandabem');
    let visivel_ecommerce = document.getElementById('visivel_ecommerce');

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
        idFaqEdit.value = data.faq.id
        categoria.value = data.faq.category_id
        resposta.value = data.faq.answer
        pergunta.value = data.faq.question
        visivel_ecommerce.checked = data.faq.visible_customer === 1;
        visivel_mandabem.checked = data.faq.visible_mandabem === 1;
    })
    .catch(error => {
        console.error('Erro:', error);
    });

}