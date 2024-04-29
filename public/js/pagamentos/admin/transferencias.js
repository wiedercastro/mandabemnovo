let modal_transferencias = document.getElementById('modal_transferencias');
let modal_edit_banco_user = document.getElementById('modal_edit_banco_user');

const abreModalTransferencias = () => {
    modal_transferencias.classList.remove('hidden');
    modal_transferencias.classList.add('flex');

    fetch('transferencia', {
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
        let rows = '';
    
        data.data.forEach(item => {
            const date    = new Date(item.date_insert);
            const day     = String(date.getDate()).padStart(2, '0'); 
            const month   = String(date.getMonth() + 1).padStart(2, '0'); 
            const year    = date.getFullYear(); 
            const hours   = String(date.getHours()).padStart(2, '0'); 
            const minutes = String(date.getMinutes()).padStart(2, '0'); 
            const seconds = String(date.getSeconds()).padStart(2, '0');
        
            const formattedDate = `${day}/${month}/${year} ${hours}:${minutes}:${seconds}`;
          
            rows += `
            <tr class="bg-white hover:bg-gray-100 border-b rounded-full font-light">
                <td class="px-6 py-4">${formattedDate}</td>
                <td class="px-6 py-4 flex items-center">
                    <span class="mr-1">${item.banco}</span>
                    <svg 
                        onclick="editBancoCliente(${item.id})" 
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 stroke-blue-600 hover:stroke-blue-700 cursor-pointer">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                    </svg>
                </td>
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

const fechaModalTransferencias = () => {
    modal_transferencias.classList.add('hidden');
}

const editBancoCliente = (idUser) => {
    modal_edit_banco_user.classList.remove('hidden');
    modal_edit_banco_user.classList.add('flex');
}

const fechaModalEditBancoCliente = () => {
    modal_edit_banco_user.classList.add('hidden');
}

