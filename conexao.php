<?php

try{
    //troque essa linha de baixo
    $conn = new PDO('firebird:host=nomepc;dbname=arquivoFDBdosistema;charset=utf8', 'SYSDBA', 'masterkey');
} catch (PDOException $e) {
    echo 'Não possível conectar, erro' . $e;
};        