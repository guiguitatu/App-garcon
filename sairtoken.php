<?php
session_start();
function generateToken($length): string
{
    $characters = '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ';
    $token = '';
    $maxIndex = strlen($characters) - 1;

    for ($i = 0; $i < $length; $i++) {
        $randomIndex = mt_rand(0, $maxIndex);
        $token .= $characters[$randomIndex];
    }

    return $token;
}

setcookie('token', '', 0);

setcookie('token', generateToken(10), time() + 60 * 60 * 24 * 7 * 4 * 12);

header('Location: token.php');