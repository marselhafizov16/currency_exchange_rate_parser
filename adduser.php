<?php
session_start();
error_reporting(-1);
spl_autoload_register();
require_once "config/db.php";   // Подключаем файл подключения к БД

if (isset($_POST['reg'])) {  // Проверяем, была ли нажата кнопка отправки формы
    $registration = new classes\Registration($_POST['name'], $_POST['phone'], $_POST['mail'], $_POST['password1'], $_POST['password2']);  // Отправляем данные на обработку
    $registration->check($link);
    header("Location: adduser.php");
    die;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/reset.css">
    <link rel="stylesheet" type="text/css" href="css/stylereg.css">
    <title>Регистрация</title>
</head>
<body>
<?php if(empty($_SESSION['name'])) : ?>  <!-- Пока пользователь не зарегестрировался, показываем форму -->
    <div class="container">
        <div class="head">
            <h1>Регистрация</h1>
        </div>
        <hr>
        <div class="flash">
            <?php 
                if (isset($_SESSION["success"])) {  // Выводим предупреждения
                    echo $_SESSION["success"];
                    unset($_SESSION["success"]);
                }
                if (isset($_SESSION["errors"])) {
                    echo $_SESSION["errors"];
                    unset($_SESSION["errors"]);
                }
            ?>
        </div>
        <div class="form">
            <form action="adduser.php" method="POST">
                <p>Имя:</p>
                <p><input type="text" name="name"></p>
                <p>Телефон:</p>
                <p><input type="text" name="phone"></p>
                <p>E-mail:</p>
                <p><input type="text" name="mail"></p>
                <p>Пароль:</p>
                <p><input type="password" name="password1"></p>
                <p>Повторите пароль:</p>
                <p> <input type="password" name="password2"></p>
                <p><input type="submit" name="reg" value="Зарегистрироваться"></p>
            </form>
        </div>
        <hr>
        <div class="links">
            <h3>Если вы уже зарегистрированы, вы можете просто авторизоваться:</h3>
            <button><a href="index.php">Авторизоваться</a></button>
        </div>
    </div>
<?php else : ?>  <!-- Если зарегестрировался, перенаправляем на страницу профиля -->
    <?php
    header("Location:profile.php");
    die();
    ?>
<? endif; ?>
</body>
</html>