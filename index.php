<?php
session_start();  // Старт сессии
error_reporting(-1);
spl_autoload_register();  // Подключаем классы
require_once "config/db.php";   // Подключаем файл подключения к БД


// Если форма отправлена, отправляем данные на проверку в метод класса.
if (isset($_POST['auth'])) {  
    $authorization = new classes\Authorization($_POST['phoneormail'], $_POST['password']);  
    $authorization->check($link);
    header("Location: index.php");
    die;
}
?>

<!-- Страница авторизации -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/reset.css">
    <link rel="stylesheet" type="text/css" href="css/styleautho.css">
    <title>Авторизация</title>
</head>
<body>

<?php if(empty($_SESSION['name'])) : ?>  <!-- Если не авторизован, выводим форму -->
    <div class="container">
        <div class="head">
            <h3>Авторизация</h3>
        </div>
        <div class="flash">
            <?php 
                if (isset($_SESSION["errors"])) {
                    echo $_SESSION["errors"];
                    unset($_SESSION["errors"]);
                }
            ?>
        </div>
        <div class="form">
            <form action="index.php" method="POST">
                <p>Введите телефон или E-mail:</p> 
                <p><input type="text" name="phoneormail"></p>
                <p>Введите пароль: </p>
                <p><input type="password" name="password"></p>
                <p><input type="submit" name="auth" value="Войти"></p>
            </form>
        </div>
        <div class="links">
            <h3>Если вы еще не зарегистрированы, предлагаем вам завести аккаунт на нашем сайте:</h3>
            <div class="buttons">
                <button><a href="adduser.php">Зарегистрироваться</a></button>
            </div>
        </div>
    </div>
<?php else : ?> <!-- Если авторизован, перенаправляем на страницу профиля -->
    <?php
    header("Location:profile.php");
    die();
    ?>
<? endif; ?>
</body>
</html>