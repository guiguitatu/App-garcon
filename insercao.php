<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
if ($_COOKIE['usuario']) {
    $cod = $_COOKIE['usuario']['codido'];
    $gar = $_COOKIE['usuario']['nome'];
    $gararray = array(
        'codido' => $cod,
        'garcon' => $gar
    );
    $_SESSION['usuario'] = $gararray;
    $codgarcon = $_SESSION['usuario']['codido'];
    $garcon = $_SESSION['usuario']['garcon'];
} else {
    header("Location: login.php");
}

if (!empty($_SESSION['carrinho'])) {

    try {
        $mesa = $_GET['mesa'];
        $conn = new PDO('firebird:host=nomedopc;dbname=caminhoarquivoFDBnosistema;charset=utf8', 'SYSDBA', 'masterkey');


        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        foreach ($_SESSION['carrinho'] as $item) {
            $null = null;
            $produto = $item['produto'];
            $preco = $item['preco'];
            $cod_gruest = $item['cod_gruest'];
            $quantidade = $item['quantidade'];
            $cod_pro = $item['cod_pro'];
            $hora = date('H:i:s');
            $observacao = $item['observacao'];
            $codgar = intval($codgarcon);
            // Consultas SQL para obter valores necessários
            $sqlCodPro = "SELECT cod_pro FROM produto WHERE COD_PROAPP = " . $cod_pro;
            $sqlValor = "SELECT VALOR FROM produto WHERE COD_PROAPP = " . $cod_pro;
            if ($_SESSION['opcao'] == 'ficha'){
                $sqlDocto = "select VENDABAR.docto as ID from VENDABAR  where ficha =" . $mesa . " and caixa = ''";
            } else {
                $sqlDocto = "select VENDABAR.docto as ID from VENDABAR  where mesa =" . $mesa . " and caixa = ''";
            }
            $sqldata = "select DATACAIXA from EMPRESA";
            $stmtCodPro = $conn->prepare($sqlCodPro);
            $stmtValor = $conn->prepare($sqlValor);
            $stmtDocto = $conn->prepare($sqlDocto);
            $stmtdata = $conn->prepare($sqldata);

            $stmtCodPro->execute();
            $stmtValor->execute();
            $stmtDocto->execute();
            $stmtdata->execute();
            $cod_pro = $stmtCodPro->fetchColumn();
            $valor_unit = floatval($stmtValor->fetchColumn());
            $docto = intval($stmtDocto->fetchColumn());
            $datasemform = $stmtdata->fetchColumn();

            $data = date('Y-m-d', strtotime($datasemform));

            echo $data;

            var_dump($cod_pro);
            echo '<br>';
            var_dump($valor_unit);
            echo '<br>';
            var_dump($docto);
            echo '<br>';
            $valor_tot = $valor_unit * $quantidade;

            $sqlMaxCodigo = "SELECT MAX(codigo) + 1 FROM PRODMOVBAR";
            $maxCodigo = $conn->query($sqlMaxCodigo)->fetchColumn();
            echo 'inserção';
            echo '<br>';
            $sqlInsercao = "INSERT INTO PRODMOVBAR (CODIGO, DOCTO, COD_PRO, QUANT, VALOR_UNIT, VALOR_TOT, COD_GAR, DATAMOV, HORA, OBS) 
                            VALUES (:codigo, :docto, :cod_pro, :quantidade, :valor_unit, :valor_tot, :cod_gar, CAST('" . $data . "' as DATE), :hora, :observacao)";

            $stmtInsercao = $conn->prepare($sqlInsercao);
            echo 'Código : Int    ';
            echo'<br>';
            var_dump($maxCodigo);
            echo'<br>';
            echo 'Docto : Int    ';
            echo'<br>';
            var_dump($docto);
            echo'<br>';
            echo 'Cod pro : Varchar    ';
            echo'<br>';
            var_dump($cod_pro);
            echo'<br>';
            echo 'Quantidade : float    ';
            echo'<br>';
            var_dump($quantidade);
            echo'<br>';
            echo 'Valor unit: float    ';
            echo'<br>';
            var_dump($valor_unit);
            echo'<br>';
            echo 'Valor total : float    ';
            echo'<br>';
            var_dump($valor_tot);
            echo'<br>';
            echo'cod Gar: int    ';
            echo'<br>';
            var_dump($codgar);
            echo'<br>';
            echo'data : ';
            echo'<br>';
            echo $data;
            echo '<br>';
            echo'hora : ' . $hora . '    ';
            echo'<br>';
            var_dump($hora);
            echo'<br>';
            echo'<br>';
            echo 'observacao : Varchar    ';
            echo'<br>';
            var_dump($observacao);
            echo'<br>';
            $stmtInsercao->bindParam(':codigo', $maxCodigo, PDO::PARAM_INT);
            $stmtInsercao->bindParam(':docto', $docto,  PDO::PARAM_INT);
            $stmtInsercao->bindParam(':cod_pro', $cod_pro, PDO::PARAM_INT);
            $stmtInsercao->bindParam(':quantidade', $quantidade, PDO::PARAM_STR);
            $stmtInsercao->bindParam(':valor_unit', $valor_unit, PDO::PARAM_STR);
            $stmtInsercao->bindParam(':valor_tot', $valor_tot, PDO::PARAM_STR);
            $stmtInsercao->bindParam(':cod_gar', $codgar, PDO::PARAM_INT);
            $stmtInsercao->bindParam(':hora', $hora);
            $stmtInsercao->bindParam(':observacao', $observacao);
            echo $sqlInsercao . '<br>';
            $stmtInsercao->execute();
            echo'<BR>';
        }
        unset($_SESSION['carrinho']);
        unset($_SESSION['mesa']);

        echo 'Inserção no banco de dados realizada com sucesso!';
        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        echo "Erro de conexão: " . $e->getMessage();
    } catch (Exception $e) {
    }
} else {
    echo 'Carrinho vazio. Nada a inserir.';
}