<?php
session_start();

if(!isset($_SESSION['comments'])){
    $_SESSION['comments'] = [];
}

$_SESSION['comments'][] = [
    'login' => $_POST['login'],
    'text' => $_POST['password']
];
header('Location: comment.php');
exit();

?>