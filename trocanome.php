<?php

if ($_SERVER['REQUEST_URI'] == '/login') {
    header('Location: /login.php');
    exit;
}elseif ($_SERVER['REQUEST_URI'] == '/index') {
    header('Location: /index.php');
    exit;
}elseif ($_SERVER['REQUEST_URI'] == '/garcon') {
    header('Location: /garcon.php');
    exit;
}

$arquivos = array('login.php', 'garcon.php', 'criaficha.php', 'insercao.php', 'index.php');

$txtantigo = 'firebird:host=nomepc;dbname=caminhoarquivo;charset=utf8';
//trocar essa linha de baixo:
$txtnovo = 'firebird:host=nomedopc;dbname=caminhoarquivoFDBnosistema;charset=utf8';

foreach ($arquivos as $arquivo) {
    $cont = file_get_contents($arquivo);

    $newcont = str_replace($txtantigo, $txtnovo, $cont);

    file_put_contents($arquivo, $newcont);
}

if (!$_COOKIE['token']){
    function generateToken($length): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $token = '';
        $maxIndex = strlen($characters) - 1;

        for ($i = 0; $i < $length; $i++) {
            $randomIndex = mt_rand(0, $maxIndex);
            $token .= $characters[$randomIndex];
        }

        return $token;
    }

    setcookie('token', generateToken(10), time() + 60 * 60 * 24 * 7 * 4 * 12);
}if (!$_COOKIE['usuario']){
    header("Location: login.php");
    exit();
}else {
    try {
        //trocar essa linha de baixo:
        $conn = new PDO('firebird:host=nomedopc;dbname=caminhoarquivoFDBnosistema;charset=utf8', 'SYSDBA', 'masterkey');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "erro de conexão: " . $e->getMessage();
    }

    $sql = "select TOKEN from DISPOSITIVOS";

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

        if (!$bool) {header('location: esse.php');}

    } catch (PDOException $e) {
        echo "Erro na consulta" . $e->getMessage();
    }
}