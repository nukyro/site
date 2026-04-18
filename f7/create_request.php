<?php
session_start();

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'datab'; 

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Ошибка подключения к базе данных: " . mysqli_connect_error());
}



if (isset($_POST['create_request']) && $_POST['create_request'] == 'Отправить заявку') {
    
    $title = $_POST['title']; 
    $description = $_POST['description']; 
    $user_login = $_SESSION['login']; 

    $user_query = "SELECT id FROM reg WHERE login = '$user_login'";
    $user_result = mysqli_query($conn, $user_query);
    $user_row = mysqli_fetch_assoc($user_result);
    $user_id = $user_row['id'];
    
    if (!empty($title) && !empty($description)) {
        
        $query = "INSERT INTO requests (user_id, title, description, status) 
                  VALUES ('$user_id', '$title', '$description', 'Новая')"; 
        
        $result = mysqli_query($conn, $query);

        if ($result) {
            $message = '<div class="message message-success">Ваша заявка успешно отправлена!</div>';
        } else {
             $message = '<div class="message message-error">Ошибка при добавлении заявки в базу данных: ' . mysqli_error($conn) . '</div>';
        }
    
    } else {
        $message = '<div class="message message-error">Заполните все поля формы.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создание заявки</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

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
    <h2>Создание заявки</h2>
    
    <?php if (isset($message)) echo $message; ?>

    <form action="create_request.php" method="post">
        <label for="title">Тема заявки</label>
        <input type="text" id="title" name="title" required>

        <label for="description">Описание проблемы</label>
        <textarea id="description" name="description" required></textarea>

        <input type="submit" name="create_request" value="Отправить заявку">
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