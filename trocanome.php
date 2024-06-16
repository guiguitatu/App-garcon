<?php
session_start();
include_once('conexao.php');
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

$_SESSION['tudocerto'] = true;

if (!$_COOKIE['token']){
    function generateToken($length): string
    {
        $characters = '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ';
        $token = '';
        $maxIndex = strlen($characters) - 1;

        for ($i = 0; $i < $length; $i++) {
            $randomIndex = mt_rand(0, $maxIndex);
            $token .= $characters[$randomIndex];
        }

        return $token;
    }

    setcookie('token', generateToken(10), time() + 60 * 60 * 24 * 7 * 4 * 12);
} else {
    try {
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
        if ($_SESSION['origin'] != 'token') {
            if (!$bool) {
                header('location: token.php');
            }
        }
    } catch (PDOException $e) {
        echo "Erro na consulta" . $e->getMessage();
    }
}
