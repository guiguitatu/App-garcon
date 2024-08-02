<?php

include_once("conexao.php");

$mesa = $_SESSION['mesa'];

$numeromesa = intval($mesa);

$sqlbloqueada = "SELECT ficha FROM vendabar WHERE FICHA = $numeromesa AND (CAIXA='' or CAIXA is NULL) AND (SITUACAO='' or SITUACAO is NULL) AND BLOQUEADA = 'S'";
$sqlbloqueadatab = "SELECT ficha, OBS from ficha_bloq where ficha = $numeromesa";
$stmtbloqueada = $conn->prepare($sqlbloqueada);
$stmtbloqueadatab = $conn->prepare($sqlbloqueadatab);
$stmtbloqueada->execute();
$stmtbloqueadatab->execute();
$bloqueada = $stmtbloqueada->fetchColumn();
$bloqueadatab = $stmtbloqueadatab->fetch(PDO::FETCH_ASSOC);
$obs = $bloqueadatab['OBS'];
$ficha = $bloqueadatab['FICHA'];