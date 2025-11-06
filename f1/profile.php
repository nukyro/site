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
ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_NOTICE);

if (isset($_POST['out']) && $_POST['out'] == 'Выход') {
    $_SESSION['login'] = '';
    $_SESSION['pass1'] = '';
    $_SESSION['status'] = '';
    
    echo "<script>alert('Вы не вошли в аккаунт');</script>";
    echo "<script>location.href='index.php';</script>";
    exit;
}

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'borisov';

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Ошибка подключения к базе данных: " . mysqli_connect_error());
}
?>

<header>
    <nav class="nav-links">
        <a href="index.php">Главная</a>
        <a href="catalog.php">Каталог</a>
        <a href="auto.php">Авторизация</a>
        <a href="reg.php">Регистрация</a>
        <a href="profile.php">Профиль</a>
    </nav>
</header>

<div class="main-block">
    <h2>Ваш Профиль</h2>

    <?php
    if (isset($_SESSION['login']) && !empty($_SESSION['login'])) {

        $slogin = $_SESSION['login'];
        $spass1 = $_SESSION['pass1'];

        if (isset($_POST['update_user']) && $_POST['update_user'] == 'Изменить контакты') {
            $id = $_POST['id'];
            $login2 = $_POST['login2'];
            $mail = $_POST['mail'];
            $name = $_POST['name'];
            $surname = $_POST['surname'];
            $pass2 = $_POST['pass2'];

            $squery = "UPDATE reg SET 
                        login = '$login2',
                        email = '$mail', 
                        password = '$pass2', 
                        surname = '$surname', 
                        name = '$name' 
                        WHERE id = '$id'";
            $sresult = mysqli_query($conn, $squery);

            if ($sresult) {
                $_SESSION['login'] = $login2;
                $_SESSION['pass1'] = $pass2;
                echo '<div class="message message-success">Данные успешно обновлены!</div>';
            } else {
                echo '<div class="message message-error">Ошибка при обновлении данных: ' . mysqli_error($conn) . '</div>';
            }
        }
        
        $query = "SELECT * FROM reg WHERE login = '$slogin' AND password = '$spass1'";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_array($result);

        if ($row) {
    ?>

        <div class="informacya">
            <form action="profile.php" method="POST">
                
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