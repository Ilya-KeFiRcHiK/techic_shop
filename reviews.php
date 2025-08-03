<?php
error_reporting(E_ALL);

require_once('link.php');

$review = $_POST['comment'];
$password = $_POST['password'];
$login = $_POST['login'];

$query = "INSERT INTO `reviews` (`name`, `review`) VALUES (?, ?)";

$stmt = $db->prepare($query);

$stmt->bind_param('ss', $login, $review);

$result = $stmt->execute();

header('Location: login.php');
exit();
?>