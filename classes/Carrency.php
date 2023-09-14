<?php
namespace classes;

// Подключение библиотеки DiDOM.
require_once "vendor/autoload.php";   
use DiDom\Document;


class Carrency 
{
    private $url = "https://cbr.ru/curreNcy_base/daily/";   // Url с курсом валют.

    //Метод парсинга курса валют и сохранения/обновления данных в БД.
    public function getCurrency($link) 
    {
        // С помощью парсера получаем элементы с курсами валют из заданного url.
        $document = new Document($this->url, true);  
        $elems = $document->find('td');

        // В массив сохраняем полученные данные.
        $mainArray = [];
        foreach ($elems as $elem) 
        {
            $mainArray[] = $elem->text();
        }
        $chunkArray = array_chunk($mainArray, 5);

        //  Если БД пуста, то сохраняем полученные парсером данные, иначе обнавляем только необходимые данные.
        $select = mysqli_query($link, "SELECT `id` FROM `currencies`");
        $resultSelect = mysqli_fetch_all($select);
        if (empty($resultSelect)) {
            foreach ($chunkArray as $subarray) {
                $rare = (float)str_replace(",",".", $subarray[4]);
                $insurt = mysqli_query($link, "INSERT INTO `currencies` VALUES (NULL,'$subarray[1]','$subarray[2]','$subarray[3]','$rare')");
            }
        } else {
            $count = $resultSelect[0][0];
            foreach ($chunkArray as $subarray) {
                $rate = (float)str_replace(",",".", $subarray[4]); 
                $insurt = mysqli_query($link, "UPDATE `currencies` SET `rate`='$rate' WHERE id='$count'");
                $count += 1;
            }
        }    
    }


    // Метод извлекаетвалюту для первичного вывода
    public function getFirstCurrency($link) 
    {
        $select1 = mysqli_query($link, "SELECT * FROM `currencies` WHERE code='USD'");
        $select2 = mysqli_query($link, "SELECT `code`, `currency` FROM `currencies`");
        $cod = mysqli_fetch_all($select1);
        $cc = mysqli_fetch_all($select2);
        $_SESSION['cod'] = $cod;
        $_SESSION['cc'] = $cc;
    }


    // Метод извлекает и передает выбранную валюту
    public function getChoiceCurrency($link, $currency)
    {
        $select1 = mysqli_query($link, "SELECT * FROM `currencies` WHERE code='$currency'");
        $cod = mysqli_fetch_all($select1);
        $_SESSION['cod'] = $cod;
    }


    // Метод конвертирует валюту
    public function convert($link, $from, $to, $amount, $cod)
    {
        if ($amount < 0) {
            $_SESSION['flesh'] = "Значение должно быть положительным";
            return false;
        }

        if ($from == $to) {
            $select1 = mysqli_query($link, "SELECT * FROM `currencies` WHERE code='$cod'");
            $rescod = mysqli_fetch_all($select1);
            $_SESSION['cod'] = $rescod;
            $_SESSION['flesh'] = "{$amount} {$from} = {$amount} {$to}";
            return false;
        }
        // var_dump($from, $to, $amount);

        if ($from !== "RUB") {
            $select1 = mysqli_query($link, "SELECT * FROM `currencies` WHERE code='$from'");
            $rescod = mysqli_fetch_all($select1);
            $result = ((float)$rescod[0][4] * (float)$amount) / $rescod[0][2];
            $_SESSION['flesh'] = "{$amount} {$from} = {$result} {$to}";
            // $_SESSION['resultconvert'] = $result;
            // $_SESSION['count'] = $amount;
            $_SESSION['cod'] = $rescod;
        }

        if ($from == "RUB") {
            $select1 = mysqli_query($link, "SELECT * FROM `currencies` WHERE code='$to'");
            $rescod = mysqli_fetch_all($select1);
            $result = (float)$amount / ((float)$rescod[0][4] * $rescod[0][2]);
            $_SESSION['flesh'] = "{$amount} {$from} = {$result} {$to}";
            // $_SESSION['resultconvert'] = $result;
            // $_SESSION['count'] = $amount;
            $_SESSION['cod'] = $rescod;
        }
    }
}