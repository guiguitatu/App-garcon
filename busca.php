<?php
header('Content-Type: application/json');
include_once("conexao.php");

$q = $_GET['q'] ?? '';
if (empty($q)) {
    echo json_encode([]);
    exit();
}

try {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifique se a entrada é numérica
    $isNumeric = is_numeric($q);

    $upper = strtoupper($q);
    $lower = strtolower($q);

    if ($isNumeric) {
        $sql = "SELECT produto.COD_PROAPP, produto.DESCRICAO, produto.COD_GRUEST, produto.VALOR, produto.COD_PRO, grupoest.nome
         FROM produto LEFT JOIN grupoest ON produto.COD_GRUEST = grupoest.COD_GRUEST
         WHERE produto.COD_PRO LIKE :q 
         AND produto.COD_GRUEST is not null 
         AND produto.VALOR is not null 
         AND produto.COD_PRO is not null 
         AND produto.descricao is not null";
    } else {
        $sql = "SELECT produto.COD_PROAPP, produto.DESCRICAO, produto.COD_GRUEST, produto.VALOR, produto.COD_PRO, grupoest.nome
         FROM produto LEFT JOIN grupoest ON produto.COD_GRUEST = grupoest.COD_GRUEST 
         WHERE LOWER(produto.descricao) LIKE LOWER(:q)
         AND produto.COD_GRUEST is not null 
         AND produto.VALOR is not null 
         AND produto.COD_PRO is not null 
         AND produto.descricao is not null";
    }
    $stmt = $conn->prepare($sql);
    $stmt->execute(['q' => '%' . $q . '%']);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($results) {
        echo json_encode($results);
    } else {
        echo json_encode(['error' => 'No results found.']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
