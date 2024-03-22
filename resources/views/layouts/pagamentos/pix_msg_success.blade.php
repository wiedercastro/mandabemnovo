<h2>
    <?php if (isset($data->type) && $data->type == 'review') : ?>
        <small style="font-size: 20px;">
            Basta ler o QR code na opção PIX do seu Internet Bank e efetuar o pagamento, ou então copiar o código e colar na área "Pix Copia e Cola" também na opção PIX do seu Internet Bank.
        </small>

    <?php else : ?>
        Prontinho!<br>
        <small style="font-size: 20px;">
            Agora basta ler o QR code na opção PIX do seu Internet Bank e efetuar o pagamento, ou então copiar o código e colar na área "Pix Copia e Cola" também na opção PIX do seu Internet Bank.
        </small>

    <?php endif; ?>
</h2>
<center>
    <div class="" style="" id="content-to-cpy-barcode">

        <img src=" <?php echo $data->pix['qrcode']?>" class="img-thumbnail" style="width: 200px"?>

    </div>
    <div class="box-radius" style="display: none;" id="content-to-cpy-barcode">
        <span class="red"><i class="fa fa-info-circle"></i> Selecione o código do QR Code e clique em "Ctrl + C" para copiar:</span><br>
        <input style="width: 100%; border: none;" id="inp-boleto-barcode" value="<?php echo $data->pix['qrcode_text']?>">
    </div>


    <div class="text-center" style="margin-right: 12px; margin-bottom: 8px; margin-top: 5px;">
        <button style="padding: 10px;" class="btn btn-primary" type="button" id="btn-cpy-clipb"><i class="fa fa-barcode"></i> PIX Copia e Cola</button>
    </div>
</center>
<div class="clear"></div>


<script>


    var copyBtn = $("#btn-cpy-clipb");
    var inputCOPYBOARD = $("#inp-boleto-barcode");

    function copyToClipboardFF(text) {
        window.prompt("Copy to clipboard: Ctrl C, Enter", text);
    }

    function copyToClipboard() {
        var success = true,
                range = document.createRange(),
                selection;

        // For IE.
        if (window.clipboardData) {
            window.clipboardData.setData("Text", inputCOPYBOARD.val());
        } else {
            // Create a temporary element off screen.
            var tmpElem = $('<div>');
            tmpElem.css({
                position: "absolute",
                left: "-1000px",
                top: "-1000px",
            });
            // Add the input value to the temp element.
            tmpElem.text(inputCOPYBOARD.val());
            $("body").append(tmpElem);
            // Select temp element.
            range.selectNodeContents(tmpElem.get(0));
            selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);
            // Lets copy.
            try {
                success = document.execCommand("copy", false, null);
            } catch (e) {
                $('#content-to-cpy-barcode').show();
//                copyToClipboardFF(inputCOPYBOARD.val());
            }
            if (success) {
                $('#content-to-cpy-barcode').show();
//                alert("The text is on the clipboard, try to paste it!");
                // remove temp element.
                if (!$('#info-barcode-copied').length) {
                    $('<br><small id="info-barcode-copied"><i class="fa fa-check"></i> Copiado com Sucesso!.</small>').insertAfter(copyBtn);
                }
                tmpElem.remove();
            }
        }
    }

    copyBtn.on('click', copyToClipboard);

    $(function () {

        $('#inp-valor-pix').val('');
        new Clipboard(".btn");

    });

</script>