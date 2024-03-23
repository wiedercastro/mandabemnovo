let armazenaValorPixQrCode = document.getElementById('valorQrCode')

$('#gerarQR').submit(function(event) {
  event.preventDefault();

  $('#preloaderGerarQrCode').prop('disabled', true).text('Gerando Qr Code...');

  var formData = new FormData(document.getElementById('gerarQR'));

  $.ajax({
      url: 'http://localhost:8989/gerarPix',
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function(data) {
        console.log(data.pix)
        if (data.pix.status === "qr_code_created") {
          let qrCodeURL = data.pix.qrcode;
          armazenaValorPixQrCode.value = data.pix.qrcode_text
          $('#image_pix_pagamento').attr('src', qrCodeURL);
        }
      },
      error: function(xhr, status, error) {
        // Lidar com erros
      }
  }).always(function() {
    $('#preloaderGerarQrCode').prop('disabled', false).text('Gerar Qrcode');
    abreModalParaPixPagamento();
  });
});



$(document).ready(function() {
  $('#valor_pix').inputmask('currency', {
      prefix: '', // Altere o prefixo conforme necessário
      alias: 'numeric',
      autoGroup: true,
      digits: 2,
      radixPoint: ",",
      groupSeparator: ".",
      allowMinus: false,
      rightAlign: false,
      numericInput: true, // Define entrada numérica da direita para a esquerda
      removeMaskOnSubmit: false
  });

  // Adiciona um evento de clique ao campo
  $('#valor_declaracao').click(function() {
      // Verifica se o valor é igual a zero
      if ($(this).val() === "0") {
          // Define o valor como null
          $(this).val(null);
      }
  });
});