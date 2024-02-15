<?php

/*
 insert into VENDABAR (CODIGO, DOCTO, DATA, HORA, FICHA, PESSOAS, COD_GAR, CAIXA, OBS)
values
((select MAX(codigo)+1 from VENDABAR), (select MAX(DOCTO)+1 from VENDABAR), CURRENT_DATE, Current_hour, [NUMERO_MESA_CARTAO], [PESSOAS], [ID_USUARIO], '', [OBS])
 */
$numeromesa = $_GET['mesa'];
$conn = new PDO('firebird:host=PC-Gui;dbname=D:\Astracon\Dados\ASTRABAR.FDB;charset=utf8', 'SYSDBA', 'masterkey');
$sqlficha = "SELECT ficha FROM vendabar WHERE FICHA = $numeromesa AND CAIXA='' AND (BLOQUEADA = '' OR BLOQUEADA IS NULL)";
$sqlbloqueada = "SELECT ficha FROM vendabar WHERE FICHA = $numeromesa AND CAIXA='' AND BLOQUEADA = 'S'";
$stmtficha = $conn->prepare($sqlficha);
$stmtbloqueada = $conn->prepare($sqlbloqueada);
$stmtficha->execute();
$stmtbloqueada->execute();
$mesabar = $stmtficha->fetchColumn();
$bloqueada = $stmtbloqueada->fetchColumn();
echo $mesabar;

if ($mesabar != null){
    header("location: garcon.php");
} else if($bloqueada != null) {
    echo '<link rel="stylesheet" href="criaficha.CSS">';
    echo "<header>";
    echo "<h1>Astra</h1>";
    echo '<h1>' . ' | Ficha: ' . $numeromesa . ' |</h1>';
    echo "</header>";
    echo "<h2>Essa ficha/Mesa está bloqueada, favor escolha outra ficha</h2>";
    echo "<a href='index.php'><button class='btnvolta'>Voltar para a inserção da ficha</button></a>";
} else echo "bunda";
