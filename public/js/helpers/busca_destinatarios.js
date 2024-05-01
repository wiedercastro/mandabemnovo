const buscaClientes = async (event) => {
    let textoDigitado = event.target.value

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
    let clientesList = event.target.parentElement.querySelector('.resultDestinatarios');

    console.log(resJson);

    if (resJson.clientes.length === 0 || textoDigitado.length === 0) {
        clientesList.classList.add('hidden');
    } else {
        clientesList.classList.remove('hidden');
    }

    let lista = ''

    resJson.clientes.forEach(item => {
        lista +=
        `<ul>
            <li onclick="selecionaDestinatario('${item.name}', ${item.id})" class="bg-white-50 hover:bg-blue-500 hover:text-white hover:font-bold text-xs px-2 py-1 cursor-pointer">${item.name}</li>
        </ul>`;
    });

    clientesList.innerHTML = lista;
}

const selecionaDestinatario = (nameUser, idUser) => {
    console.log(nameUser, idUser)

    const cliente = document.getElementById('cliente');
    const id_cliente = document.getElementById('id_cliente');

    if (cliente) {
        cliente.value = nameUser;
        id_cliente.value = idUser;
    }

   /*  let clientesList = event.target.parentElement.querySelector('.resultDestinatarios');
    //clientesList.classList.remove('hidden');
    clientesList.classList.add('hidden'); */
}