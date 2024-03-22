<?php
session_start();
unset($_SESSION['opcao']);
unset($_SESSION['carrinho']);
header('location: index.php');
