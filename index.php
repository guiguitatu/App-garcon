<?php
session_start();
include('trocanome.php');
unset($_SESSION['']);
if ($_COOKIE['usuario']) {
    if ($_COOKIE['usuario']['nome'] == null or $_COOKIE['usuario']['nome'] == '' ){
        header("Location: login.php");
    }
    $codgarcon = $_COOKIE['usuario']['codido'];
    $garcon = $_COOKIE['usuario']['nome'];
} else {
    header("Location: login.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['opcao'])){
        if($_POST['opcao'] == 'mesa'){
            $_SESSION['opcao'] = 'mesa';
        } else {
            $_SESSION['opcao'] = 'ficha';
        }
        $_SESSION['mesa'] = $_POST['mesa'];
        header('location: criaficha.php');
        exit();
    } else {
        $_SESSION['erro'][] = "Opção de ficha/mesa não selecionada";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="imgs/logoastraconbranco.png" type="imagem">
    <link rel="stylesheet" href="./index.css">

    <title>Index</title>
</head>
<?php
try {
        $conn = new PDO('firebird:host=PC-GUI;dbname=D:/Astracon/DadosClientes/ASTRACONNFCEZEZITOS.fdb;charset=utf8', 'SYSDBA', 'masterkey');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "select TIPOABERT from empresa where TIPOABERT like '0%' or TIPOABERT like '1%'  or TIPOABERT like '2%'  or TIPOABERT like '3%'  or TIPOABERT like '4%'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $opcao = $stmt->fetchColumn();
        echo '<body>';

} catch (PDOException $e){
    echo '<body style="justify-content: center">
            <div style="display: flex; flex-direction: column; width: 80vw; align-items: center; justify-content: center">
            <p><b>Não foi possível fazer a conexão com o sistema, favor clicar no botão abaixo para atualizar a página e tentar novamente.</b></p><br><br>
               <a href="index.php"><button style="width: 50vw; height: 100px; font-size: 30px">Recarregar a página</button></a>
          </div>';
    die();
}
?>

<header>
    <div style="position: absolute; z-index: 5; right: 15px; height: 95%; align-items: center; display: flex; justify-content: center">
        <a href="config.php"><button class="btnconfig"><img src="./imgs/config.png" style="height: 80%"/> </button></a>
    </div>
</header>
<br><br>
<div class="main">
    <?php
    echo ' <div style="display: flex; flex-direction: row; align-items: center; margin-bottom: 40px">
               <h7 style="display: flex; justify-content: center;"> <b> Garçom: ' . $garcon . '</b></h7>
               <a href="sairusuario.php" style="text-decoration: none;"> <button class="btnlimpa" style="background-color: #ec5858">Sair</button></a>
           </div>
         ';
    if (isset($_SESSION['erro'])){
         echo '<div class="erro2">';
         foreach ($_SESSION['erro'] as $erro){
            echo '<p>'. $erro . '</p>';
         }
        echo '</div><br>';
    }
    unset($_SESSION['erro']);

    echo '<form action=' . htmlspecialchars($_SERVER['PHP_SELF']) .' method="post" class="form">';
        if ($opcao == '0-Todos'){
            echo '
            <div style="display: flex; align-items: center; justify-content: space-evenly; margin: 0 0 40px;"> 
                <div style="display: flex; align-items: center">
                    <input type="radio" id="mesa" name="opcao" value="mesa" onchange="mudanome()" style="width: 25px; height: 25px;">
                    <label for="opcao1">Mesa</label>
                </div>
    
                <div style="display: flex; align-items: center">
                    <input type="radio" id="ficha" name="opcao" value="ficha" onchange="mudanome()"style="width: 25px; height: 25px;">
                    <label for="opcao2">Ficha</label>
                </div>
            </div>    
            <label for="mesa" id="txtinput" style="display: flex; justify-content: center">Digite o numero:</label>';
        } else if ($opcao == '1-Mesa'){
        echo '
            <div style="display: flex; flex-direction: row; align-items: center; justify-content: space-evenly; margin: 0 0 40px;"> 
                <div style="display: flex; align-items: center">
                    <input type="radio" id="mesa" name="opcao" value="mesa" onchange="mudanome()" checked>
                    <label for="opcao1">Mesa</label>
                </div>
    
                <div style="display: flex; align-items: center">
                    <input type="radio" id="ficha" name="opcao" value="ficha" onchange="mudanome()" style="width: 25px; height: 25px;">
                    <label for="opcao2">Ficha</label>
                </div>
            </div>    
            <label for="mesa" id="txtinput" style="display: flex; justify-content: center">Digite o numero da mesa:</label>';
    } else if ($opcao == '2-Ficha'){
        echo '
            <div style="display: flex; flex-direction: row; align-items: center; justify-content: space-evenly; margin: 0 0 40px;"> 
                <div style="display: flex; align-items: center">
                    <input type="radio" id="mesa" name="opcao" value="mesa" onchange="mudanome()" style="width: 25px; height: 25px;"">
                    <label for="opcao1">Mesa</label>
                </div>
    
                <div style="display: flex; align-items: center">
                    <input type="radio" id="ficha" name="opcao" value="ficha" onchange="mudanome()" style="width: 25px; height: 25px;" checked>
                    <label for="opcao2">Ficha</label>
                </div>
            </div>    
            <label for="mesa" id="txtinput" style="display: flex; justify-content: center">Digite o numero da ficha:</label>';
    } else if ($opcao == '3-Somente'){
        echo '
            <div style="display: none; flex-direction: row; align-items: center; justify-content: space-evenly; margin: 0 0 40px;"> 
                <div style="display: flex; align-items: center">
                    <input type="radio" id="mesa" name="opcao" value="mesa" onchange="mudanome()" checked style="width: 25px; height: 25px;">
                    <label for="opcao1">Mesa</label>
                </div>
            </div>        
            <label for="mesa" id="txtinput" style="display: flex; justify-content: center">Digite o numero da mesa:</label>';
    } else if ($opcao == '4-Somente'){
        echo '
            <div style="display: none; flex-direction: row; align-items: center; justify-content: space-evenly; margin: 0 0 40px;"> 
                <div style="display: flex; align-items: center">
                    <input type="radio" id="mesa" name="opcao" value="ficha" onchange="mudanome()" checked style="width: 25px; height: 25px;">
                    <label for="opcao1">Ficha</label>
                </div>
            </div>        
            <label for="mesa" id="txtinput" style="display: flex; justify-content: center">Digite o numero da ficha:</label>';
    } else
    ?>
        <div class="btns">
            <input type="text" id="nunmesa" name="mesa" pattern="[0-9]+" title="Número da mesa" readonly class="input">
            <button type="button" class="btnlimpa" onclick="limpa()">Limpar</button>
        </div>

        <div class="btns">
            <button type="button" class="btnnums" onclick="mudanum(1)">1</button>
            <button type="button" class="btnnums" onclick="mudanum(2)">2</button>
            <button type="button" class="btnnums" onclick="mudanum(3)">3</button>
        </div>
        <div class="btns">
            <button type="button" class="btnnums" onclick="mudanum(4)">4</button>
            <button type="button" class="btnnums" onclick="mudanum(5)">5</button>
            <button type="button" class="btnnums" onclick="mudanum(6)">6</button>
        </div>
        <div class="btns">
            <button type="button" class="btnnums" onclick="mudanum(7)">7</button>
            <button type="button" class="btnnums" onclick="mudanum(8)">8</button>
            <button type="button" class="btnnums" onclick="mudanum(9)">9</button>
        </div>
        <div class="btns">
            <button type="button" class="zero" onclick="mudanum(0)">0</button>
            <button type="button" class="btnnums" onclick="backspace()">⌫</button>
        </div>
        <button type="submit" style="margin-top: 5px">Enviar</button>
    </form>
</div>
<script>

    function limpa(){
     let inp = document.getElementById('nunmesa');
     inp.value = ''
    }

    function mudanum(numero){
        let inp = document.getElementById('nunmesa');
        if (inp.value.length < 4){
            inp.value = inp.value + numero;
        }
    }

    function backspace(){
        let inp = document.getElementById('nunmesa');
        inp.value = inp.value.slice(0, -1);
    }

    function mudanome() {
        let mesa = document.getElementById("mesa");
        let ficha = document.getElementById("ficha");
        let label = document.getElementById("txtinput");

        if (mesa.checked) {
            label.textContent = "Digite o número da mesa:";
        } else if (ficha.checked) {
            label.textContent = "Digite o número da ficha:";
        }
    }
</script>
</body>
</html>