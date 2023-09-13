<?php
session_start();   // Старт сессии.
error_reporting(-1);
spl_autoload_register();    // Подключение классов.
require_once "config/db.php"; // Подключение БД.


// Если куки не существует, то задаем на 3 часа и создаем объект и обращаемся к методу, который получает курс валют и сохраняет их в БД.
if (!isset($_COOKIE['threeHours'])) {  
    $class = new classes\Carrency;
    $class->getCurrency($link);
    setcookie('threeHours', 1, time() + 10800);
}

// Если форма не отправлена, то обращаемся к методу, который извлекает значения первичной валюты (USD).
if (!isset($_GET['choosecurrency']) || !isset($_GET['convert'])) {
    $choice1 = new classes\Carrency;
    $choice1->getFirstCurrency($link);
    $cod = $_SESSION['cod'][0];
    $cc = $_SESSION['cc'];
}

// Если отправлена форма с данным свойством, то обращаемся к методу, который извлекает значения переданной валюты. 
if (isset($_GET['choosecurrency'])){
    $choice2 = new classes\Carrency;
    $choice2->getChoiceCurrency($link, $_GET['currency']);
    $cod = $_SESSION['cod'][0];
    $cc = $_SESSION['cc'];
}

// Конвертация в RUB
if (isset($_GET['convert'])) {
    $convert1 = new classes\Carrency;
    $convert1->convert($link, $_GET['amount'], $_GET['cod']);
    $result = $_SESSION['resultconvert'];
    $count = $_SESSION['count'];
    $cod = $_SESSION['cod'][0];
}

// Конвертация в выбранную валюту
if (isset($_GET['convertopposite'])) {
    $convert2 = new classes\Carrency;
    $convert2->oppositeConvert($link, $_GET['amount'], $_GET['cod']);
    $result = $_SESSION['resultconvert'];
    $count = $_SESSION['count'];
    $cod = $_SESSION['cod'][0];
}


// Если пользовтель нажал  "Развернуть", меняем значение сессии.
if (isset($_GET['but']) && $_GET['but'] == 'opposite') {
    if ($_SESSION['turn'] == 'first') {
        $_SESSION['turn'] = 'second';
        var_dump($_SESSION['turn']);
    } else {
        $_SESSION['turn'] = 'first';
    }
    header('Location:index.php');
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
    <link rel="stylesheet" type="text/css" href="css/styleprofile.css">
    <title>Профиль</title>
</head>
<body>
    <div class="container">
        <div class="flesh">  <!-- Флеш-сообщение об успешном входе в аккаунт. -->
            <?php 
                if (isset($_SESSION["success"])) {
                    echo $_SESSION["success"];
                    unset($_SESSION["success"]);
                }
            ?>
        </div>
        <div class="profil">   <!-- Данные пользователя. -->
            <div class="date">
                <h3>Личные данные</h3>
                <p>Имя: <?php echo $_SESSION['name'] ?></p>
                <p>Телефон: <?php echo $_SESSION['phone'] ?></p>
                <p>E-mail: <?php echo $_SESSION['email'] ?></p>
            </div>
        </div>
        <hr>
        <div class="currency">   <!-- Конвертер валют. -->
            <h1>Конвертер курса валют</h1>
            <div class="document">
                <div class="choosecurrency">    <!-- Форма выбора валюты. -->
                    <form action="" method="get">
                        <select name="currency">
                            <?php
                            foreach ($cc as $elem) {
                                if ($elem[0] == $cod[1]) {
                                    echo "<option value='$elem[0]' selected>{$elem[0]} - {$elem[1]}</option>";
                                } else {
                                    echo "<option value='$elem[0]'>{$elem[0]} - {$elem[1]}</option>";
                                }
                            }
                            ?>
                        </select>
                        <input type="submit" name="choosecurrency" value="Выбрать валюту">
                    </form>
                </div>
                <div class="viewcurrency">   <!-- Вывод курса выбранной валюты и конвертация. -->
                    <?php if ($_SESSION['turn'] == 'first') : ?>
                        <div class="first">
                            <?php 
                            echo "Курс на данный момент: " .  $cod[2] . " " .  $cod[1] . " = " . $cod[4] . " RUB";
                            ?>
                            <form action="" method="get">
                                <p>
                                    <?php
                                    
                                        echo "Конвертировать из " .$cod[1];
                                        echo "<input type='hidden' name='cod' value='$cod[1]'>" .
                                            "<input type='number' name='amount' value='$cod[2]'>" . " в RUB ".
                                            "<input type='submit' name='convert' value='Конвертировать'>";
                                    ?>
                                </p>
                            </form>
                            <div class="convertresult">
                                <?php 
                                    if (isset($_SESSION["flesh"])) {
                                    echo $_SESSION["flesh"];
                                    unset($_SESSION["flesh"]);
                                    }
                                    if (isset($result) && isset($count)) {
                                        echo "<p>$count $cod[1] =  $result RUB</p>";
                                    }
                                ?>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="second">
                        <?php 
                            echo "Курс на данный момент: " .  $cod[2] . " " .  $cod[1] . " = " . $cod[4] . " RUB";
                            ?>
                            <form action="" method="get">
                                <p>
                                    <?php
                                    
                                        echo "Конвертировать из " . "RUB";
                                        echo "<input type='hidden' name='cod' value='$cod[1]'>" .
                                            "<input type='number' name='amount' value='1'> в " . $cod[1] .
                                            "<input type='submit' name='convertopposite' value='Конвертировать'>";
                                    ?>
                                </p>
                            </form>
                            <div class="convertresult">
                                <?php 
                                    if (isset($_SESSION["flesh"])) {
                                    echo $_SESSION["flesh"];
                                    unset($_SESSION["flesh"]);
                                    }
                                    if (isset($result) && isset($count)) {
                                        echo "<p>$result RUB =  $count $cod[1]</p>";
                                    }
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="opposite">
                    <a href="?but=opposite">Развернуть</a>
                </div>
            </div>
        </div>
        <hr>
        <div class="links">  <!-- Выход из аккаунта. -->
            <button><a href="?do=exit" style="text-decoration:none;">Выйти из аккаунта</a></button>
        </div>
    </div>
</body>
</html>
