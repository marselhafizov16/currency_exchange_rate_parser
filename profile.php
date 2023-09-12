<?php
session_start();   // Старт сессии.
error_reporting(-1);
spl_autoload_register();    // Подключение классов.
require_once "config/db.php"; // Подключение БД.


// Если куки не существует, то задаем на 3 часа и создаем объект и обращаемся к методу, который получает курс валют и сохраняет их в БД.
if (!isset($_COOKIE['threeHours'])) {  
    $class = new classes\Carrency;
    $class->getCarrency($link);
    setcookie('threeHours', 1, time() + 10800);
}


// Если пользовтель нажал кнопку "Выйти из аккаунта", удаляем сессию, куки и возвращаемся на старницу авторизации.
if (isset($_GET['do']) && $_GET['do'] == 'exit') { 
    session_unset();
    setcookie('threeHours', 1, time() - 10800);
    header('Location:index.php');
    exit;
}
?>

<!-- Профиль пользователя. -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/reset.css">
    <link rel="stylesheet" type="text/css" href="css/styleprofil.css">
    <title>Профиль</title>
</head>
<body>
    <div class="container">
        <div class="flesh">  <!-- Информация об успешном входе в аккаунт. -->
            <?php 
                if (isset($_SESSION["success"])) {
                    echo $_SESSION["success"];
                    unset($_SESSION["success"]);
                }
            ?>
        </div>
        <div class="profil">   <!-- Данные пользователя. -->
            <h1>Добро пожаловать на свой профиль, <?php echo $_SESSION['name'] ?></h1>
            <hr>
            <div class="date">
                <h3>Личные данные</h3>
                <p>Имя: <?php echo $_SESSION['name'] ?></p>
                <p>Телефон: <?php echo $_SESSION['phone'] ?></p>
                <p>E-mail: <?php echo $_SESSION['email'] ?></p>
            </div>
        </div>
        <div class="currancy">   <!-- Конвертер валют. -->
            <h1>Конвертер курса валют</h1>
            <div class="document">

            </div>
        </div>
        <hr>
        <div class="links">  <!-- Выход из аккаунта. -->
            <button><a href="?do=exit" style="text-decoration:none;">Выйти из аккаунта</a></button>
        </div>
    </div>
</body>
</html>
