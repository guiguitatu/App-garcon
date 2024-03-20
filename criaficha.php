<?php
session_start();
if ($_GET['mesa'] == null or $_GET['mesa'] == ''){
    header('location: index.php');
}
if ($_COOKIE['usuario']) {
    $cod = $_COOKIE['usuario']['codido'];
    $gar = $_COOKIE['usuario']['nome'];
    $gararray = array(
        'codido' => $cod,
        'garcon' => $gar
    );
    $_SESSION['usuario'] = $gararray;
    $codgar = $_SESSION['usuario']['codido'];
    $garcon = $_SESSION['usuario']['garcon'];
} else {
    header("Location: login.php");
}
$mesa = intval($_POST["mesa"]);
if ($_SERVER["REQUEST_METHOD"] == "POST" && $mesa != null){
    $nome = test_input($_POST["nome"]) ?? '';
    $numPessoas = intval(test_input($_POST["numPessoas"])) ?? 1;
    $observacao = test_input($_POST["observacao"])?? '';
    $numeromesa = intval($_POST["mesa"]);
    var_dump($nome);
    var_dump($numPessoas);
    var_dump($observacao);
    var_dump($numeromesa);

    try {
        $conn = new PDO('firebird:host=PC-Gui;dbname=D:/Astracon/Dados/ASTRABAR.FDB;charset=utf8', 'SYSDBA', 'masterkey');
        $sqldata = "select DATACAIXA from EMPRESA";
        $stmtdata = $conn->prepare($sqldata);
        $stmtdata->execute();
        $datasemform = $stmtdata->fetchColumn();
        $data = date('Y-m-d', strtotime($datasemform));
        $hora = date('H:i:s');
        $sqlinsert = "INSERT INTO VENDABAR (CODIGO, DOCTO, DATA, HORA, FICHA, NOME, PESSOAS, COD_GAR, CAIXA, OBS) VALUES
              ((SELECT MAX(codigo)+1 FROM VENDABAR), (SELECT MAX(DOCTO)+1 FROM VENDABAR), CAST('" . $data . "' AS DATE), '$hora', $numeromesa, '$nome', $numPessoas, $codgar, '', '$observacao')";
        $stmtinsert = $conn->prepare($sqlinsert);
        $stmtinsert->execute();
        if ($_GET['mesa'] != null){
            $numeromesa = intval($_GET["mesa"]);
        } else $numeromesa = intval($_POST['mesa']);
        header("location: garcon.php?mesa=$numeromesa");
    } catch (PDOException $e) {
        echo '<link rel="stylesheet" href="criaficha.CSS">';
        echo "<header>";
        echo "<h1>Bacias</h1>";
        echo "</header>";
        echo "<h1> <?php var_dump($nome);?></h1><br>";
        echo "<h1> <?php var_dump($numPessoas);?></h1><br>";
        echo "<h1> <?php var_dump($observacao);?></h1><br>";
        echo "<h1> <?php var_dump($numeromesa);?></h1><br>";

        echo "<h2> Erro na barbaridade: ". $e->getMessage() . "</h2>";
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
/*
 insert into VENDABAR (CODIGO, DOCTO, DATA, HORA, FICHA, NOME, PESSOAS, COD_GAR, CAIXA, OBS)
values
((select MAX(codigo)+1 from VENDABAR), (select MAX(DOCTO)+1 from VENDABAR), CURRENT_DATE, Current_hour, [NUMERO_MESA_CARTAO], [NOME], [PESSOAS], [ID_USUARIO], '', [OBS])
 */
try {
    $numeromesa = $_GET['mesa'];
    $conn = new PDO('firebird:host=PC-Gui;dbname=D:/Astracon/Dados/ASTRABAR.FDB;charset=utf8', 'SYSDBA', 'masterkey');
    $sqlficha = "SELECT ficha FROM vendabar WHERE FICHA = $numeromesa AND (CAIXA='' or CAIXA is NULL) AND (SITUACAO='' or SITUACAO is NULL) AND (BLOQUEADA = '' OR BLOQUEADA IS NULL)";
    $sqlbloqueada = "SELECT ficha FROM vendabar WHERE FICHA = $numeromesa AND (CAIXA='' or CAIXA is NULL) AND (SITUACAO='' or SITUACAO is NULL) AND BLOQUEADA = 'S'";
    $stmtficha = $conn->prepare($sqlficha);
    $stmtbloqueada = $conn->prepare($sqlbloqueada);
    $stmtficha->execute();
    $stmtbloqueada->execute();
    $mesabar = $stmtficha->fetchColumn();
    $bloqueada = $stmtbloqueada->fetchColumn();
    echo $mesabar;

    if ($mesabar != null){
        header("location: garcon.php?mesa=$numeromesa");
    } else if($bloqueada != null) {
        echo '<link rel="shortcut icon" href="imgs/logoastraconbranco.png" type="imagem">';
        echo '<link rel="stylesheet" href="criaficha.CSS">';
        echo "<header>";
        echo "<h1>Astra</h1>";
        echo '<h1>' . ' | Ficha: ' . $numeromesa . ' |</h1>';
        echo "</header>";
        echo "<h2>Essa ficha/Mesa está bloqueada, favor escolha outra ficha</h2>";
        echo "<a href='index.php'><button class='btnvolta'>Voltar para a inserção da ficha</button></a>";
    } else {
        echo '<link rel="stylesheet" href="criaficha.CSS">';
        echo '<script src="criaficha.js"></script>';
        echo "<header>";
        echo "<h1>Astra</h1>";
        echo '<h1>' . ' | Ficha: ' . $numeromesa . ' |</h1>';
        echo "</header>";
        echo '
    <article id="btns">
    <h2>Mesa não aberta ainda, abrir ficha/mesa?.</h2>
    <div class="btnescolha">
    <button class="sim" onclick="mudapagina(true)">Sim</button>
    <button class="nao" onclick="mudapagina(false)">Não</button>
    </div>
    </article>
    <div class="formu"  id="formficha" style="display: none">
    <form method="post" style="display: flex;flex-direction: column;align-items: center; width: 95%" action="' . $_SERVER['PHP_SELF'] . '">
        <span>
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" placeholder="Nome do cliente"><br><br>
        </span>
        <input type="hidden" id="mesa" name="mesa" value="'. $numeromesa . '">
        <span>
        <label for="numPessoas">Nº de Pessoas:</label>
        <input type="text" id="numPessoas" name="numPessoas" pattern="\d+" title="Digite somente números" placeholder="Número de pessoas"><br><br>
        </span>
        
        <span>
        <label for="observacao">Observação:</label><br>
        <textarea id="observacao" name="observacao" rows="4" cols="100" placeholder="Observação para a ficha"></textarea><br><br>
        </span>
        
        <input type="submit" value="Enviar" class="btnenvia">
    </form></div>';
    }
} catch (PDOException $e) {
    echo '<link rel="stylesheet" href="criaficha.CSS">';
    echo "<header>";
    echo "<h1>Astra</h1>";
    echo "</header>";

    echo "<h2> Erro na Consulta: ". $e->getMessage() . "</h2>";
}
