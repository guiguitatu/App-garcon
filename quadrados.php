<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marcador de Bolinha</title>
    <style>

    </style>
</head>
<body>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div class="option-label">
        <input type="radio" id="opcao1" name="opcao" value="mesa" checked>
        <label for="opcao1">Mesa</label>
    </div>

    <div class="option-label">
        <input type="radio" id="opcao2" name="opcao" value="ficha">
        <label for="opcao2">Ficha</label>
    </div>

    <button type="submit">Enviar</button>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['opcao'])){
        $opcaoSelecionada = $_POST['opcao'];
        echo "Opção selecionada: " . $opcaoSelecionada;
    }
}
?>

</body>
</html>
