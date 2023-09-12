<?php
namespace classes;

class Authorization 
{
    public $phoneormail;
    public $password;

    public function __construct($phoneormail, $password)
    {
        $this->phoneormail = !empty($phoneormail) ? trim($phoneormail) : '';
        $this->password = !empty($password) ? trim($password) : '';
    }

    // Метод проверяет введенные пользователем данные.
    public function check($link)
    {
        // Проверка на пустоту и вывод ошибки
        if (empty($this->phoneormail) || empty($this->password)) {   
            $_SESSION['errors'] = 'Все поля для ввода обязательны!';
            return false;
        }

        // Проверка на повторение телефона/почты
        $resultuser = mysqli_query($link, "SELECT * FROM users WHERE phone = '$this->phoneormail' OR email = '$this->phoneormail' "); 
        $user = mysqli_fetch_assoc($resultuser);  

        //проверяем на наличие такого пользователя и совпадение пароля
        if(!empty($user) && password_verify($this->password, $user['password'])) {  
            $_SESSION['auth'] = true;
            $_SESSION['id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['phone'] = $user['phone'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['success'] = 'Вы успешно авторизовались!';
        } else {
            $_SESSION['errors'] = "Некорректный телефон/mail или пароль!";
        }
    }
}