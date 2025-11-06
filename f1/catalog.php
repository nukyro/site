<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Каталог</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'borisov'; 

$conn = mysqli_connect($host, $user, $pass, $dbname);

$user_id = null;
$user_full_name = ''; 
if (isset($_SESSION['login']) && !empty($_SESSION['login'])) {
    $login = mysqli_real_escape_string($conn, $_SESSION['login']);
    $user_query = "SELECT id, name, surname FROM reg WHERE login = '$login'";
    $user_result = mysqli_query($conn, $user_query);
    if ($user_result && $user_row = mysqli_fetch_assoc($user_result)) {
        $user_id = $user_row['id'];
        $user_full_name = $user_row['name'] . ' ' . $user_row['surname'];
    }
}

if (isset($_POST['post_comment']) && $user_id) {
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $comment_text = mysqli_real_escape_string($conn, trim($_POST['comment_text']));
    $category_param = urlencode($_POST['category_param'] ?? '');

    if (!empty($comment_text)) {
        $insert_query = "INSERT INTO comments (product_id, user_id, comment_text) VALUES ('$product_id', '$user_id', '$comment_text')";
        if (mysqli_query($conn, $insert_query)) {
            $message_status = "Комментарий успешно добавлен!";
        } else {
            $message_status = "Ошибка при добавлении комментария: " . mysqli_error($conn);
        }
    } else {
        $message_status = "Нельзя отправить пустой комментарий.";
    }
    header("Location: catalog.php?category=" . $category_param . "&message=" . urlencode($message_status));
    exit;
}

$display_message = '';
if (isset($_GET['message'])) {
    $display_message = $_GET['message'];
    $message_class = (strpos($display_message, 'успешно') !== false) ? 'message-success' : 'message-error';
    $display_message = "<div class='message $message_class'>$display_message</div>";
}
?>

<header>
    <nav class="nav-links">
        <a href="index.php">Главная</a>
        <a href="catalog.php">Каталог</a>
        <a href="catalog1.php">Каталог1</a>
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

<div class="main-block" style="max-width: 900px;">
    <h2>Каталог товаров</h2>
    
    <?php echo $display_message; ?>

    <div class="category-filter">
        <?php $active_all = !isset($_GET['category']) || empty($_GET['category']) ? 'active' : ''; ?>
        <a href="catalog.php" class="filter-link <?php echo $active_all; ?>">Все товары</a>
        <?php
        $category_query = "SELECT name FROM category ORDER BY name ASC";
        $category_result = mysqli_query($conn, $category_query);
        
        while ($cat_row = mysqli_fetch_assoc($category_result)) {
            $cat_name = $cat_row['name'];
            $active_class = (isset($_GET['category']) && $_GET['category'] == $cat_name) ? 'active' : '';
            echo "<a href=\"catalog.php?category=" . urlencode($cat_name) . "\" class=\"filter-link $active_class\">" . $cat_name . "</a>";
        }
        ?>
    </div>
    
    <div class="product-list">
        <?php
        $where_clause = "";
        $current_category = '';
        if (isset($_GET['category']) && !empty($_GET['category'])) {
            $current_category = $_GET['category'];
            $selected_category = mysqli_real_escape_string($conn, $current_category);
            $where_clause = "WHERE category = '$selected_category'";
        }

        $tovar_query = "SELECT * FROM tovar $where_clause ORDER BY id DESC";
        $tovar_result = mysqli_query($conn, $tovar_query);
        
        if (mysqli_num_rows($tovar_result) > 0) {
            while ($tovar_row = mysqli_fetch_assoc($tovar_result)) {
                $product_id = $tovar_row['id'];
        ?>
        <div class="product-card">
            <h3><?php echo $tovar_row['name']; ?></h3>
            <p><strong>Категория:</strong> <?php echo $tovar_row['category']; ?></p>
            <p><strong>Описание:</strong> <?php echo $tovar_row['smalldesc']; ?></p>
            <p class="price"><?php echo $tovar_row['price']; ?></p>

            <div class="comment-section">
                <h4>Комментарии</h4>
                
                <div class="comment-list">
                    <?php
                    $comments_query = "SELECT c.comment_text, c.created_at, r.name, r.surname 
                                       FROM comments c
                                       JOIN reg r ON c.user_id = r.id
                                       WHERE c.product_id = $product_id
                                       ORDER BY c.created_at DESC";
                    $comments_result = mysqli_query($conn, $comments_query);
                    
                    if (mysqli_num_rows($comments_result) > 0) {
                        while ($comment = mysqli_fetch_assoc($comments_result)) {
                            $author_name = $comment['name'] . ' ' . $comment['surname'];
                            $date = date("d.m.Y H:i", strtotime($comment['created_at']));
                            echo "<div class='comment-item'>";
                            echo "<span class='comment-author'>$author_name</span>";
                            echo "<span class='comment-date'>($date)</span>";
                            echo "<p class='comment-text'>" . $comment['comment_text'] . "</p>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>Комментариев пока нет.</p>";
                    }
                    ?>
                </div>

                <?php if ($user_id): ?>
                    <div class="comment-form">
                        <h5>Оставить комментарий (Вы: <?php echo $user_full_name; ?>)</h5>
                        <form action="catalog.php" method="POST">
                            <textarea name="comment_text" placeholder="Ваш комментарий..." required></textarea>
                            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                            <input type="hidden" name="category_param" value="<?php echo $current_category; ?>">
                            <input type="submit" name="post_comment" value="Отправить комментарий" style="width: auto; padding: 8px 15px;">
                        </form>
                    </div>
                <?php else: ?>
                    <p style="text-align: center; margin-top: 15px;"><a href="auto.php">Авторизуйтесь</a>, чтобы оставить комментарий.</p>
                <?php endif; ?>
            </div>
        </div>
        <?php
            }
        } else {
            echo "<div class=\"message message-error\">Товары в этой категории не найдены.</div>";
        }
        ?>
    </div>
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