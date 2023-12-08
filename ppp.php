<?php
session_start();

// Lógica para adicionar produtos ao carrinho
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['produto']) && isset($_POST['preco'])) {
        $produto = $_POST['produto'];
        $preco = $_POST['preco'];

        $item = array('produto' => $produto, 'preco' => $preco);

        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = array();
        }

        array_push($_SESSION['carrinho'], $item);
    }
}
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Carrinho de Compras</title>
    </head>
    <body>
    <h1>Produtos Disponíveis</h1>

    <div>
        <p>Produto 1 - $10.00</p>
        <form action="" method="post">
            <input type="hidden" name="produto" value="Produto 1">
            <input type="hidden" name="preco" value="10.00">
            <input type="submit" value="Adicionar ao Carrinho">
        </form>
    </div>

    <div>
        <p>Produto 2 - $20.00</p>
        <form action="" method="post">
            <input type="hidden" name="produto" value="Produto 2">
            <input type="hidden" name="preco" value="20.00">
            <input type="submit" value="Adicionar ao Carrinho">
        </form>
    </div>

    <h1>Carrinho de Compras</h1>

    <?php
    if (empty($_SESSION['carrinho'])) {
        echo "<p>Carrinho vazio</p>";
    } else {
        foreach ($_SESSION['carrinho'] as $item) {
            echo "<p>{$item['produto']} - $ {$item['preco']}</p>";
        }
    }
    ?>
    <?php
    $mesa = $_GET['mesa'];
    // Conectar ao banco de dados
    $conn = new PDO('firebird:host=PC-Gui;dbname=D:\Astracon\Dados\ASTRABAR.FDB;charset=utf8', 'SYSDBA', 'masterkey');
    $sql = "select COD_PROAPP, COD_GRUEST, DESCRICAO, from PRODUTO order by COD_GRUEST where COD_GRUEST >=1";
    $smtp = $conn->query($sql);
    while ($row = $smtp->fetch(PDO::FETCH_ASSOC)){
        echo 'teste';
    }

    ?>
    <a href="checkout.php">Finalizar Compra</a>
    </body>
    </html>
