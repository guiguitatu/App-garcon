<?php
session_start();

// Lógica para adicionar produtos ao carrinho
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar se o botão "Limpar Carrinho" foi pressionado
    if (isset($_POST['limpar_carrinho'])) {
        unset($_SESSION['carrinho']);
    } elseif (isset($_POST['produto']) && isset($_POST['preco']) && isset($_POST['cod_gruest'])) {
        $produto = $_POST['produto'];
        $preco = $_POST['preco'];
        $cod_gruest = $_POST['cod_gruest'];

        $item = array('produto' => $produto, 'preco' => $preco, 'cod_gruest' => $cod_gruest);

        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = array();
        }

        array_push($_SESSION['carrinho'], $item);
    }
}

// Conectar ao banco de dados
$conn = new PDO('firebird:host=PC-Gui;dbname=D:\Astracon\Dados\ASTRABAR.FDB;charset=utf8', 'SYSDBA', 'masterkey');

// Consulta SQL para obter produtos agrupados por grupo
$sql = "SELECT P.COD_PROAPP, P.DESCRICAO, P.COD_GRUEST, P.VALOR, G.NOME
        FROM produto P
        LEFT JOIN GRUPOEST G ON P.COD_GRUEST = G.COD_GRUEST
        WHERE P.COD_GRUEST IS NOT NULL AND P.VALOR IS NOT NULL AND P.COD_PROAPP IS NOT NULL AND P.DESCRICAO IS NOT NULL and p.COD_GRUEST >= 1
        ORDER BY P.COD_GRUEST";

$stmt = $conn->query($sql);

// Página HTML
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <style>
        .product-group {
            margin-bottom: 20px;
            border: 1px solid #ccc;
            padding: 10px;
        }

        .product {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<h1>Produtos Disponíveis</h1>

<?php
// Agrupar produtos por COD_GRUEST
$produtosAgrupados = array();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $cod_gruest = $row['COD_GRUEST'];
    $produtosAgrupados[$cod_gruest][] = $row;
}

// Exibir produtos agrupados
foreach ($produtosAgrupados as $cod_gruest => $produtos) {
    echo '<div class="product-group">';
    echo "<h2>{$produtos[0]['NOME']}</h2>"; // Exibir o nome do grupo

    foreach ($produtos as $produto) {
        echo '<div class="product">';
        echo "<p>{$produto['DESCRICAO']} - $ {$produto['VALOR']}</p>";
        echo '<form action="" method="post">';
        echo '<input type="hidden" name="produto" value="' . $produto['DESCRICAO'] . '">';
        echo '<input type="hidden" name="preco" value="' . $produto['VALOR'] . '">';
        echo '<input type="hidden" name="cod_gruest" value="' . $cod_gruest . '">';
        echo '<input type="submit" value="Adicionar ao Carrinho">';
        echo '</form>';
        echo '</div>';
    }

    echo '</div>';
}
?>

<h1>Carrinho de Compras</h1>

<form action="" method="post">
    <?php
    if (empty($_SESSION['carrinho'])) {
        echo "<p>Carrinho vazio</p>";
    } else {
        foreach ($_SESSION['carrinho'] as $item) {
            echo "<p>{$item['produto']} - $ {$item['preco']} - Grupo {$item['cod_gruest']}</p>";
        }
        // Adicione o botão "Limpar Carrinho"
        echo '<input type="submit" name="limpar_carrinho" value="Limpar Carrinho">';
    }
    ?>
</form>

<a href="checkout.php">Finalizar Compra</a>

</body>
</html>
