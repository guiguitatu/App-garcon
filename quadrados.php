<?php
try {
    $conn = new PDO('firebird:host=PC-Gui;dbname=D:\Astracon\Dados\ASTRABAR.FDB;charset=utf8', 'SYSDBA', 'masterkey');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = 'select * from GRUPOEST order by NOME';
    $stmt = $conn->query($sql);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

        if ($row['COD_GRUEST'] != 9) {
            echo '<button class="btndivs" onclick="escondediv(' . $row['COD_GRUEST'] . ')"> <img src="imgs/comer.png"style="height: 150px; width: 150px;"> <br>' . $row['NOME'] . '</button>';
        } else {
            echo '<button class="btndivs" onclick="escondediv(' . $row['COD_GRUEST'] . ')"><img src="imgs/comer.png" style="height: 150px; width: 150px;"> DESTILADOS </button>';
        }
    }
} catch (PDOException $e) {
    echo "Erro de conexão: " . $e->getMessage();
}
