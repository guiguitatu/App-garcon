<?php

session_start();
session_destroy();

setcookie('usuario[nome]', '', 0);

if (isset($_COOKIE['usuario'])){
    header("Location: login.php");
    exit();
} else echo 'porra';