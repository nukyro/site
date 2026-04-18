<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'datab';

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Ошибка подключения к базе данных: " . mysqli_connect_error());
}
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
    <h2>Профиль пользователя</h2>

    <?php

    if (isset($_POST['update_user']) && $_POST['update_user'] == 'Изменить контакты') {
        
        $id = $_POST['id'];
        $login2 = $_POST['login2'];
        $mail = $_POST['mail'];
        $pass2 = $_POST['pass2'];
        $name = $_POST['name'];
        $surname = $_POST['surname'];

        $query = "UPDATE reg SET login='$login2', email='$mail', password='$pass2', name='$name', surname='$surname' WHERE id='$id'";

        $result = mysqli_query($conn, $query);

        if ($result) {
            $_SESSION['login'] = $login2; 
            $_SESSION['pass1'] = $pass2; 
            echo '<div class="message message-success">Данные успешно изменены!</div>';
        } else {
            echo '<div class="message message-error">Ошибка при изменении данных: ' . mysqli_error($conn) . '</div>';
        }
    }

    if (isset($_SESSION['login'])) {
        $login = $_SESSION['login'];
        $query = "SELECT * FROM reg WHERE login='$login'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_array($result);
            
    ?>
        <div class="profile-details">
            <form action="profile.php" method="post">
                <label for="login2">Логин:</label>
                <input type="text" id="login2" name="login2" value="<?php echo $row[1]; ?>">

                <label for="mail">Почта:</label>
                <input type="email" id="mail" name="mail" value="<?php echo $row[2]; ?>">

                <label for="pass2">Пароль:</label>
                <input type="password" id="pass2" name="pass2" value="<?php echo $row[5]; ?>">

                <label for="name">Имя:</label>
                <input type="text" id="name" name="name" value="<?php echo $row[4]; ?>">

                <label for="surname">Фамилия:</label>
                <input type="text" id="surname" name="surname" value="<?php echo $row[3]; ?>">
                
                <input type="hidden" name="id" value="<?php echo $row[0]; ?>">
                <input type="submit" name="update_user" value="Изменить контакты">
            </form>
        </div>

    <?php
        } else {
            echo '<div class="message message-error">Данные пользователя не найдены. <a href="auto.php">Авторизуйтесь</a>.</div>';
        }
    } else {
        echo '<div class="message message-error">Вы не авторизованы. <a href="auto.php">Войдите</a>, чтобы просмотреть профиль.</div>';
    }
    ?>
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