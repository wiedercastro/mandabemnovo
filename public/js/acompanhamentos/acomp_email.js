
let modal_container = document.getElementById('modal-container');
let modal_info_acompanhamentos = document.getElementById('modal-info-acompanhamentos');

const fechaModalAcompanhamento = () => {
  modal_container.classList.add('hidden');
}

const exibiModalComInformacoes = () => {
  modal_info_acompanhamentos.classList.remove('hidden'); 
  modal_info_acompanhamentos.classList.add('flex');
}

const fechaModalComInformacoes = () => {
  modal_info_acompanhamentos.classList.add('hidden');
}

const abreModalAcompanhamento = (id) => {
  fetch(`http://localhost:8989/acomp_email/${id}`, {
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
    document.getElementById('assunto').value = data.data.subject;
    document.getElementById('corpo_email').value = data.data.body;
    document.getElementById('id').value = data.data.id;
  })
  .catch(error => {
    console.error('Erro:', error);
  });
  modal_container.classList.remove('hidden'); 
  modal_container.classList.add('flex');
}


const enviaFormulario = document.getElementById('submitForm');
const submitButton = document.getElementById('submitFormButton');

enviaFormulario.addEventListener('submit', (e) => {
  e.preventDefault();
  
  submitButton.innerHTML = "Salvando...."
  submitButton.disabled = true;

  const formData = {
    assunto    : document.getElementById('assunto').value,
    corpo_email: document.getElementById('corpo_email').value,
    id         : document.getElementById('id').value,
    _token     : document.getElementById('_token').value,
  };

  fetch(`http://localhost:8989/acomp_email`, {
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
    if (data === 'ok') {
      alert("Dados atualizados :)")
    }
    
    submitButton.innerHTML = "Salvar"
    submitButton.disabled = false;

    //fecha modal após o sucesso
    modal_container.classList.add('hidden');
  })
  .catch(error => {
    console.error('Erro:', error);
  });
});


