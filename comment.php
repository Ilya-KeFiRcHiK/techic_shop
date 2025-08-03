<?php
session_start();

if(isset($_SESSION['comments'])){
    foreach($_SESSION['comments'] as $comment){
        echo "<h1>{$comment['login']}</h1>";
        echo "<p>{$comment['password']}</p>";
    }
}
?>

<form action="addcomment.php" method="POST">
    <label for="login">Логин:</label>
    <input type="text" name="login" id="login">
    <label for="login">Клиент:</label>
    <button type="submit">Отправить</button>
</form>

