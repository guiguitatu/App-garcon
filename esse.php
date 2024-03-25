<?php
session_start();

function generateToken($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $token = '';
    $maxIndex = strlen($characters) - 1;

    for ($i = 0; $i < $length; $i++) {
        $randomIndex = mt_rand(0, $maxIndex);
        $token .= $characters[$randomIndex];
    }

    return $token;
}

if (!$_COOKIE['token']){
    setcookie('token', generateToken(10), time() + 60 * 60 * 24 * 7 * 4 * 12);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Garçon</title>
    <meta charset="UTF-8" name="viewport" content="user-scalable=no">
    <link rel="shortcut icon" href="imgs/logoastraconbranco.png" type="imagem">
    <link rel="stylesheet" href="CSS.CSS">

</head>
<body>

<header>
    <div style="position: relative; width: 100vw; z-index: 10; height: 100px; display: flex; justify-content: center; align-items: center">
        <div style="display: flex; flex-direction: row; align-items: center; height: 100%; justify-content: center">
            <h1>Astra</h1>
        </div>
        <div style="position: absolute; z-index: 5; right: 0; height: 70px">
        </div>
    </div>
</header>



<?php
if (isset($_SESSION['teste'])){
    echo '<h1>Porra</h1>';
}
unset($_SESSION['teste']);
echo '<h1>' . $_COOKIE['token'] . $_SESSION['teste'] .'</h1>' ?>



</body>
</html>
