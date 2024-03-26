<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Garçon</title>
    <meta charset="UTF-8" name="viewport" content="user-scalable=no">
    <link rel="shortcut icon" href="imgs/logoastraconbranco.png" type="imagem">
    <link rel="stylesheet" href="CSS.CSS">

</head>
<body>

<header>
    <div style="position: relative; width: 100vw; z-index: 10; height: 100px; display: flex; justify-content: center; align-items: center">
        <div style="display: flex; flex-direction: row; align-items: center; height: 100%; justify-content: center">
            <h1>Astra</h1>
        </div>
        <div style="position: absolute; z-index: 5; right: 0; height: 70px">
        </div>
    </div>
</header>

<div style="display: flex; flex-direction: column; align-items:center; width: 100vw">
<h5>Dispositivo não habilitado</h5> <br>
<h5>Entre em contato com o responsável do estabelecimento informando o token abaixo para habilitação do dispositivo.</h5> <br>
<h5> Token:  <?php echo $_COOKIE['token']?></h5>


</div>

</body>
</html>
