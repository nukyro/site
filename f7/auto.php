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
$dbname = 'datab'; 

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Ошибка подключения к базе данных: " . mysqli_connect_error());
}

if (isset($_POST['out']) && $_POST['out'] == 'Выход') {
    
    unset($_SESSION['login']);
    unset($_SESSION['pass1']);
    unset($_SESSION['status']);
    
    header("Location: auto.php"); 
    exit;
}

$is_logged_in = false;
if (isset($_SESSION['login']) && isset($_SESSION['pass1'])) {
    $is_logged_in = true;
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
    <h2>Авторизация</h2>

<?php
if (isset($_POST['auto']) && $_POST['auto'] == 'Войти') {
    
    $login = $_POST['login'];
    $pass1 = $_POST['pass1'];

    $query = "SELECT id, login, password, status FROM reg WHERE login = '$login' AND password = '$pass1'"; 
    
    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_array($result);

        if ($row && mysqli_num_rows($result) == 1) {
            
            $_SESSION['login'] = $row['login'];
            $_SESSION['pass1'] = $pass1; 
            $_SESSION['status'] = $row[3]; 
            $is_logged_in = true;
            
            if ($_SESSION['status'] == 1) {
                echo '<div class="message message-success">Добро пожаловать в <a href="admin.php">панель администратора</a></div>';
            } else {
                echo '<div class="message message-success">Добро пожаловать, ' . $_SESSION['login'] . '!</div>';
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
    
} else if ($is_logged_in) {
    if ($_SESSION['status'] == 1) {
        echo '<div class="message message-success">Вы вошли как администратор. <a href="admin.php">Перейти в панель</a></div>';
    } else {
        echo '<div class="message message-success">Добро пожаловать, ' . $_SESSION['login'] . '!</div>';
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