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
$dbname = 'datab'; 

$conn = mysqli_connect($host, $user, $pass, $dbname);
?>

<header>
    <nav class="nav-links">
        <a href="index.php">Главная</a>
        <a href="auto.php">Авторизация</a>
        <a href="reg.php">Регистрация</a>
        <?php
        if (isset($_SESSION['login']) && !empty($_SESSION['login'])) {
            echo '<a href="profile.php">Профиль</a>';
            echo '<a href="create_request.php">Создание заявки</a>';
            echo '<a href="view_requests.php">Просмотр заявок</a>';
            if ($_SESSION['status'] == 1) {
                echo '<a href="admin.php">Панель администратора</a>';
            }
        }
        ?>
    </nav>
</header>

<div class="main-block">
    <h2>Регистрация</h2>

<?php
if (isset($_POST['reg']) && $_POST['reg'] == 'Зарегистрироваться') {
    
    if (!$conn) {
        die('<div class="message message-error">Ошибка подключения к БД: ' . mysqli_connect_error() . '</div>');
    }

    $login = $_POST['login']; 
    $email = $_POST['email']; 
    $pass1 = $_POST['pass1']; 
    $pass2 = $_POST['pass2'];
    $surname = $_POST['surname']; 
    $name = $_POST['name'];
    
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

<?php
if ($conn) {
    mysqli_close($conn);
}
?>


<footer>
    <p>© <?php echo date('Y'); ?> Мой сайт заявок</p>
</footer>

</body>
</html>