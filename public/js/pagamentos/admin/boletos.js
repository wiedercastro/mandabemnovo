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
            <tr class="bg-white hover:bg-gray-100 border rounded-full font-light">
                <td class="px-6 py-4">${formattedDate}</td>
                <td class="px-6 py-4">${item.value}</td>
                <td class="px-6 py-4">${item.cliente}</td>
                <td class="px-6 py-4">R$ ${item.credito}</td>
                <td class="px-6 py-4"></td>
                <td class="px-6 py-4">${item.status}</td>
                <td class="px-6 py-4"></td>
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