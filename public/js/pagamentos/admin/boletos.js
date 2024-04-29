let modal_boletos = document.getElementById('modal_boletos');

const abreModalBoletos = () => {
    modal_boletos.classList.remove('hidden'); 
    modal_boletos.classList.add('flex');

    fetch('boleto', {
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
        console.log(data)

        let rows = '';
    
        data.data.forEach(item => {
            const date    = new Date(item.date_insert);
            const day     = String(date.getDate()).padStart(2, '0'); 
            const month   = String(date.getMonth() + 1).padStart(2, '0'); 
            const year    = date.getFullYear(); 
        
            const formattedDate = `${day}/${month}/${year}`;
          
            rows += `
            <tr class="bg-white hover:bg-gray-100 border-b rounded-full font-light">
                <td class="px-6 py-4">${formattedDate}</td>
                <td class="px-6 py-4">${item.value}</td>
                <td class="px-6 py-4">${item.cliente}</td>
                <td class="px-6 py-4">
                    <p class="ml-1">R$ ${item.valor_solicitado}</p>
                </td>
                <td class="px-2 py-2 text-green-600 font-bold">
                    <a href="https://mandabem.com.br/pagamento/ver_comprov_transf/${item.documento}" target="_blank" class="flex items-center cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                        </svg>
                        <p class="text-xs">Ver</p>
                    </a>
                </td>
                <td class="px-1 py-3">${item.status}</td>
            </tr>
            `;
        });
    
        // Insira todas as linhas de uma vez no corpo da tabela
        document.getElementById('responseBody').innerHTML = rows

    })
    .catch(error => {
        console.error('Erro:', error);
    });
}

const fechaModalBoletos = () => {
  modal_boletos.classList.add('hidden');
}