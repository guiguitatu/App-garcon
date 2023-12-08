<?php
// Verifica se o número da mesa do cartão está presente no $_GET
if (isset($_GET['mesa'])) {
    $numeroMesaCartao = $_GET['mesa'];
    // Verifica se o formulário foi enviado e se o botão "inserir_sql" foi pressionado
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inserir_sql'])) {
        // Obtém o array de sessão
        $carrinho = isset($_SESSION['carrinho']) ? $_SESSION['carrinho'] : array();

        try {
            // Conectar ao banco de dados
            $conn = new PDO('firebird:host=PC-Gui;dbname=D:\Astracon\Dados\ASTRABAR.FDB;charset=utf8', 'SYSDBA', 'masterkey');
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Consulta SQL para obter os dados
            $sql = "SELECT produto.cod_proapp AS PRODUTO, produto.descricao AS NOME, produto.valor AS PRECO, PRODMOVBAR.quant AS QUANTIDADE, PRODMOVBAR.obs AS OBSERVACAO, PRODMOVBAR.codigo AS ID
                    FROM produto
                    INNER JOIN PRODMOVBAR ON produto.cod_pro = PRODMOVBAR.cod_pro
                    INNER JOIN VENDABAR ON VENDABAR.docto = PRODMOVBAR.docto
                    WHERE VENDABAR.ficha = :numeroMesaCartao AND VENDABAR.caixa = ''";

            // Prepara a consulta
            $stmt = $conn->prepare($sql);

            // Binda o parâmetro
            $stmt->bindParam(':numeroMesaCartao', $numeroMesaCartao);

            // Executa a consulta
            $stmt->execute();

            // Loop pelos resultados e insere na tabela PRODMOVBAR
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Código de inserção
                $cod_pro = "o_valor_cod_pro"; // Substitua pelo valor real
                $quantidade = $row['QUANTIDADE'];
                $valor_unit = $row['PRECO']; // Substitua pelo valor real
                $cod_gar = ""; // Substitua pelo valor real
                $dataMov = date("d/m/Y");
                $hora = date("H:i:s");
                $obs = "o_valor_obs"; // Substitua pelo valor real

                $insertSQL = "INSERT INTO PRODMOVBAR (CODIGO, DOCTO, COD_PRO, QUANT, VALOR_UNIT, VALOR_TOT, COD_GAR, DATAMOV, HORA, OBS)
                              VALUES ((select MAX(codigo) + 1 from PRODMOVBAR), (select VENDABAR.docto from VENDABAR where ficha = $numeroMesaCartao and (vendabar.caixa is null or vendabar.caixa = '') and (vendabar.SITUACAO is null or vendabar.SITUACAO = '')),
                                      :cod_pro, :quantidade, :valor_unit, :valor_tot, :cod_gar, :dataMov, :hora, :obs)";

                $stmtInsert = $conn->prepare($insertSQL);

                $stmtInsert->bindParam(':codigo', $codigo);
                $stmtInsert->bindParam(':docto', $docto);
                $stmtInsert->bindParam(':cod_pro', $cod_pro);
                $stmtInsert->bindParam(':quantidade', $quantidade);
                $stmtInsert->bindParam(':valor_unit', $valor_unit);
                $stmtInsert->bindParam(':valor_tot', $valor_tot);
                $stmtInsert->bindParam(':cod_gar', $cod_gar);
                $stmtInsert->bindParam(':dataMov', $dataMov);
                $stmtInsert->bindParam(':hora', $hora);
                $stmtInsert->bindParam(':obs', $obs);

                $stmtInsert->execute();
            }

            // Zerar o array de sessão
            unset($_SESSION['carrinho']);

            echo "Inserção realizada com sucesso!";
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    } else {
        echo "Formulário não enviado ou botão não pressionado.";
    }
} else {
    echo "Número da mesa do cartão não encontrado na URL.";
}
?>
