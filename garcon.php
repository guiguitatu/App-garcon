<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

// Lógica para lidar com a atualização da página
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['refresh']) && $_GET['refresh'] == 1) {
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Lógica para adicionar produtos ao carrinho
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['limpar_carrinho'])) {
        echo 'caralhadas';
        unset($_SESSION['carrinho']);
        $numeroMesa = $_GET['mesa'];
        header('Location: ' . $_SERVER['PHP_SELF'] . '?mesa=' . $numeroMesa);
        exit;
    } elseif (isset($_POST['produto']) && isset($_POST['preco']) && isset($_POST['cod_gruest'])) {
        $produto = $_POST['produto'];
        $preco = $_POST['preco'];
        $cod_gruest = $_POST['cod_gruest'];

        $item = array('produto' => $produto, 'preco' => $preco, 'cod_gruest' => $cod_gruest, 'observacao' => ''); // Inicialize com uma string vazia

        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = array();
        }

        $_SESSION['carrinho'][] = $item;
    }
}

// Lógica para processar a remoção de um item do carrinho
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remover_item'])) {
    $index = $_POST['remover_item'];
    unset($_SESSION['carrinho'][$index]);
    $_SESSION['carrinho'] = array_values($_SESSION['carrinho']);
    $numeroMesa = $_GET['mesa'];
    header('Location: ' . $_SERVER['PHP_SELF'] . '?mesa=' . $numeroMesa);
    exit;
}

// Lógica para adicionar observação
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['observacao'])) {
    foreach ($_POST['observacao'] as $index => $observacao) {
        $observacao = trim($observacao);
        $_SESSION['carrinho'][$index]['observacao'] = $observacao;
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
$stmt2 = $conn->query($sql);

// Página HTML
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Astraconbar</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="CSS.CSS">
    <script src="JSgarcon.js"></script>

</head>
<body>
<header>
    <h1>Astra</h1>
    <?php
    $mesa = $_GET['mesa'];
    echo '<h1>' . ' | Mesa: ' . $mesa . ' |</h1>';
    echo' ';
    echo '<h1>Última seção em: ' . date('d/m h:i') . '</h1>';
    ?>
</header>
<!--Mostra os botões dpara a inserção no pedido-->
<div class="btns" id="carrossel">
    <?php
    try {
        $conn = new PDO('firebird:host=PC-Gui;dbname=D:\Astracon\Dados\ASTRABAR.FDB;charset=utf8', 'SYSDBA', 'masterkey');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'select * from GRUPOEST order by NOME';
        $stmt = $conn->query($sql);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

            if ($row['COD_GRUEST'] != 9) {
                echo '<button class="btndivs" onclick="escondediv(' . $row['COD_GRUEST'] . ')"> <img src="imgs/comer.png"style="height: 150px; width: 150px;"> <br>' . $row['NOME'] . '</button>';
            } else {
                echo '<button class="btndivs" onclick="escondediv(' . $row['COD_GRUEST'] . ')"><img src="imgs/comer.png" style="height: 150px; width: 150px;"> DESTILADOS </button>';
            }
        }
    } catch (PDOException $e) {
        echo "Erro de conexão: " . $e->getMessage();
    }

    ?>
    <button class='btnpedido' onclick="mostrapedidos()">Ver os itens da ficha</button>
    <?php
    if (empty($_SESSION['carrinho'])) {
        echo '<h1>Não foi adicionado nada no carrinho</h1>';
    } else {
        echo '<button class="btnpedido" onclick="escondediv(100)">Ver o que está sendo colocado no pedido</button>';
    }
    ?>
</div>
<!-- Mostra os itens para o pedido -->
<div id="produtos" class="pedidos">
    <?php
    $mesa = $_GET['mesa'];
    $conn = new PDO('firebird:host=PC-Gui;dbname=D:\Astracon\Dados\ASTRABAR.FDB;charset=utf8', 'SYSDBA', 'masterkey');

    $sql = "select produto.cod_proapp as PRODUTO ,produto.descricao as NOME, produto.descricaonota as DESCRICAO ,produto.valor as PRECO,PRODMOVBAR.quant as QUANTIDADE,PRODMOVBAR.obs AS OBSERVACAO,PRODMOVBAR.codigo AS ID from produto
inner join  PRODMOVBAR on produto.cod_pro = PRODMOVBAR.cod_pro
inner join  VENDABAR on VENDABAR.docto = PRODMOVBAR.docto
where  VENDABAR.ficha = $mesa and VENDABAR.caixa = ''";
    $stmt = $conn->query($sql);
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Array associativo para rastrear a quantidade e o preço total de cada item
    $itensAgrupados = array();

    foreach ($resultado as $item) {
        $nome = $item['NOME'];
        $preco = $item['PRECO'];

        // Se o item já existe no array, atualiza a quantidade e o preço
        if (isset($itensAgrupados[$nome])) {
            $itensAgrupados[$nome]['quantidade'] += 1;
            $itensAgrupados[$nome]['precoTotal'] += $preco;
        } else {
            // Se é a primeira vez que encontramos o item, adiciona ao array
            $itensAgrupados[$nome] = array(
                'quantidade' => 1,
                'precoTotal' => $preco
            );
        }
    }

    // Exibe a lista agrupada
    echo '<div class="listapedidos">';
    echo '<ul>';

    foreach ($itensAgrupados as $nome => $info) {
        echo '<li>' . $info['quantidade'] . 'x ' . $nome . ' - R$ ' . number_format($info['precoTotal'], 2, ',') . '</li>';
    }

    echo '</ul>';
    echo'</div>';
    echo '<button class="btnpedido" onclick="voltartelainicial()">Voltar a Tela de pedidos</button>';
    ?>
</div>

<?php
// Agrupar produtos por COD_GRUEST
$produtosAgrupados = array();
while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    $cod_gruest = $row['COD_GRUEST'];
    $produtosAgrupados[$cod_gruest][] = $row;
}

// Exibir produtos agrupados
foreach ($produtosAgrupados as $cod_gruest => $produtos) {
    echo '<div class="product-group" id="' . $cod_gruest . '">';
    echo "<h2>{$produtos[0]['NOME']}</h2>";

    foreach ($produtos as $produto) {
        echo '<div class="product">';
        echo "<p>" . $produto['DESCRICAO'] . "- $ " . number_format($produto['VALOR'], 2 , ',') . "</p>";
        echo '<form action="" method="post">';
        echo '<input type="hidden" name="produto" value="' . $produto['DESCRICAO'] . '">';
        echo '<input type="hidden" name="preco" value="' . number_format($produto['VALOR'], 2, ",") . '">';
        echo '<input type="hidden" name="cod_gruest" value="' . $cod_gruest . '">';
        echo '<input type="submit" value="Adicionar ao Pedido" class="bot">';
        echo '</form>';
        echo '</div>';
    }
    echo '<button class="btnpedido" onclick="escondediv(' . $cod_gruest . ')" style="align-content: center">Voltar a tela inicial</button>';
    echo '</div>';
}
?>
<div class="carrinho" id="100">
    <form action="" method="post">
        <?php
        if (empty($_SESSION['carrinho'])) {
            echo "<p>Carrinho vazio</p>";
        } else {
            foreach ($_SESSION['carrinho'] as $index => $item) {
                echo '<div class="carrinho-item" id="carrinho-' . $index . '">';
                echo '<div class="divitembtn">';
                echo "<p>{$item['produto']} - $ {$item['preco']} - Grupo {$item['cod_gruest']}</p>";
                echo "<button type='submit' name='remover_item' value='" . $index . "'>Remover</button>";
                echo '</div>';
                echo '<button type="button" onclick="mostrarObservacao(' . $index . ')">Adicionar Observação</button>';

                $observacao = $item['observacao'] ?? '';
                echo '<p class="observacao" id="observacao-' . $index . '">Observação: ' . $observacao . '</p>';

                echo '<input type="text" name="observacao[' . $index . ']" placeholder="Digite a observação" class="input-observacao"';
                if (!empty($observacao)) {
                    echo ' value="' . $observacao . '"';
                }
                echo '>';
                echo '</div>';
            }
            echo '<input type="submit" name="limpar_carrinho" value="Limpar Carrinho">';
            echo '<button class="btnpedido" onclick="escondediv(100)">Voltar a tela inicial';
        }
        ?>
        <input type="hidden" name="refresh" value="1">
    </form>
</div>

<!-- div para imprimir os itens da variável de sessão -->
<div class="itens-carrinho">
    <h2>Itens no Carrinho</h2>
    <?php
    if (!empty($_SESSION['carrinho'])) {
        foreach ($_SESSION['carrinho'] as $index => $item) {
            echo '<div class="carrinho-item" id="carrinho-' . $index . '">';
            echo "<p>{$item['produto']} - $ {$item['preco']} - Grupo {$item['cod_gruest']}</p>";
            if (isset($item['observacao'])) {
                echo '<p class="observacao" id="observacao-' . $index . '">Observação: ' . $item['observacao'] . '</p>';
            }

            echo '</div>';
        }
    } else {
        echo "<p>Carrinho vazio</p>";
    }
    ?>
</div>

</body>
</html>