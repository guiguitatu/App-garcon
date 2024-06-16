<?php

try{
    //troque essa linha de baixo
    $conn = new PDO('firebird:host=nomehost;dbname=caminhoarquivoFDBsistema";charset=utf8', 'SYSDBA', 'masterkey');
} catch (PDOException $e) {
    echo 'Não possível conectar, erro' . $e;
};        