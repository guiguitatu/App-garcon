<?php
header('Content-Type: application/json'); // Garante que o tipo de conteúdo seja JSON
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
        $sql = "SELECT COD_PROAPP, DESCRICAO, COD_GRUEST, VALOR, COD_PRO FROM produto WHERE cod_pro LIKE :q AND COD_GRUEST is not null AND VALOR is not null AND COD_PRO is not null AND descricao is not null";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['q' => '%' . $q . '%']);
    } else {
        $sql = "SELECT COD_PROAPP, DESCRICAO, COD_GRUEST, VALOR, COD_PRO FROM produto WHERE (descricao LIKE :q OR descricao LIKE :upper OR descricao LIKE :lower) AND COD_GRUEST is not null AND VALOR is not null AND COD_PRO is not null AND descricao is not null";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['q' => '%' . $q . '%', 'upper' => '%' . $upper . '%', 'lower' => '%' . $lower . '%']);
    }

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($results) {
        echo json_encode($results);
    } else {
        echo json_encode(['error' => 'No results found.']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
