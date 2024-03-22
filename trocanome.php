<?php

$arquivos = array('login.php', 'garcon.php', 'criaficha.php', 'insercao.php');

$txtantigo = 'firebird:host=host;dbname=caminhoarquivo;charset=utf8';
$txtnovo = 'firebird:host=hostnoserv;dbname=caminhoparaoarquivo.fdbnoserv;charset=utf8';

foreach ($arquivos as $arquivo) {
    $cont = file_get_contents($arquivo);

    $newcont = str_replace($txtantigo, $txtnovo, $cont);

    file_put_contents($arquivo, $newcont);
};