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



$user_login = $_SESSION['login'];
$user_query = "SELECT id FROM reg WHERE login = '$user_login'";
$user_result = mysqli_query($conn, $user_query);
$user_row = mysqli_fetch_assoc($user_result);
$user_id = $user_row['id'];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мои заявки</title>
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

<div class="main-block" style="max-width: 900px;">
    <h2>Мои заявки</h2>

    <div class="product-list">
    <?php
    $requests_query = "SELECT id, title, description, status, created_at FROM requests  ORDER BY created_at DESC";
    $requests_result = mysqli_query($conn, $requests_query);
    
    if (mysqli_num_rows($requests_result) > 0) {
        while ($request = mysqli_fetch_assoc($requests_result)) {
            // Исправленная логика для совместимости с PHP 7.x
            $status_class = '';
            if ($request['status'] == 'Новая' || $request['status'] == 'Отклонена') {
                $status_class = 'message-error';
            } elseif ($request['status'] == 'В работе' || $request['status'] == 'Выполнена') {
                $status_class = 'message-success';
            }
    ?>
        <div class="product-card">
            <h3>Заявка №<?php echo $request['id']; ?>: <?php echo htmlspecialchars($request['title']); ?></h3>
            <p><strong>Дата создания:</strong> <?php echo date("d.m.Y H:i", strtotime($request['created_at'])); ?></p>
            <p><strong>Описание:</strong> <?php echo htmlspecialchars($request['description']); ?></p>
            <div class="message <?php echo $status_class; ?>" style="display: inline-block; padding: 5px 10px; margin-top: 10px;">
                Статус: <strong><?php echo $request['status']; ?></strong>
            </div>
        </div>
    <?php
        }
    } else {
        echo "<div class='message message-success'>У вас пока нет заявок. <a href='create_request.php'>Создать заявку</a></div>";
    }
    ?>
    </div>
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