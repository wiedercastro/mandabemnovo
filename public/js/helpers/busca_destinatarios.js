const buscaClientes = async (event) => {
    const inputElement = event.target; // O campo de entrada onde o evento foi disparado
    const textoDigitado = inputElement.value;

    // Encontra o contêiner do modal a partir do elemento que acionou o evento
    const modalContainer = inputElement.closest('.form-container');
    const clientesList = modalContainer.querySelector('.resultDestinatarios');

    const res = await fetch(`http://localhost:8989/buscaClientes?text=${encodeURIComponent(textoDigitado)}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        },
    });

    if (!res.ok) {
        throw new Error('Erro ao fazer requisição');
    }

    const resJson = await res.json();

    if (resJson.clientes.length === 0 || textoDigitado.length === 0) {
        clientesList.classList.add('hidden');
    } else {
        clientesList.classList.remove('hidden');
    }

    let lista = '';
    resJson.clientes.forEach(item => {
        lista += `
        <ul>
            <li data-id="${item.id}" onclick="selecionaCliente(this)" class="bg-white-50 hover:bg-blue-500 hover:text-white hover:font-bold text-xs px-2 py-1 cursor-pointer">${item.name}</li>
        </ul>`;
    });

    clientesList.innerHTML = lista;
};

const selecionaCliente = (element) => {

    const modalContainer = element.closest('.form-container');
    const clienteInput = modalContainer.querySelector('.cliente');
    const idClienteInput = modalContainer.querySelector('.id_cliente'); 

    const itemName = element.innerText; // Nome do cliente
    const itemId = element.getAttribute('data-id'); // Adicione este atributo ao criar a lista

    clienteInput.value = itemName;
    idClienteInput.value = itemId;
};
