<?php
session_start();
error_reporting(-1);

if (isset($_GET['do']) && $_GET['do'] == 'exit') { // Если пользовтель нажал кнопку "Выйти из аккаунта", удаляем ссееия и возвращаемся к авторизации
    session_unset();
    header('Location:index.php');
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль</title>
</head>
<body>
    <div class="container">
        <div class="flesh">
            <?php 
                if (isset($_SESSION["success"])) {
                    echo $_SESSION["success"];
                    unset($_SESSION["success"]);
                }
            ?>
        </div>

        <h1>Добро пожаловать на свой профиль, <?php echo $_SESSION['name'] ?></h1>

        <hr>
        <div class="date">
            <h3>Личные данные</h3>
            <p>Имя: <?php echo $_SESSION['name'] ?></p>
            <p>Телефон: <?php echo $_SESSION['phone'] ?></p>
            <p>E-mail: <?php echo $_SESSION['mail'] ?></p>
        </div>
        <hr>
        <div class="links">
            <button><a href="settings.php" style="text-decoration:none;">Настройки</a></button > <!-- Страница с возможностью исзменить свои данные, доступ имеют только авторизованные пользователи -->
            <button><a href="?do=exit" style="text-decoration:none;">Выйти из аккаунта</a></button>
        </div>
    </div>

</body>
</html>
