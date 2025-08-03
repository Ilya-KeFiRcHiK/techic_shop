<?php
session_start();

if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

$_SESSION['cart'][] = [
    'name' => $_POST['name'],
    'category' => $_POST['category']
];
?>