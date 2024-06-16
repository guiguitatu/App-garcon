<?php
session_start();
$_SESSION['origin'] = 'token';
include_once('conexao.php');
include('trocanome.php');
$token = $_COOKIE['token'];
    try {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo 'Não foi possível conectar ao banco.';
    }

    $sql = 'SELECT TOKEN FROM DISPOSITIVOS';

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($resultado as $row){
            if ($row['TOKEN'] == $_COOKIE['token']){
                $bool = true;
                break;
            }
        }

        if ($bool){
            header('Location: index.php');
        }
    } catch (PDOException $e) {
        echo 'Não foi possível fazer a consulta no banco';
    }



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
    <h5>Token:  <?php echo $token?></h5> <br>

    <form action="token.php" style="width: 100vw; display: flex; justify-content: center;">
        <button type="submit" class="btnpedido" style="width: 50%; height: 40px; font-size: 30px">Verificar o token</button>
    </form>


</div>

</body>
</html>
