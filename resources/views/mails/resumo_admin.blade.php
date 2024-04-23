<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <h2>Olá Pessoal, tudo Bem?</h2><br>
        <div id="content">
            <p>Codigo de Rastreio:</p>
            <span>{{$envio->etiqueta_correios}}</span>
        </div>
        <div>
            {{$resumo_texto}}
        </div><br>
        <span>Pode verificar por favor?</span><br>
        <p>Muito obrigado,</p><br>
        <p>Equipe<span style="color: #25688B"> Manda</span> <span style="color: rgb(141, 21, 21)">Bem :)</span></p>
    </body>
</html>


<style>

#content {
    display: flex;
    align-items: center;
}
</style>