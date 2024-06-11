<?php
session_start();
if ($_COOKIE['usuario']) {
    if ($_COOKIE['usuario']['nome'] == null or $_COOKIE['usuario']['nome'] == '' ){
        header("Location: login.php");
    }
    $codgarcon = $_COOKIE['usuario']['codido'];
    $garcon = $_COOKIE['usuario']['nome'];
} else {
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="imgs/logoastraconbranco.png" type="imagem">
    <link rel="stylesheet" href="./index.css">

    <title>Config</title>

</head>
<body style="justify-content: center">

    <main class="config">
        <div class="conteudo">
            <?php
                echo '  <div style="width: 95%; display: flex; flex-direction: column; align-items: center; justify-content: center">
                            <p><b>Token: ' . $_COOKIE['token'] . '</b></p><a href="sairtoken.php" style="width: 100%; display: flex; justify-content: center; text-decoration: none;"><button class="btnsair">Excluir Token</button></a>
                        </div> <br>
                        <a href="index.php" style="width: 100%; display: flex; justify-content: center; text-decoration: none;"> <button style="width: 60%; font-size: 20px">Voltar</button></a>';
            ?>
        </div>

    </main>

</body>
</html>