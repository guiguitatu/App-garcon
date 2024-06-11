<?php

session_start();

setcookie('usuario[nome]', '', 0);

if (isset($_COOKIE['usuario'])){
    header("Location: login.php");
    exit();
} else header("Location: login.php");
