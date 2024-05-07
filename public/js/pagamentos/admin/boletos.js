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
        console.log(data.boletos)

        let rows = '';
    
        data.boletos.forEach(item => {
            const date    = new Date(item.date_insert);
            const day     = String(date.getDate()).padStart(2, '0'); 
            const month   = String(date.getMonth() + 1).padStart(2, '0'); 
            const year    = date.getFullYear(); 
        
            const formattedDate = `${day}/${month}/${year}`;
          
            rows += `
            <tr class="bg-white hover:bg-gray-100 border rounded-full font-bold">
                <td class="px-6 py-4">${formattedDate}</td>
                <td class="px-6 py-4">${item.value}</td>
                <td class="px-6 py-4 max-w-sm">${item.cliente}</td>
                <td class="px-2 py-4">R$ ${item.credito}</td>
                <td class="px-2 py-4">teste</td>
                <td class="px-2 py-4">${item.status}</td>
                <td class="px-2 py-4 flex items-center text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                    </svg>
                    <p>Bol</p>
                </td>
                <td class="px-2 py-4">
                    <button class="bg-blue-500 hover:bg-blue-600 px-2 py-1 rounded">
                        <i class="fa fa-edit text-white"></i>
                    </button>
                    <button class="bg-red-500 hover:bg-red-600 px-2 py-1 rounded">
                        <i class="fa fa-trash text-white"></i>
                    </button>
                    <button class="bg-green-600 hover:bg-green-700 px-2 py-1 rounded text-white">
                        Liberar
                    </button>
                </td>
            </tr>
            `;
        });
    
        // Insira todas as linhas de uma vez no corpo da tabela
        document.getElementById('resultBoletos').innerHTML = rows

    })
    .catch(error => {
        console.error('Erro:', error);
    });
}

const fechaModalBoletos = () => {
  modal_boletos.classList.add('hidden');
}