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
    $convert = new classes\Carrency;
    $convert->convert($link, $_GET['from'], $_GET['to'], $_GET['amount'], $_GET['cod']);
    $cod = $_SESSION['cod'][0];
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
                <h6>Личные данные</h6>
                <p>Имя: <?php echo $_SESSION['name'] ?></p>
                <p>Телефон: <?php echo $_SESSION['phone'] ?></p>
                <p>E-mail: <?php echo $_SESSION['email'] ?></p>
            </div>
        </div>
        <div class="currency">   <!-- Конвертер валют. -->
            <h6>Конвертер курса валют</h6>
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
                    <div class="veiw">
                        <?php echo "<p>Курс на данный момент: " .  $cod[2] . " " .  $cod[1] . " = " . $cod[4] . " RUB</p>";?>
                    </div>
                    <div class="work">
                        <form action="" method="get">
                            <?php echo "Конвертировать из "?>
                            <select name="from">
                                <?php
                                echo "<option value='$cod[1]' selected>{$cod[1]}</option>";
                                echo "<option value='RUB'>RUB</option>";
                                ?>
                            </select>
                            <?php echo "<input type='number' name='amount' value='1'> в ";?>
                            <select name="to">
                                <?php
                                echo "<option value='$cod[1]' selected>{$cod[1]}</option>";
                                echo "<option value='RUB' selected>RUB</option>";
                                ?>
                            </select>
                            <?php echo 
                            "<input type='hidden' name='cod' value='$cod[1]'>" .
                            "<input type='submit' name='convert' value='Конвертировать'>";?>
                        </form>
                    </div>
                    <div class="convertresult">    <!-- Вывод результата конвертации. -->
                        <?php 
                        if (isset($_SESSION["flesh"])) {
                        echo $_SESSION["flesh"];
                        unset($_SESSION["flesh"]);
                        }
                        ?>
                    </div>
                </div>
        </div>
        <div class="links">  <!-- Выход из аккаунта. -->
            <button><a href="?do=exit" style="text-decoration:none;">Выйти из аккаунта</a></button>
        </div>
    </div>
</body>
</html>
