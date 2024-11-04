<?php

try{
    //troque essa linha de baixo
    $conn = new PDO('firebird:host=ASTRACON1;dbname=C:\Astracon\DadosClientes\ASTRACONNFCEBarUsina.FDB;charset=utf8', 'SYSDBA', 'masterkey');
} catch (PDOException $e) {
    echo ' 
     <link rel="stylesheet" href="css.CSS">
        <header>
        <h1> Astra </h1>
        
     </header>
     <h1> Erro 402 </h1>
     <p style="text-align: center">Erro com a conexão com o banco de dados no servidor. <br>
     Verifique a conexão.</p>   
     ';
};        