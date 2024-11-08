<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
ob_start();
include_once('conexao.php');
if ($_COOKIE['usuario']) {
    if ($_COOKIE['usuario']['nome'] == null or $_COOKIE['usuario']['nome'] == '' ){
        header("Location: login.php");
    }
    $codgarcon = $_COOKIE['usuario']['codido'];
    $garcon = $_COOKIE['usuario']['nome'];
} else {
    header("Location: login.php");
}

if (!empty($_SESSION['carrinho'])) {

    try {
        $mesa = $_GET['mesa'];
        set_time_limit(300);
       
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
            if ($_SESSION['opcao'] == 'mesa'){
                $sqlDocto = "select VENDABAR.docto as ID from VENDABAR  where mesa =" . $mesa . " and (caixa = '' or caixa is null) and (VENDABAR.situacao <> 'C' OR VENDABAR.situacao is null)";
            } else {
                $sqlDocto = "select VENDABAR.docto as ID from VENDABAR  where ficha =" . $mesa . " and (caixa = '' or caixa is null) and (VENDABAR.situacao <> 'C' OR VENDABAR.situacao is null)";
            }


            $sqldata = "select DATACAIXA from EMPRESA";
            $stmtCodPro = $conn->prepare($sqlCodPro);
            $stmtValor = $conn->prepare($sqlValor);
            $stmtDocto = $conn->prepare($sqlDocto);
            $stmtdata = $conn->prepare($sqldata);

            try {
                $stmtCodPro->execute();
            } catch (PDOException $e) {
                echo "Erro ao executar stmtCodPro: " . $e->getMessage();
                exit;
            }

            try {
                $stmtValor->execute();
            } catch (PDOException $e) {
                echo "Erro ao executar stmtValor: " . $e->getMessage();
                exit;
            }

            try {
                $stmtCodPro->execute();
                $stmtValor->execute();
                $stmtDocto->execute();
                $stmtdata->execute();
            } catch (PDOException $e) {
                echo "Erro de consulta pra inserção: " . $e->getMessage();
            }
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
            try {
                $stmtInsercao->execute();
            } catch (PDOException $e) {
                echo "Erro de inserção: " . $e->getMessage();
            }
            echo'<BR>';
        }
        unset($_SESSION['carrinho']);
        unset($_SESSION['mesa']);
        $conn = null;
        echo 'Inserção no banco de dados realizada com sucesso!';
        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        echo "Erro de e: " . $sqlInsercao . $e->getMessage();
    }
} else {
    echo 'Carrinho vazio. Nada a inserir.';
}
ob_end_flush();