<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'borisov'; 

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Ошибка подключения к базе данных: " . mysqli_connect_error());
}

if (isset($_POST['out']) && $_POST['out'] == 'Выход') {
    setcookie("user_login", "", time() - 3600, "/");
    setcookie("user_pass1", "", time() - 3600, "/");
    setcookie("user_status", "", time() - 3600, "/");
    
    unset($_SESSION['login']);
    unset($_SESSION['pass1']);
    unset($_SESSION['status']);
    
    header("Location: auto.php"); 
    exit;
}

$is_logged_in = false;
if (isset($_COOKIE['user_login']) && isset($_COOKIE['user_pass1'])) {
    $login = $_COOKIE['user_login'];
    $pass1 = $_COOKIE['user_pass1'];
    
    $query = "SELECT status FROM reg WHERE login = ? AND password = ?"; 
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $login, $pass1); 
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_array($result);
            $status = $row[0];
            
            $_SESSION['login'] = $login;
            $_SESSION['pass1'] = $pass1;
            $_SESSION['status'] = $status;
            
            $is_logged_in = true;
        } else {
            setcookie("user_login", "", time() - 3600, "/");
            setcookie("user_pass1", "", time() - 3600, "/");
            setcookie("user_status", "", time() - 3600, "/");
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<header>
    <nav class="nav-links">
        <a href="index.php">Главная</a>
        <a href="catalog.php">Каталог</a>
        <a href="auto.php">Авторизация</a>
        <a href="autosql.php">Авторизация1</a>
        <a href="reg.php">Регистрация</a>
        <a href="profile.php">Профиль</a>
    </nav>
</header>

<div class="main-block">
    <h2>Авторизация</h2>

<?php
if (isset($_POST['auto']) && $_POST['auto'] == 'Войти') {
    
    $login = $_POST['login'];
    $pass1 = $_POST['pass1'];

    $query = "SELECT * FROM reg WHERE login = ? AND password = ?"; 
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $login, $pass1);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result) {
            $row = mysqli_fetch_array($result);

            if ($row && mysqli_num_rows($result) == 1) {
                
                $expiration_time = time() + (3600 * 24 * 7);

                setcookie("user_login", $row['login'], $expiration_time, "/");
                setcookie("user_pass1", $pass1, $expiration_time, "/");
                setcookie("user_status", $row[6], $expiration_time, "/");
                
                $_SESSION['login'] = $row['login'];
                $_SESSION['pass1'] = $pass1; 
                $_SESSION['status'] = $row[6]; 
                $is_logged_in = true;
                
                if ($_SESSION['status'] == 1) {
                    echo '<div class="message message-success">Добро пожаловать в <a href="admin.php">панель администратора</a></div>';
                } else {
                    echo '<div class="message message-success">Добро пожаловать, ' . htmlspecialchars($_SESSION['login']) . '!</div>';
                }
                
                echo '<form action="auto.php" method="post" style="text-align:center;">
                        <input type="submit" name="out" value="Выход">
                    </form>';
                
            } else {
                echo '<div class="message message-error">Данные не верны, попробуйте ещё раз <a href="auto.php">Войти</a></div>';
            }
        } else {
             echo '<div class="message message-error">Ошибка запроса: ' . mysqli_error($conn) . '</div>';
        }
        mysqli_stmt_close($stmt); 
    } else {
         echo '<div class="message message-error">Ошибка подготовки запроса: ' . mysqli_error($conn) . '</div>';
    }
    
} else if ($is_logged_in) {
    if ($_SESSION['status'] == 1) {
        echo '<div class="message message-success">Вы вошли автоматически как администратор. <a href="admin.php">Перейти в панель</a></div>';
    } else {
        echo '<div class="message message-success">Добро пожаловать, ' . htmlspecialchars($_SESSION['login']) . '! (Автоматический вход)</div>';
    }
    
    echo '<form action="auto.php" method="post" style="text-align:center;">
            <input type="submit" name="out" value="Выход">
        </form>';
        
} else { 
?>

<form action="auto.php" method="post">
    <label for="login">Введите логин</label>
    <input type="text" id="login" name="login" required>

    <label for="pass1">Введите пароль</label>
    <input type="password" id="pass1" name="pass1" required>

    <input type="submit" name="auto" value="Войти">
</form>

<?php
}

if ($conn) {
    mysqli_close($conn);
}
?>

</div>

<footer>
    <div class="contact-info">Контактная информация</div>
    <div class="partners">Партнеры</div>
</footer>

</body>
</html>