<?php
session_start();
include('trocanome.php');
date_default_timezone_set('America/Sao_Paulo');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $garcon = $_POST['garcon'] ?? '';
    $senha = $_POST['senha'];

    if ($garcon == "") {
        $_SESSION['erro'] = ["Garçom não selecionado"];
    } else {
        try {
            $conn = new PDO('firebird:host=nomepc;dbname=caminhoarquivoFDBnosistema;charset=utf8', 'SYSDBA', 'masterkey');

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT PS from REPRESENTANTE where NOMEREP = '$garcon'";
            $sqlcod = "SELECT COD_REP from REPRESENTANTE where NOMEREP = '$garcon'";
            $stmt = $conn->prepare($sql);
            $stmtcod = $conn->prepare($sqlcod);
            $stmt->execute();
            $stmtcod->execute();
            $ps = $stmt->fetchColumn();
            $cod = $stmtcod->fetchColumn();
            if ($ps == $senha) {
                $_SESSION['senha'] = $senha;
                $garconarray = array(
                    'codido' => $cod,
                    'nome' => $garcon
                );
                $_SESSION['usuario'] = $garconarray;
                $exp = time() + 60 * 60 * 24 * 7;
                setcookie('usuario[codido]', $cod, $exp);
                setcookie('usuario[nome]', $garcon, $exp);
                header('Location: index.php');
                exit;
            } else {
                $_SESSION['erro'] = ["Senha incorreta"];
            }
        } catch (PDOException $e) {
            $_SESSION['erro'] = ["Erro ao conectar ao banco de dados"];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Astraconbar</title>
    <meta charset="UTF-8" name="viewport" content="user-scalable=no">
    <link rel="stylesheet" href="index.css">
</head>
<body class="login">
<main class="principal">
    <div class="conteudo">
        <h2> Faça seu login </h2>
        <?php if ($_SESSION['erro']): ?>
            <div class="erro">
                <?php foreach ($_SESSION['erro'] as $erro): ?>
                    <p><?= $erro ?></p>
                <?php endforeach ?>
            </div>
        <?php endif;
        unset($_SESSION['erro']);
        ?>
        <form action="#" method="post" class="login">
            <div class="input">
                <label for="garcon">Usuário:</label>
                <select name="garcon" id="garcon">
                    <option value="">Selecione um garçom</option>
                    <?php
                    $conn = new PDO('firebird:host=nomepc;dbname=caminhoarquivoFDBnosistema;charset=utf8', 'SYSDBA', 'masterkey');
                    $stmt = $conn->query('SELECT NOMEREP from REPRESENTANTE');
                    $garcons = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($garcons as $garcon) {
                        echo "<option value='{$garcon['NOMEREP']}'>{$garcon['NOMEREP']}</option>";
                    }
                    $conn = null;
                    ?>
                </select>
            </div>
            <div class="input">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <button type="submit">Entrar</button>
        </form>
    </div>
</main>
</body>
</html>
