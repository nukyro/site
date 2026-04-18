<?php
session_start();

if (isset($_GET['out'])) {
    unset($_SESSION['login']);
    unset($_SESSION['pass1']);
    unset($_SESSION['status']);
    header("Location: index.php");
    exit;
}

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'datab'; 

$conn = mysqli_connect($host, $user, $pass, $dbname);
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
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
    </style>
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
            echo '<a href="index.php?out=1">Выход</a>';
        }
        ?>
    </nav>
</header>

<div class="main-block page-content" style="max-width: 900px;">
    
    <h1>Добро пожаловать</h1>

    <div class="slider-container" style="margin-top: 0; margin-bottom: 30px;">
        <div class="slider" id="slider">
            <div class="slide"><img src="https://picsum.photos/800/400?random=1" alt="Slide 1"></div>
            <div class="slide"><img src="https://picsum.photos/800/400?random=2" alt="Slide 2"></div>
            <div class="slide"><img src="https://picsum.photos/800/400?random=3" alt="Slide 3"></div>
        </div>
        <button class="prev-slide" onclick="moveSlide(-1)">&#10094;</button>
        <button class="next-slide" onclick="moveSlide(1)">&#10095;</button>
    </div>

    <p style="text-align: center;">Это центральный узел вашего сайта по управлению заявками.</p>

    <div style="text-align: center;">
    <?php if (isset($_SESSION['login']) && !empty($_SESSION['login'])): ?>
        <p>Вы вошли как: **<?php echo $_SESSION['login']; ?>**</p>
        <form action="auto.php" method="post" style="display:inline-block; margin-top: 10px;">
            <input type="submit" name="out" value="Выйти из аккаунта">
        </form>
    <?php else: ?>
        <p>Пожалуйста, <a href="auto.php">авторизуйтесь</a> или <a href="reg.php">зарегистрируйтесь</a>.</p>
    <?php endif; ?>
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

<script>
    let currentSlide = 0;
    const slider = document.getElementById('slider');
    const slides = document.querySelectorAll('.slide');
    const totalSlides = slides.length;

    function moveSlide(direction) {
        currentSlide += direction;
        if (currentSlide < 0) {
            currentSlide = totalSlides - 1;
        } else if (currentSlide >= totalSlides) {
            currentSlide = 0;
        }
        updateSlider();
    }

    function updateSlider() {
        const offset = -currentSlide * 100;
        slider.style.transform = `translateX(${offset}%)`;
    }
    
    // Автоматическая прокрутка
    setInterval(() => moveSlide(1), 5000);
</script>

</body>
</html>