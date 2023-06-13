<?php
session_start();
error_reporting(-1);
require_once "db/db.php";
require_once "classes/Setting.php";

if (isset($_POST['submitName']) && !empty($_POST['newName'])) {  // Если заполнена эта графа и нажата эта кнопка, отправляем данные на обработку
    $username = new Setting();
    $username->change($link, $_POST['newName'], $_POST['name']);
} 

if (isset($_POST['submitPhone']) && !empty($_POST['newPhone'])) {  // Если заполнена эта графа и нажата эта кнопка, отправляем данные на обработку
    $userphone = new Setting();
    $userphone->change($link, $_POST['newPhone'], $_POST['phone']);
} 

if (isset($_POST['submitMail']) && !empty($_POST['newMail'])) {  // Если заполнена эта графа и нажата эта кнопка, отправляем данные на обработку
    $userphone = new Setting();
    $userphone->change($link, $_POST['newMail'], $_POST['mail']);
} 

if (isset($_POST['submitPass']) && !empty($_POST['oldPassword']) && !empty($_POST['newPassword'])) {  // Если заполнена эта графа и нажата эта кнопка, отправляем данные на обработку
    $userpassword = new Setting();
    $userpassword->changePassword($link, $_POST['oldPassword'], $_POST['newPassword'], $_POST['newPasswordCheck']);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Настройки</title>
</head>
<body>
<?php if(isset($_SESSION['auth']) && $_SESSION['auth'] == true) : ?>  <!-- Проверяем пользователся на авторизацию -->
    <div class="container">
        <div class="flash"> 
            <?php if (isset($_SESSION['flesh'])) {  // Выводим предупреждения, если они имеются
                echo $_SESSION['flesh'];
                unset($_SESSION['flesh']);
            } ?>
        </div>
        <div class="name">
            <h3>Настройка имени</h3>
            <p>Ваше имя на данный момент: <?php echo $_SESSION['name'] ?></p>
            <form action="" method="post">
                <p>Введи новое имя:
                    <input type="text" name="newName">
                    <input type="hidden" name="name" value="name">
                    <input type="submit" name="submitName" value="Изменить имя">
                </p>
            </form>
        </div>
        <hr>
        <div class="phone">
            <h3>Настройка телефона</h3>
            <p>Ваше телефон на данный момент: <?php echo $_SESSION['phone'] ?></p>
            <form action="" method="post">
                <p>Введи новый телефон:
                    <input type="text" name="newPhone">
                    <input type="hidden" name="phone" value="phone">
                    <input type="submit" name="submitPhone" value="Изменить телефон">
                </p>
            </form>
        </div>
        <hr>
        <div class="mail">
            <h3>Настройка E-mail</h3>
            <p>Ваше E-mail на данный момент: <?php echo $_SESSION['mail'] ?></p>
            <form action="" method="post">
                <p>Введи новый E-mail:
                    <input type="text" name="newMail">
                    <input type="hidden" name="mail" value="mail">
                    <input type="submit" name="submitMail" value="Изменить E-mail">
                </p>
            </form>
        </div>
        <hr>
        <div class="password">
            <h3>Настройка пароля</h3>
            <form action="" method="post">
                <p>Введите старый пароль: <input name="oldPassword" type="password"></p>
                <p>Введите новый пароль: <input name="newPassword" type="password"></p>
                <p>Повторите новый пароль: <input name="newPasswordCheck" type="password"></p>
                <input type="submit" name="submitPass" value="Изменить пароль">
                </p>
            </form>
        </div>
        <hr>
        <div>
            <button><a href="profile.php" style="text-decoration:none;">Вернуться к профилю</a></button>
        </div>
    </div>
<?php else : ?>  <!-- Если пользователь не авторизован, выводим предупреждение -->
    <?php
        header("location:index.php");
        $_SESSION["errors"] = "Доступ к настройкам имеют только авторизованные пользователи!";
    ?>
<?php endif; ?>
</body>
</html>