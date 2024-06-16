<?php
session_start();
include('trocanome.php');
include_once('conexao.php');

if ($_SESSION['mesa'] == null or $_SESSION['mesa'] == '') {

}
if ($_COOKIE['usuario']) {
    $cod = $_COOKIE['usuario']['codido'];
    $gar = $_COOKIE['usuario']['nome'];
    $gararray = array(
        'codido' => $cod,
        'garcon' => $gar
    );
    $_SESSION['usuario'] = $gararray;
    $codgar = $_SESSION['usuario']['codido'];
    $garcon = $_SESSION['usuario']['garcon'];
} else {
    header("Location: login.php");
}

date_default_timezone_set('America/Sao_Paulo');

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['refresh']) && $_GET['refresh'] == 1) {
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

$itensParaCarrinho = json_decode($_POST['itens_para_carrinho'], true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($itensParaCarrinho)) {
    if (isset($_POST['limpar_carrinho'])) {
        unset($_SESSION['carrinho']);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } elseif ((isset($_POST['produto']) && isset($_POST['preco']) && isset($_POST['cod_gruest'])) || isset($itensParaCarrinho)) {

        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }

        foreach ($itensParaCarrinho as $item) {
            $_SESSION['carrinho'][] = $item;
        }

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remover_item'])) {
        $index = $_POST['remover_item'];

        if (isset($_SESSION['carrinho'][$index]) && is_numeric($index) && $index >= 0) {
            if ($_SESSION['carrinho'][$index]['quantidade'] > 1) {
                $_SESSION['carrinho'][$index]['quantidade']--;
            } else {
                unset($_SESSION['carrinho'][$index]);
                $_SESSION['carrinho'] = array_values($_SESSION['carrinho']);
            }

            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } else {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }

    if (isset($_POST['adicionar_item'])) {
        $index = $_POST['adicionar_item'];

        if (isset($_SESSION['carrinho'][$index]) && is_numeric($index) && $index >= 0) {
            $_SESSION['carrinho'][$index]['quantidade']++;

            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } else {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['observacao'])) {
    foreach ($_POST['observacao'] as $index => $observacao) {
        $observacao = trim($observacao);
        $_SESSION['carrinho'][$index]['observacao'] = $observacao;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Garçon</title>
    <meta charset="UTF-8" name="viewport" content="user-scalable=no">
    <link rel="shortcut icon" href="imgs/logoastraconbranco.png" type="imagem">
    <link rel="stylesheet" href="CSS.CSS">
    <script src="JSgarcon.js"></script>

</head>
<body>

<header>
    <div style="position: relative; width: 100vw;margin-left:50px; z-index: 10; height: 100px; display: flex; justify-content: flex-start; align-items: center">
        <div style="display: flex; flex-direction: row; align-items: center; height: 100%; justify-content: center">
            <h3>Astra</h3>
            <?php
            $mesa = $_SESSION['mesa'];
            if ($_SESSION['opcao'] == 'mesa') {
                echo '<h3>' . ' | Mesa: ' . $mesa . ' | </h3>';
            } else {
                echo '<h3>' . ' | Ficha: ' . $mesa . ' | </h3>';
            }
            echo ' ';
            echo '<h3>Usuário: ' . $garcon . '</h3>';
            ?>
        </div>
        <div style="position: absolute; z-index: 5; right: 0; height: 70px">
            <a href="sairmesa.php">
                <button class="btnsair">Sair do pedido</button>
            </a>
        </div>
    </div>
</header>

<!--Mostra os botões dpara a inserção no pedido-->
<div class="btns" id="200">
    <div class="busca" id="divbusca">
        <input type="text" class="inputbusca" id="search" onkeyup="searchFunction()" placeholder="Buscar produto...">
        <div id="result" class="resultado"></div>
    </div>

    <div class="botao-container">
        <?php
        try {
            $sql = 'select * from GRUPOEST order by NOME';
            $stmt = $conn->query($sql);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                echo '<button class="btndivs" onclick="escondediv(' . $row['COD_GRUEST'] . ')"> <img src="imgs/comer.png" style="height: 100px; width: 100px;"> <br>' . $row['NOME'] . '</button>';

            }
        } catch (PDOException $e) {
            echo "Erro de conexão: " . $e->getMessage();
        }
        ?>
    </div>
    <?php
    if (empty($_SESSION['carrinho'])) {
        echo '<br><h1>Não foi adicionado nada no carrinho</h1>';
    } else {
        echo '<button class="btnpedido" onclick="voltartelainicial()">Ver o que está sendo colocado no pedido</button>';
    }
    ?>
</div>
<!-- Mostra os itens para o pedido -->

<?php
if (empty($_SESSION['carrinho'])) {
    echo '<div id="produtos" class="pedidos" id="100" style="display: flex">';
} else {
    echo '<div id="produtos" class="pedidos" id="100" style="display: none">';
}

$mesa = $_SESSION['mesa'];

try {
    
    if ($_SESSION['opcao'] == 'ficha') {
        $sql = "SELECT produto.cod_proapp AS PRODUTO, produto.descricao AS NOME, produto.descricaonota AS DESCRICAO, produto.valor AS PRECO, PRODMOVBAR.quant AS QUANTIDADE, PRODMOVBAR.obs AS OBSERVACAO, PRODMOVBAR.codigo AS ID FROM produto
    INNER JOIN PRODMOVBAR ON produto.cod_pro = PRODMOVBAR.cod_pro
    INNER JOIN VENDABAR ON VENDABAR.docto = PRODMOVBAR.docto
    WHERE VENDABAR.ficha = $mesa AND (VENDABAR.caixa = '' OR VENDABAR.caixa is null) AND (VENDABAR.situacao <> 'C' OR VENDABAR.situacao is null) AND PRODMOVBAR.VALOR_TOT > 0";
    } else {
        $sql = "SELECT produto.cod_proapp AS PRODUTO, produto.descricao AS NOME, produto.descricaonota AS DESCRICAO, produto.valor AS PRECO, PRODMOVBAR.quant AS QUANTIDADE, PRODMOVBAR.obs AS OBSERVACAO, PRODMOVBAR.codigo AS ID FROM produto
    INNER JOIN PRODMOVBAR ON produto.cod_pro = PRODMOVBAR.cod_pro
    INNER JOIN VENDABAR ON VENDABAR.docto = PRODMOVBAR.docto
    WHERE VENDABAR.mesa = $mesa AND (VENDABAR.caixa = '' OR VENDABAR.caixa is null) AND (VENDABAR.situacao <> 'C' OR VENDABAR.situacao is null) AND PRODMOVBAR.VALOR_TOT > 0";
    }

    $stmt = $conn->query($sql);
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $itensAgrupados = array();

    foreach ($resultado as $item) {
        $nome = $item['NOME'];
        $quantidade = $item['QUANTIDADE'];

        if (isset($itensAgrupados[$nome])) {
            $itensAgrupados[$nome]['QUANTIDADE'] += $quantidade;
        } else {
            $itensAgrupados[$nome] = array(
                'NOME' => $nome,
                'QUANTIDADE' => $quantidade,
            );
        }
    }
    echo '<div class="listapedidos">';
    if (!empty($itensAgrupados)) {
        echo '<ul>';
        foreach ($itensAgrupados as $item) {
            echo '<li>' . round($item['QUANTIDADE']) . 'x ' . $item['NOME'] . '</li>';
        }
        echo '</ul>';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo '<button class="btnpedido" onclick="voltartelainicial()">Inserir item à ficha</button>';
            echo '<button class="btnpedido" onclick="telabtns()">Voltar para tela dos botões';
        } else {
            echo '<button class="btnpedido" onclick="telabtns()"> Inserir item na ficha </button>';
        }
    } else {
        echo "<h1>Não há nada inserido na ficha ainda</h1>";
        echo '<button class="btnpedido" onclick="telabtns(200)"> Inserir item na ficha </button>';
    }
    echo '</div>';

} catch (PDOException $e) {
    echo "Erro na execução da consulta: " . $e->getMessage();
}
?>
</div>
<!-- Mostra as divs de cada COD_GRUEST -->
<?php
$sql = "select COD_PROAPP, DESCRICAO, COD_GRUEST, VALOR from produto where COD_GRUEST is not null and valor is not null and cod_pro is not null and descricao is not null";

$stmt2 = $conn->query($sql);
$produtosAgrupados = array();
while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    $cod_gruest = $row['COD_GRUEST'];
    $produtosAgrupados[$cod_gruest][] = $row;
}

// Função de comparação para ordenar os produtos por descrição
function compararDescricao($a, $b) {
    return strcmp($a['DESCRICAO'], $b['DESCRICAO']);
}

// Ordenar produtos dentro de cada grupo
foreach ($produtosAgrupados as $cod_gruest => &$produtos) {
    usort($produtos, 'compararDescricao');
}
unset($produtos); // Desreferenciar para evitar erros futuros

foreach ($produtosAgrupados as $cod_gruest => $produtos) {
    echo '<div class="product-group product-group' . $cod_gruest . '" id="' . $cod_gruest . '" style="display: none">';
    echo '<button class="btnpedido" onclick="escondediv(' . $cod_gruest . ')" style="align-content: center">Voltar a tela de botões</button>';
    foreach ($produtos as $produto) {
        $produtoId = 'produto_' . $produto['COD_PROAPP'] . '_' . $cod_gruest;
        echo '<div class="product">';
        echo '<form action="" class="produto" method="post" onkeydown="return event.key != ' . "'Enter'" . ';">';
        echo '<input type="hidden" name="produto" value="' . $produto['DESCRICAO'] . '">';
        echo '<input type="hidden" name="cod_pro" value="' . $produto['COD_PROAPP'] . '">';
        echo '<input type="hidden" name="preco" value="' . number_format($produto['VALOR'], 2, ",") . '">';
        echo '<input type="hidden" name="cod_gruest" value="' . $cod_gruest . '">';
        echo '<input type="button" class="btnquant" id="mais' . $produtoId . '" name="mais" onclick="alteraQuantidade(\'' . $produtoId . '\', 1)" value="+">';
        echo '<input name="quantidade" class="quant" id="' . $produtoId . '" value="0" min="0">';
        echo '<input type="button" class="btnquant" name="menos" onclick="alteraQuantidade(\'' . $produtoId . '\', -1)" value="-">';
        echo "<p style='font-size: 30px'>" . $produto['DESCRICAO'] . "</p>";
        echo '</form>';
        echo '</div>';
    }
    echo '</div>';
}
echo '<button class="btn-flutuante" style="display:none;" id="adicionar-todos-carrinho"  onclick="adicionarItensCarrinho()">Adicionar Itens ao Carrinho</button>';
?>
<!-- Div para o carrinho -->
<div class="carrinho" id="carrinhodiv">
    <?php
    if (empty($_SESSION['carrinho'])) {
        echo '<form action="" method="post" class="formcarrinho" id="carrinhoform" style="display: none" onkeydown="return event.key != \'Enter\';">';
    } else {
        echo '<form action="" method="post" class="formcarrinho" id="carrinhoform" onkeydown="return event.key != \'Enter\';">';
    }
    ?>

    <?php
    if (empty($_SESSION['carrinho'])) {
        echo "<h1>Nada adicionado ao carrinho ainda</h1>";
    } else {
        echo '<div style="width: 80vw; height: auto; display: flex; flex-direction: column; align-items: flex-start;">';
        foreach ($_SESSION['carrinho'] as $index => $item) {
            echo '<div class="carrinhoitem" id="carrinho-' . $index . '">';
            echo '<div class="divitembtn">';
            echo "<button type='button' class='btnplus' onclick='toggleObservacao(" . $index . ")'><b>≡</b></button>";
            echo "<p style='font-size: 30px'> {$item['produto']}</p>";
            if ($item['quantidade'] > 1) {
                echo "<button type='submit' class='btnquant' name='remover_item' value='{$index}'><b>-</b></button>";
            } else {
                echo "<button type='submit' style='height:55px; background-color: #ff4655; width: auto; padding: 0 5px 0 5px; font-size: 25px' class='btnquant' name='remover_item' value='{$index}'>Remover item</button>";
            }
            echo "<p style='font-size: 43px'> {$item['quantidade']}</p>";
            echo "<button type='submit' class='btnquant' name='adicionar_item' value='{$index}'><b>+</b></button>";
            echo "<br>";
            echo '</div>';

            $observacao = $item['observacao'] ?? '';
            if ($observacao != null) {
                echo '<div style="display: flex; flex-direction: row; width: 80vw; justify-content: flex-start" id="observacao-' . $index . '">';
            } else {
                echo '<div style="display: none; flex-direction: row; width: 80vw; justify-content: flex-start" id="observacao-' . $index . '">';
            }

            echo '<input type="text" style="display: flex" class="inputobs" name="observacao[' . $index . ']" placeholder="Digite a observação" class="input-observacao" value="' . htmlspecialchars($observacao) . '" onsubmit="hideMobileKeyboardOnEnter(event)">';
            echo '<button type="button" class="btnobs" onclick="adicionarObservacao(' . $index . ')">Adicionar Observação</button>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
        echo '<div class="btnlimpaconfere">';
        echo '<button type="submit" class="btnlimpacarrinho" name="limpar_carrinho" value="Limpar pedido">Limpar pedido</button>';
        echo '<button type="button" class="btnverificapedido" name="mandarpedido" value="Verificarpedido" class="btnsmanda" onclick="mostraconclusao(300)">Conferir o pedido</button>';
        echo '</div>';
    }
    echo '<button type="button" class="btnpedido" onclick="mostrabtns(200)">Adicionar item à ficha</button>';
    ?>
    <input type="hidden" name="refresh" value="1">
    </form>
</div>


<button class='btnpedido' onclick="mostrapedidos()" id="btnverpedido" id="btnverprodutos" style="display: none">Ver os
    itens da ficha
</button>
<div class="pedidoconfere" id="conferepedido">
    <form action="insercao.php?mesa=<?php echo $mesa ?>" method="post" style="justify-content: center">
        <?php
        foreach ($_SESSION['carrinho'] as $index => $item) {
            echo '<div class="carrinho-item" id="carrinho-' . $index . '">';
            //echo "<li style='font-size: 35px;'>{$item['quantidade']} x {$item['produto']} - $ {$item['preco']}</li>";
            echo "<li style='font-size: 45px;'>{$item['quantidade']} x {$item['produto']}</li>";
            if ($item['observacao'] != "") {
                echo "<p style='font-size: 35px'> Observação: {$item['observacao']}</p>";
            }
        }
        echo '<button type="submit" name="mandarpedido" value="Verificarpedido" class="btnenvia">Fazer a inserção na ficha</button>';
        echo '<button type="button" class="btnpedido" style="width: 500px;" onclick="voltartelainicial()">Voltar para tela do pedido';
        ?>
    </form>
</div>
<!-- div para imprimir os itens da variável de sessão (DEBUG)
<div class="itens-carrinho" style="display: block">
<h2>DEBUG</h2>
<?php
//    if (!empty($_SESSION['carrinho'])) {
//        foreach ($_SESSION['carrinho'] as $index => $item) {
//           echo '<div class="carrinho-item" id="carrinho-' . $index . '">';
//            echo "<p>{$item['produto']} - $ {$item['preco']} - Grupo {$item['cod_gruest']}</p>";
//            if (isset($item['observacao'])) {
//                echo '<p class="observacao" id="observacao-' . $index . '">Observação: ' . $item['observacao'] . '</p>';
//            }
//            echo '</div>';
//        }
//    } else {
//        echo "<h1>Carrinho vazio</h1>";
//    }
//    ?>
</div>-->
</body>
</html>