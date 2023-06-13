<?php
error_reporting(-1);

class Setting 
{
    public function change($link, $new, $field)   // Метод, обрабатыващий все данные кроме пароля
    {
        $id = $_SESSION['id'];
        $result = mysqli_query($link, "UPDATE users SET $field='$new' WHERE id='$id' ");
        $_SESSION["$field"] = $new;
        $_SESSION['flesh'] = 'Данные успешно обновлены!';
    }

    public function changePassword($link, $oldPass, $newPass, $newPassCheck)  // Метод, обрабатывающий изменение пароля
    {
        $id = $_SESSION['id'];
        $result = mysqli_query($link, "SELECT * FROM users WHERE id='$id'" );  // Находим пользователя с данным id в БД
        $user = mysqli_fetch_assoc($result);

        if (!preg_match('/^[a-z0-9]+$/i', $newPass)) {  // Проверяем на корректность пароля
            $_SESSION['flesh'] = 'Пароль должен состоять из латинских букв и цифр!';
            return false;
        }

        if (6 > mb_strlen($newPass)) {   // Проверяем на корректность пароля
            $_SESSION['flesh'] = 'Пароль должен быть больше 6 знаков!';
            return false;
        }

        if (!empty($oldPass) && password_verify($oldPass, $user['password'])) // Если совпадает существующий пароль и введенный пользователем для проверки, то меняем пароль
        {
            if ($newPass == $newPassCheck)
            {
                $newPasswordHash = password_hash($newPass, PASSWORD_DEFAULT);
                $result1 = mysqli_query($link, "UPDATE users SET password='$newPasswordHash' WHERE id='$id'" );
                $_SESSION['flesh'] = 'Пароль успешно изменен!';
            } else {
                $_SESSION['flesh'] = 'Новый пароль не совпадает!';
            }
        } else {
            $_SESSION['flesh'] = 'Вы ввели неверный старый пароль!';

        }
    }
}