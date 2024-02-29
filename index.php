<?php
session_start();
if (!$_COOKIE['usuario']){
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.CSS">

    <title>Formulário</title>
</head>
<body>
<div class="main">
    <form action="criaficha.php" method="get" class="form">
        <label for="mesa">Digite o numero da mesa:</label>
        <div class="btns">
            <input type="text" id="mesa" name="mesa" pattern="[0-9]+" title="Número da mesa" readonly class="input">
            <button type="button" class="btnlimpa" onclick="limpa()">Limpar</button>
        </div>

        <div class="btns">
            <button type="button" class="btnnums" onclick="mudanum(1)">1</button>
            <button type="button" class="btnnums" onclick="mudanum(2)">2</button>
            <button type="button" class="btnnums" onclick="mudanum(3)">3</button>
        </div>
        <div class="btns">
            <button type="button" class="btnnums" onclick="mudanum(4)">4</button>
            <button type="button" class="btnnums" onclick="mudanum(5)">5</button>
            <button type="button" class="btnnums" onclick="mudanum(6)">6</button>
        </div>
        <div class="btns">
            <button type="button" class="btnnums" onclick="mudanum(7)">7</button>
            <button type="button" class="btnnums" onclick="mudanum(8)">8</button>
            <button type="button" class="btnnums" onclick="mudanum(9)">9</button>
        </div>
        <div class="btns">
            <button type="button" class="zero" onclick="mudanum(0)">0</button>
            <button type="button" class="btnnums" onclick="backspace()">⌫</button>
        </div>
        <button type="submit" style="margin-top: 5px">Enviar</button>
    </form>
</div>
<script>

    function limpa(){
     let inp = document.getElementById('mesa');
     inp.value = ''
    }

    function mudanum(numero){
        let inp = document.getElementById('mesa');
        if (inp.value.length < 5){
            inp.value = inp.value + numero;
        }
    }

    function backspace(){
        let inp = document.getElementById('mesa');
        inp.value = inp.value.slice(0, -1);
    }
</script>
</body>
</html>