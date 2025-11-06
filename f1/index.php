<?php
session_start();

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'borisov'; 

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!isset($_SESSION['login']) && isset($_COOKIE['user_login']) && isset($_COOKIE['user_pass1'])) {
    $login = mysqli_real_escape_string($conn, $_COOKIE['user_login']);
    $pass1 = mysqli_real_escape_string($conn, $_COOKIE['user_pass1']);
    
    $query = "SELECT status FROM reg WHERE login = '$login' AND password = '$pass1'"; 
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        
        $_SESSION['login'] = $login;
        $_SESSION['pass1'] = $pass1;
        $_SESSION['status'] = $row[0];
    } else {
        setcookie("user_login", "", time() - 3600, "/");
        setcookie("user_pass1", "", time() - 3600, "/");
        setcookie("user_status", "", time() - 3600, "/");
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        .page-content {
            text-align: center;
            padding: 20px;
        }
    </style>
</head>
<body>

<header>
    <nav class="nav-links">
        <a href="index.php">Главная</a>
        <a href="catalog.php">Каталог</a>
        <a href="auto.php">Авторизация</a>
        <a href="reg.php">Регистрация</a>
        <?php
        if (isset($_SESSION['login']) && !empty($_SESSION['login'])) {
            echo '<a href="profile.php">Профиль</a>';
            echo '<form action="auto.php" method="post" style="display:inline-block; margin-left: 15px;">
                    <input type="submit" name="out" value="Выход" style="width: auto; background: none; color: #555555; padding: 0; border: none; font-weight: 500; text-transform: none; letter-spacing: normal;">
                  </form>';
        }
        ?>
    </nav>
</header>

<div class="main-block page-content">
    
    <h1>Добро пожаловать</h1>
    <p>Это центральный узел вашего тестового сайта.</p>

    <?php if (isset($_SESSION['login']) && !empty($_SESSION['login'])): ?>
        <p>Вы вошли как: **<?php echo $_SESSION['login']; ?>**</p>
    <?php else: ?>
        <p>Пожалуйста, <a href="auto.php">Авторизуйтесь</a> или <a href="reg.php">Зарегистрируйтесь</a>, чтобы получить полный доступ.</p>
    <?php endif; ?>

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