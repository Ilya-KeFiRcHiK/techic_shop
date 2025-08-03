<form action="reviews.php" method="POST">
    <label for="login">Логин:</label>
    <input type="text" id="login" name="login">
    <label for="password">Пароль:</label>
    <input type="password" id="password" name="password">
    <label for="comment">Коммент:</label>
    <input type="text" id="comment" name="comment">
    <button type="submit">Отправить</button>
</form>

<?php
require_once('link.php');

$query = "SELECT * 
FROM reviews";

$stmt = $db->prepare($query);
$stmt->execute();
$result = $stmt->get_result()->fetch_all();

var_dump($result);
?>
