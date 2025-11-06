<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'borisov'; 

$conn = mysqli_connect($host, $user, $pass, $dbname);
?>

<header>
    <nav class="nav-links">
        <a href="index.php">Главная</a>
    </nav>
</header>

<div class="main-block">
    <h2>Регистрация</h2>

<?php
if (isset($_POST['reg']) && $_POST['reg'] == 'Зарегистрироваться') {
    
    if (!$conn) {
        die('<div class="message message-error">Ошибка подключения к БД: ' . mysqli_connect_error() . '</div>');
    }

    $login = mysqli_real_escape_string($conn, $_POST['login']);
    $email = mysqli_real_escape_string($conn, $_POST['email']); 
    $pass1 = mysqli_real_escape_string($conn, $_POST['pass1']); 
    $pass2 = mysqli_real_escape_string($conn, $_POST['pass2']);
    $surname = mysqli_real_escape_string($conn, $_POST['surname']); 
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    
    if (!empty($login) && !empty($email) && !empty($pass1) && !empty($pass2) && !empty($surname) && !empty($name)) {
        
        if ($pass1 == $pass2) {
            
            $query = "INSERT INTO reg (login, email, password, status, surname, name) 
                      VALUES ('$login', '$email', '$pass1', '0', '$surname', '$name')"; 
            
            $result = mysqli_query($conn, $query);

            if ($result) {
                echo '<div class="message message-success">Регистрация прошла успешно <a href="auto.php">Войдите</a></div>';
            } else {
                 echo '<div class="message message-error">Ошибка при добавлении в базу данных: ' . mysqli_error($conn) . '</div>';
            }
        
        } else {
            echo '<div class="message message-error">Пароли не совпадают, попробуйте ещё раз <a href="reg.php">Зарегистрироваться</a></div>';
        }
    } else {
        echo '<div class="message message-error">Заполните все поля формы <a href="reg.php">Зарегистрироваться</a></div>';
    }
}
?>

    <form action="reg.php" method="post">
        <label for="login">Логин</label>
        <input type="text" id="login" name="login" required>

        <label for="email">Почта</label>
        <input type="email" id="email" name="email" required>

        <label for="surname">Фамилия</label>
        <input type="text" id="surname" name="surname" required> 

        <label for="name">Имя</label>
        <input type="text" id="name" name="name" required>

        <label for="pass1">Пароль</label>
        <input type="password" id="pass1" name="pass1" required>

        <label for="pass2">Повторите пароль</label>
        <input type="password" id="pass2" name="pass2" required>

        <input type="submit" name="reg" value="Зарегистрироваться">
    </form>
</div>

<footer>
    <div class="contact-info">Контактная информация</div>
    <div class="partners">Партнеры</div>
</footer>

<?php
if ($conn) {
    mysqli_close($conn);
}
?>

</body>
</html>