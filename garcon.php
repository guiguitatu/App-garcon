<?php
session_start();
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
        $numeroMesa = $_GET['mesa'];
        header('Location: ' . $_SERVER['PHP_SELF'] . '?mesa=' . $numeroMesa);
        exit;
    } elseif ((isset($_POST['produto']) && isset($_POST['preco']) && isset($_POST['cod_gruest'])) || isset($itensParaCarrinho)) {

        // Verifica se a sessão do carrinho já existe
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }

        // Adiciona os itens ao carrinho
        foreach ($itensParaCarrinho as $item) {
            $_SESSION['carrinho'][] = $item;
        }

        // Redireciona de volta para onde estava
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remover_item'])) {
    $index = $_POST['remover_item'];

    if (isset($_SESSION['carrinho'][$index])) {
        unset($_SESSION['carrinho'][$index]);
        $_SESSION['carrinho'] = array_values($_SESSION['carrinho']);

        $numeroMesa = $_GET['mesa'];
        header('Location: ' . $_SERVER['PHP_SELF'] . '?mesa=' . $numeroMesa);
        exit;
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

    $sql = "select produto.cod_proapp as PRODUTO, produto.descricao as NOME, produto.descricaonota as DESCRICAO, produto.valor as PRECO, PRODMOVBAR.quant as QUANTIDADE, PRODMOVBAR.obs AS OBSERVACAO, PRODMOVBAR.codigo AS ID from produto
inner join PRODMOVBAR on produto.cod_pro = PRODMOVBAR.cod_pro
inner join VENDABAR on VENDABAR.docto = PRODMOVBAR.docto
where VENDABAR.ficha = $mesa and VENDABAR.caixa = '' and PRODMOVBAR.VALOR_TOT > 0";

    try {
        $stmt = $conn->query($sql);
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $itensAgrupados = array();
        $resbool = false;

        if (!empty($resultado)) {
            $resbool = true;

            foreach ($resultado as $item) {
                $nome = $item['NOME'];
                $preco = $item['PRECO'];

                if (isset($itensAgrupados[$nome])) {
                    $itensAgrupados[$nome]['quantidade'] += 1;
                    $itensAgrupados[$nome]['precoTotal'] += $preco;
                } else {
                    $itensAgrupados[$nome] = array(
                        'quantidade' => 1,
                        'precoTotal' => $preco
                    );
                }
            }
        }

        echo '<div class="listapedidos">';
        if ($resbool) {
            echo '<ul>';

            foreach ($itensAgrupados as $nome => $info) {
                echo '<li>' . $info['quantidade'] . 'x ' . $nome . ' - R$ ' . number_format($info['precoTotal'], 2, ',') . '</li>';
            }

            echo '</ul>';
        } else {
            echo "<h1>Não há nada inserido na ficha ainda</h1>";
        }
        echo '</div>';
        echo '<button class="btnpedido" onclick="voltartelainicial()">Voltar a Tela de pedidos</button>';
    } catch (PDOException $e) {
        echo "Erro na execução da consulta: " . $e->getMessage();
    }
    ?>
</div>

<?php
$conn = new PDO('firebird:host=PC-Gui;dbname=D:\Astracon\Dados\ASTRABAR.FDB;charset=utf8', 'SYSDBA', 'masterkey');
$sql = "select COD_PROAPP, DESCRICAO, COD_GRUEST, VALOR from produto where COD_GRUEST is not null and valor is not null and cod_pro is not null and descricao is not null";
$stmt2 = $conn->query($sql);
$produtosAgrupados = array();
while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    $cod_gruest = $row['COD_GRUEST'];
    $produtosAgrupados[$cod_gruest][] = $row;
}
foreach ($produtosAgrupados as $cod_gruest => $produtos) {
    //echo '<div class="produto'.$cod_gruest .'" style="display:none; margin: 0px; padding: 0px; width: auto; height: auto;" id="' . $cod_gruest . '">';
    echo '<div class="product-group product-group'. $cod_gruest .'" id="' . $cod_gruest . '" style=display: none>';

    foreach ($produtos as $produto) {
        $produtoId = 'produto_' . $produto['COD_PROAPP'] . '_' . $cod_gruest;
        echo '<div class="product">';
        echo "<p>" . $produto['DESCRICAO'] . "- R$ " . number_format($produto['VALOR'], 2 , ',') . "</p>";
        echo '<form action="" class="produto" method="post" style="display: flex; align-items: center">';
        echo '<input type="hidden" name="produto" value="' . $produto['DESCRICAO'] . '">';
        echo '<input type="hidden" name="cod_pro" value="' . $produto['COD_PROAPP'] . '">';
        echo '<input type="hidden" name="preco" value="' . number_format($produto['VALOR'], 2, ",") . '">';
        echo '<input type="hidden" name="cod_gruest" value="' . $cod_gruest . '">';
        echo '<input type="button" class="btnquant" name="menos" onclick="alteraQuantidade(\'' . $produtoId . '\', -1)" value="-">';
        echo '<input name="quantidade" class="quant" id="' . $produtoId . '" value="0" min="0">';
        echo '<input type="button" class="btnquant" name="mais" onclick="alteraQuantidade(\'' . $produtoId . '\', 1)" value="+">';

        echo '</form>';
        echo '</div>';
      //  echo '</div>';
    }
    echo '<button class="btnpedido" onclick="escondediv(' . $cod_gruest . ')" style="align-content: center">Voltar a tela inicial</button>';
    echo '<button class="btn-flutuante" onclick="adicionarItensCarrinho(\'' . $cod_gruest . '\')">Adicionar Itens ao Carrinho</button>';
    echo '</div>';
}
?>

<!-- Carrinho para o pedido -->
<!--<div class="carrinho" id="100">
    <form action="" method="post" class="formcarrinho">
        <?php
        /*if (empty($_SESSION['carrinho'])) {
            echo "<h1>Nada adicionado ao carrinho ainda</h1>";
        } else {
            foreach ($_SESSION['carrinho'] as $index => $item) {
                echo '<div class="carrinhoitem" id="carrinho-' . $index . '">';
                echo '<div class="divitembtn">';
                echo "<button type='button' class='btnplus' onclick='fadeInObservacao(" . $index . ")'>+</button>";
                echo "<p style='font-size: 35px'> {$item['quantidade']} x {$item['produto']}</p>";
                echo "<button type='submit' class='btnremover' name='remover_item' value='" . $index . "'>Remover Item</button>";
                echo "<br>";
                echo '</div>';
                echo '<div style="display: flex; flex-direction: row">';

                $observacao = $item['observacao'] ?? '';

                echo '<input type="text" style="display:none" class="inputobs" name="observacao[' . $index . ']" placeholder="Digite a observação" class="input-observacao" value="' . htmlspecialchars($observacao) . '">';

                echo '<button type="button" style="display:none" class="btnobs" id="btn'.$index.'" onclick="adicionarObservacao(' . $index . ')">Adicionar Observação</button>';
                echo '</div>';
                echo '</div>';
            }
            echo '<div class="btnlimpaconfere">';
            echo '<input type="submit"  class="btnlimpacarrinho" name="limpar_carrinho" value="Limpar pedido">';
            echo '<button type="button" class="btnverificapedido" name="mandarpedido" value="Verificarpedido" class="btnsmanda" onclick="mostraconclusao(100)">Conferir o pedido e fazer a insercão</button>';
            echo '</div>';
        }
        echo '<button type="button" class="btnpedido" onclick="escondediv(100)">Adicionar item à ficha</button>';
        */?>
        <input type="hidden" name="refresh" value="1">
    </form>
</div>-->
<div class="carrinho" id="100">
    <form action="" method="post" class="formcarrinho">
        <?php
        if (empty($_SESSION['carrinho'])) {
            echo "<h1>Nada adicionado ao carrinho ainda</h1>";
        } else {
            foreach ($_SESSION['carrinho'] as $index => $item) {
                echo '<div class="carrinhoitem" id="carrinho-' . $index . '">';
                echo '<div class="divitembtn">';
                echo "<button type='button' class='btnplus' onclick='toggleObservacao(" . $index . ")'>+</button>";
                echo "<p style='font-size: 35px'> {$item['quantidade']} x {$item['produto']}</p>";
                echo "<button type='submit' class='btnremover' name='remover_item' value='" . $index . "'>Remover Item</button>";
                echo "<br>";
                echo '</div>';

                $observacao = $item['observacao'] ?? '';
                if ($observacao != null){
                    echo '<div style="display: flex; flex-direction: row" id="observacao-' . $index . '">';
                }else{
                    echo '<div style="display: none; flex-direction: row" id="observacao-' . $index . '">';
                }

                echo '<input type="text" style="display: flex" class="inputobs" name="observacao[' . $index . ']" placeholder="Digite a observação" class="input-observacao" value="' . htmlspecialchars($observacao) . '">';
                echo '<button type="button" class="btnobs" onclick="adicionarObservacao(' . $index . ')">Adicionar Observação</button>';
                echo '</div>';
                echo '</div>';
            }
            echo '<div class="btnlimpaconfere">';
            echo '<input type="submit"  class="btnlimpacarrinho" name="limpar_carrinho" value="Limpar pedido">';
            echo '<button type="button" class="btnverificapedido" name="mandarpedido" value="Verificarpedido" class="btnsmanda" onclick="mostraconclusao(100)">Conferir o pedido e fazer a insercão</button>';
            echo '</div>';
        }
        echo '<button type="button" class="btnpedido" onclick="escondediv(100)">Adicionar item à ficha</button>';
        ?>
        <input type="hidden" name="refresh" value="1">
    </form>
</div>

<button class='btnpedido' onclick="mostrapedidos()">Ver os itens da ficha</button>
<div class="pedidoconfere" id="conferepedido">
    <form action="insercao.php?mesa=<?php echo $mesa ?>" method="post">
        <?php
        foreach ($_SESSION['carrinho'] as $index => $item) {
            echo '<div class="carrinho-item" id="carrinho-' . $index . '">';
            echo "<p style='font-size: 35px;'>{$item['quantidade']} x {$item['produto']} - $ {$item['preco']}</p>";
            if ($item['observacao'] != ""){
                echo "<p style='font-size: 25px'> Observação: {$item['observacao']}</p>";
            }
        }
        echo '<button type="submit" name="mandarpedido" value="Verificarpedido" class="btnsmanda">Fazer a inserção na ficha</button>';
        echo '<button type="button" class="btnpedido" onclick="escondediv(100)">Voltar para tela do pedido';
        ?>
    </form>
</div>
<!-- div para imprimir os itens da variável de sessão (DEBUG)
<div class="itens-carrinho" style="display: block">
<h2>DEBUG</h2>-->
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
<!--</div>-->
</body>
</html>