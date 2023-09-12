<?php
namespace classes;

// Подключение библиотеки DiDOM.
require_once "vendor/autoload.php";   
use DiDom\Document;


class Carrency 
{
    private $url = "https://cbr.ru/curreNcy_base/daily/";   // Url с курсом валют.

    //Метод парсинга курса валют и сохранения/обновления данных в БД.
    public function getCarrency($link) 
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
            foreach ($chunkArray as $subarray) {
                $rare = (float)str_replace(",",".", $subarray[4]);
                $insurt = mysqli_query($link, "UPDATE `currencies` SET `rate`='$rare'");
            }
        }    
    }
}