<?php
if (!$_COOKIE['usuario']){
    header("Location: login.php");
    exit();
} else if (!$_COOKIE['token']){
    unset($_SESSION['senha']);
    header("location: esse.php");
} else {
    try {
        $conn = new PDO('firebird:host=PC-Gui;dbname=D:\Astracon\Dados\ASTRABAR.FDB;charset=utf8', 'SYSDBA', 'masterkey');
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
                $_SESSION['teste'] = 'barbaridade';
                header('location: esse.php');
            } else echo 'caralho';
        }

    } catch (PDOException $e) {
        echo "Erro na consulta" . $e->getMessage();
    }

    echo 'porrra';

}

$arquivos = array('login.php', 'garcon.php', 'criaficha.php', 'insercao.php', 'index.php', 'trocanome.php');

$txtantigo = 'firebird:host=PC-Gui;dbname=D:\Astracon\Dados\ASTRABAR.FDB;charset=utf8';
$txtnovo = 'firebird:host=PC-Gui;dbname=D:\Astracon\Dados\ASTRABAR.FDB;charset=utf8';

foreach ($arquivos as $arquivo) {
    $cont = file_get_contents($arquivo);

    $newcont = str_replace($txtantigo, $txtnovo, $cont);

    file_put_contents($arquivo, $newcont);
};