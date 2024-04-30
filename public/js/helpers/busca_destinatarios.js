const buscaPorDestinatario = async (event) => {
    let textoDigitado = event.target.value

    const res = await fetch(`http://localhost:8989/buscaDestinatario?text=${encodeURIComponent(textoDigitado)}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        },
    });

    if (!res.ok) {
        throw new Error('Erro ao fazer requisição');
    }

    const resJson = await res.json();
    let destinatarioList = event.target.parentElement.querySelector('.resultDestinatarios');

    if (resJson.destinatario.length === 0 || textoDigitado.length === 0) {
        destinatarioList.classList.add('hidden');
    } else {
        destinatarioList.classList.remove('hidden');
    }

    let lista = ''

    resJson.destinatario.forEach(item => {
        lista +=
        `<ul>
            <li class="bg-white-50 hover:bg-blue-500 hover:text-white hover:font-bold text-xs px-2 py-1 cursor-pointer">${item.destinatario}</li>
        </ul>`;
    });

    destinatarioList.innerHTML = lista;
}