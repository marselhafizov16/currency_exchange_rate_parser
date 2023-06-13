<?php
session_start();
error_reporting(-1);

require_once "db/db.php";   // Подключаем файл подключения к БД
require_once "classes/Authorization.php";  // Подключаем класс, проверяющий данные авторизации

if (isset($_POST['auth'])) {  // Проверяем, была ли нажата кнопка отправки формы
    $authorization = new Authorization($_POST['phoneormail'], $_POST['password']);  // Отправляем данные на обработку
    $authorization->check($link, $_POST['smart-token']);
    header("Location: index.php");
    die;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://smartcaptcha.yandexcloud.net/captcha.js" defer></script>
    <title>Авторизация</title>
</head>
<body>

<?php if(empty($_SESSION['name'])) : ?>  <!-- Если не авторизован, выводим форму -->
    <div class="container">
        <div class="block">
            <div class="head">
                <h1>Авторизация</h1>
            </div>
        </div>
        <div class="block">
            <div class="flash">
                <?php 
                    if (isset($_SESSION["errors"])) {
                        echo $_SESSION["errors"];
                        unset($_SESSION["errors"]);
                    }
                ?>
            </div>
        </div>
        <div class="block">
            <div class="form">
                <form action="index.php" method="POST">
                    <p>Введите телефон / E-mail:</p> 
                    <p><input type="text" name="phoneormail"></p>
                    <p>Введите пароль: </p>
                    <p><input type="password" name="password"></p>
                    <div style="height: 100px"
                         id="captcha-container"
                         class="smart-captcha"
                         data-sitekey="ysc1_M1cbdg8RnO0EVDAwfqqCejvNqFOBHGXwR5GE554F4df79687">
                         <input type="hidden" name="smart-token" value="" >
                    </div>
                    <p><input type="submit" name="auth" value="Войти"></p>
                </form>
            </div>
        </div>
        <div class="block">
            <div class="link">
                <h3>Если вы еще не зарегистрированы, предлагаем вам завести аккаунт на нашем сайте:</h3>
                <p><button><a href="adduser.php" style="text-decoration:none">Зарегистрироваться</a></button></p>  
                <p><button><a href="settings.php" style="text-decoration:none;">Настройки</a></button></p>  <!-- Демонтсрация страницы доступной только авторизованным людям -->
            </div>
        </div>
        
        <footer></footer>
    </div>
<?php else : ?> <!-- Если авторизован, перенаправляем на страницу профиля -->
    <?php
    header("Location:profile.php");
    die();
    ?>
<? endif; ?>
</body>
</html>