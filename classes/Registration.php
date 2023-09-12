<?php
namespace classes;

class Registration 
{
    public $name;
    public $phone;
    public $mail;
    public $password1;
    public $password2;

    // Метод присвивает свойствам переданные данные.
    public function __construct($name, $phone, $mail, $password1, $password2)
    {
        $this->name = !empty($name) ? trim($name) : '';
        $this->phone = !empty($phone) ? trim($phone) : '';
        $this->mail = !empty($mail) ? trim($mail) : '';
        $this->password1 = !empty($password1) ? trim($password1) : '';
        $this->password2 = !empty($password2) ? trim($password2) : '';
    }

    // Метод обрабатывает полученные данные на регистрацию
    public function check($link)
    {
        // Проверка на пустоту
        if (empty($this->name) || empty($this->phone) || empty($this->mail) || empty($this->password1) || empty($this->password2)) {   // Проверка на пустоту и вывод ошибки
            $_SESSION['errors'] = 'Все поля для ввода обязательны!';
            return false;
        }

        // Проверка имени на корректность - только латинские буквы и цифры
        if (!preg_match('/^[a-z0-9]+$/i', $this->name)) {  
            $_SESSION['errors'] = 'В имени допустимы только латинские буквы и цифры!';
            return false;
        }

        // Проверка почты на корректность
        if (!filter_var($this->mail, FILTER_VALIDATE_EMAIL)) {  
            $_SESSION['errors'] = 'Некорректный E-mail!';
            return false;
        }

        // Проверка пароля на совпадение с повторным вводом
        if ($this->password1 !== $this->password2) {    
            $_SESSION['errors'] = 'Пароль не совпадает!';
            return false;
        }

        // Проверка пароля на корректность - только латинские буквы и цифры
        if (!preg_match('/^[a-z0-9]+$/i', $this->password1)) {  
            $_SESSION['errors'] = 'Пароль должен состоять из латинских букв и цифр!';
            return false;
        }

        // Проверка пароля на условие  - не менее 6 символов
        if (6 > mb_strlen($this->password1)) {  
            $_SESSION['errors'] = 'Пароль должен быть больше 5 знаков!';
            return false;
        }

        $name = $this->name;
        $phone = $this->phone;
        $mail = $this->mail;
        $password = password_hash($this->password1, PASSWORD_DEFAULT);    

        // Проверяем на уникальность имени, телефона и почты
        $resultuser = mysqli_query($link, "SELECT * FROM users WHERE name = '$name' OR phone = '$phone' OR email = '$mail' "); 
        $user = mysqli_fetch_assoc($resultuser);

        // Если данные пользователя уникальны и не найдено совпаденией, сохраняем в БД нового пользователя
        if(empty($user)) {  
            $query = mysqli_query($link, "INSERT INTO `users` VALUES (NULL,'$name','$phone', '$mail', '$password')");
            $queryuser = mysqli_query($link, "SELECT * FROM `users` WHERE name='$name'");
            $resuluser = mysqli_fetch_assoc($queryuser);
            
            $_SESSION['auth'] = true;
            $_SESSION['id'] = $resuluser['id'];
            $_SESSION['name'] = $resuluser['name'];
            $_SESSION['phone'] = $resuluser['phone'];
            $_SESSION['email'] = $resuluser['email'];
        } else {
            $_SESSION['errors'] = "Пользователь с такими данными уже существует!";
        }
    }      
}