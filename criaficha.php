<?php
session_start();
include('trocanome.php');
include_once('conexao.php');
if (!isset($_SESSION['mesa'])){
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
$mesa = intval($_SESSION["mesa"]);
if ($_SERVER["REQUEST_METHOD"] == "POST" && $mesa != null){
    $nome = test_input($_POST["nome"]) ?? '';
    $numPessoas = intval(test_input($_POST["numPessoas"])) ?? 1;
    $observacao = test_input($_POST["observacao"])?? '';
    $numeromesa = intval($_SESSION["mesa"]);

    try {
        $sqldata = "select DATACAIXA from EMPRESA";
        $stmtdata = $conn->prepare($sqldata);
        $stmtdata->execute();
        $datasemform = $stmtdata->fetchColumn();
        $data = date('Y-m-d', strtotime($datasemform));
        $hora = date('H:i:s');
        if ($_SESSION['opcao'] == 'ficha') {
            $sqlinsert = "INSERT INTO VENDABAR (CODIGO, DOCTO, DATA, HORA, FICHA, NOME, PESSOAS, COD_GAR, CAIXA, OBS) VALUES
              ((SELECT MAX(codigo)+1 FROM VENDABAR), (SELECT MAX(DOCTO)+1 FROM VENDABAR), CAST('" . $data . "' AS DATE), '$hora', $numeromesa, '$nome', $numPessoas, $codgar, '', '$observacao')";
        } else {
            $sqlinsert = "INSERT INTO VENDABAR (CODIGO, DOCTO, DATA, HORA, MESA, NOME, PESSOAS, COD_GAR, CAIXA, OBS) VALUES
              ((SELECT MAX(codigo)+1 FROM VENDABAR), (SELECT MAX(DOCTO)+1 FROM VENDABAR), CAST('" . $data . "' AS DATE), '$hora', $numeromesa, '$nome', $numPessoas, $codgar, '', '$observacao')";
        }
        $stmtinsert = $conn->prepare($sqlinsert);
        $stmtinsert->execute();
        $conn = null;

        header("location: garcon.php");
    } catch (PDOException $e) {
        echo '<link rel="stylesheet" href="criaficha.CSS">';
        echo "<header>";
        echo "<h1>Bacias</h1>";
        echo "</header>";
        echo "<h1> <?php var_dump($nome);?></h1><br>";
        echo "<h1> <?php var_dump($numPessoas);?></h1><br>";
        echo "<h1> <?php var_dump($observacao);?></h1><br>";
        echo "<h1> <?php var_dump($numeromesa);?></h1><br>";

        echo "<h2> Erro: ". $e->getMessage() . "</h2>";
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
    $numeromesa = $_SESSION['mesa'];
    if ($numeromesa == null || $numeromesa == ''){
        header('location: index.php');
    }
   
    if ($_SESSION['opcao'] == 'ficha') {
        $sqlficha = "SELECT ficha FROM vendabar WHERE FICHA = $numeromesa AND (CAIXA='' or CAIXA is NULL) AND (SITUACAO='' or SITUACAO is NULL) AND (BLOQUEADA = '' OR BLOQUEADA IS NULL)";
        $sqlbloqueada = "SELECT ficha FROM vendabar WHERE FICHA = $numeromesa AND (CAIXA='' or CAIXA is NULL) AND (SITUACAO='' or SITUACAO is NULL) AND BLOQUEADA = 'S'";
        $sqlbloqueadatab = "SELECT ficha, OBS from ficha_bloq where ficha = $numeromesa";
    } else {
        $sqlficha = "SELECT MESA FROM vendabar WHERE MESA = $numeromesa AND (CAIXA='' or CAIXA is NULL) AND (SITUACAO='' or SITUACAO is NULL) AND (BLOQUEADA = '' OR BLOQUEADA IS NULL)";
        $sqlbloqueada = "SELECT MESA FROM vendabar WHERE MESA = $numeromesa AND (CAIXA='' or CAIXA is NULL) AND (SITUACAO='' or SITUACAO is NULL) AND BLOQUEADA = 'S'";
    }
    $stmtficha = $conn->prepare($sqlficha);
    $stmtbloqueada = $conn->prepare($sqlbloqueada);
    $stmtbloqueadatab = $conn->prepare($sqlbloqueadatab);
    $stmtficha->execute();
    $stmtbloqueada->execute();
    $stmtbloqueadatab->execute();
    $mesabar = $stmtficha->fetchColumn();
    $bloqueada = $stmtbloqueada->fetchColumn();
    $bloqueadatab = $stmtbloqueadatab->fetch(PDO::FETCH_ASSOC);
    $obs = $bloqueadatab['OBS'];
    $ficha = $bloqueadatab['FICHA'];
    $conn = null;
    if ($mesabar != null){
        header("location: garcon.php");
    } else if($bloqueada != null && $ficha == null) {
        echo '<link rel="shortcut icon" href="imgs/logoastraconbranco.png" type="imagem">';
        echo '<link rel="stylesheet" href="criaficha.CSS">';
        echo "<header>";
        echo "<h1>Astra </h1>";
        if ($_SESSION['opcao'] == 'ficha') {
            echo '<h1>' . ' | Ficha: ' . $numeromesa . ' | </h1>';
            echo "</header>";
            echo "<h2 style='text-align: center'>Ficha bloqueada para lançamento <br> Aguardando finalização/pagamento.</h2>";
        } else if ($_SESSION['opcao'] == 'mesa') {
            echo '<h1>' . ' | Mesa: ' . $numeromesa . ' | </h1>';
            echo "</header>";
            echo "<h2>Essa Mesa está bloqueada, favor escolha outra mesa</h2>";
        }
        echo "<a href='index.php'><button class='btnvolta'>Voltar para a inserção da ficha</button></a>";
    } else if ($bloqueadatab != null) {
        echo '<link rel="shortcut icon" href="imgs/logoastraconbranco.png" type="imagem">';
        echo '<link rel="stylesheet" href="criaficha.CSS">';
        echo "<header>";
        echo "<h1>Astra </h1>";
        echo '<h1>' . ' | Ficha: ' . $numeromesa . ' | </h1>';
        echo "</header>";
        echo "<h2  style='text-align: center'>ATENÇÃO! <br><br> Ficha não autorizada, bloqueada para uso.</h2>";
        echo "<h2>OBS: " . $obs . "</h2><br>";
        echo "<a href='index.php'><button class='btnvolta'>Voltar para a inserção da ficha</button></a>";
    } else {
        echo '<link rel="stylesheet" href="criaficha.CSS">';
        echo '<script src="criaficha.js"></script>';
        echo "<header>";
        echo "<h1>Astra</h1>";
        if ($_SESSION['opcao'] == 'ficha'){
            echo '<h1>' . ' | Ficha: ' . $numeromesa . ' |</h1>';
        } else if ($_SESSION['opcao'] == 'mesa'){
            echo '<h1>' . ' | Mesa: ' . $numeromesa . ' |</h1>';
        }
        echo "</header>";
        echo '
    <div class="formu"  id="formficha">
    <form method="post" style="display: flex;flex-direction: column;align-items: center; width: 95%" action="criaficha.php">
        <span>
        <label style="font-size: 50px">Criação da ficha: </label> <br>
        <label for="nome" style="font-size: 40px">Nome:</label>
        <input type="text" class="inputtxt" id="nome" name="nome" placeholder="Nome do cliente" maxlength="19"><br><br>
        </span>
        <input type="hidden" id="mesa" name="mesa" value="'. $numeromesa . '">
        <span>
        <label for="numPessoas" style="font-size: 40px">Nº de Pessoas:</label>
        <input type="text" id="numPessoas" name="numPessoas" pattern="\d+" title="Digite somente números" placeholder="Número de pessoas" maxlength="2"><br><br>
        </span>
        
        <span>
        <label for="observacao" style="font-size: 40px">Observação:</label><br>
        <textarea id="observacao" class="inputobscria" name="observacao" rows="4" cols="100" placeholder="Observação para a ficha" maxlength="50"></textarea><br><br>
        </span>
        
        <input type="submit" value="Criar ficha" class="btnenvia">
        <a href="index.php" style="text-decoration: none; width: 100vw; margin-top: 20px; display: flex; justify-content: center;"><input type="button" value="Voltar" class="btnenvia"></a>
    </form></div>';
    }
} catch (PDOException $e) {
    echo '<link rel="stylesheet" href="criaficha.CSS">';
    echo "<header>";
    echo "<h1>Astra</h1>";
    echo "</header>";

    echo "<h2> Erro 400 </h2>
            <p>Erro em criação de ficha.</p>
";
}
