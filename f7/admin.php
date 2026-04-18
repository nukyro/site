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

if (!isset($_SESSION['status']) || $_SESSION['status'] != 1) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['update_status'])) {
    $request_id = $_POST['request_id'];
    $new_status = $_POST['status']; 

    $update_query = "UPDATE requests SET status = '$new_status' WHERE id = '$request_id'";
    
    if (mysqli_query($conn, $update_query)) {
        $message = '<div class="message message-success">Статус заявки №' . $request_id . ' обновлен!</div>';
    } else {
        $message = '<div class="message message-error">Ошибка обновления статуса: ' . mysqli_error($conn) . '</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель администратора</title>
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

<div class="main-block admin-panel">
    <h2>Панель администратора</h2>
    
    <?php if (isset($message)) echo $message; ?>

    <h3>Все заявки в системе</h3>

    <div class="product-list">
        <?php
        $requests_query = "SELECT r.id, r.title, r.description, r.status, r.created_at, u.login 
                           FROM requests r
                           JOIN reg u ON r.user_id = u.id
                           ORDER BY r.created_at DESC";
        $requests_result = mysqli_query($conn, $requests_query);
        
        if (mysqli_num_rows($requests_result) > 0) {
            while ($request = mysqli_fetch_assoc($requests_result)) {
        ?>
            <div class="product-card">
                <h4>Заявка №<?php echo $request['id']; ?> от пользователя: **<?php echo $request['login']; ?>**</h4>
                <p><strong>Дата:</strong> <?php echo date("d.m.Y H:i", strtotime($request['created_at'])); ?></p>
                <p><strong>Тема:</strong> <?php echo htmlspecialchars($request['title']); ?></p>
                <p><strong>Описание:</strong> <?php echo htmlspecialchars($request['description']); ?></p>
                <p><strong>Текущий статус:</strong> <?php echo $request['status']; ?></p>
                
                <form action="admin.php" method="POST" style="margin-top: 15px; flex-direction: row; align-items: center; gap: 10px;">
                    <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                    <label for="status_<?php echo $request['id']; ?>" style="margin: 0; font-weight: normal;">Изменить статус:</label>
                    <select id="status_<?php echo $request['id']; ?>" name="status" required style="padding: 8px; border: 1px solid #ccc; border-radius: 4px; display: inline-block; width: auto; flex-grow: 1;">
                        <option value="Новая" <?php if ($request['status'] == 'Новая') echo 'selected'; ?>>Новая</option>
                        <option value="В работе" <?php if ($request['status'] == 'В работе') echo 'selected'; ?>>В работе</option>
                        <option value="Выполнена" <?php if ($request['status'] == 'Выполнена') echo 'selected'; ?>>Выполнена</option>
                        <option value="Отклонена" <?php if ($request['status'] == 'Отклонена') echo 'selected'; ?>>Отклонена</option>
                    </select>
                    <input type="submit" name="update_status" value="Обновить" style="width: auto; padding: 8px 15px;">
                </form>
            </div>
        <?php
            }
        } else {
            echo "<div class='message message-success'>Нет открытых заявок.</div>";
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