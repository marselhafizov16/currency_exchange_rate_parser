<?php
error_reporting(-1);

class Authorization 
{
    public $phoneormail;
    public $password;

    public function __construct($phoneormail, $password)
    {
        $this->phoneormail = !empty($phoneormail) ? trim($phoneormail) : '';
        $this->password = !empty($password) ? trim($password) : '';
    }

    public function check($link, $token)
    {
        if (empty($this->phoneormail) || empty($this->password)) {   // Проверка на пустоту и вывод ошибки
            $_SESSION['errors'] = 'Все поля для ввода обязательны!';
            return false;
        }

        define('SMARTCAPTCHA_SERVER_KEY', 'ysc2_M1cbdg8RnO0EVDAwfqqCobhL2m2nYUjUYQCb8XpScd749a3e');  // Проверка ЯндексКапчи

        function check_captcha($token) {
            $ch = curl_init();
            $args = http_build_query([
                "secret" => SMARTCAPTCHA_SERVER_KEY,
                "token" => $token,
                "ip" => $_SERVER['REMOTE_ADDR'], // Нужно передать IP пользователя.
                                                // Как правильно получить IP зависит от вашего прокси.
            ]);
            curl_setopt($ch, CURLOPT_URL, "https://smartcaptcha.yandexcloud.net/validate?$args");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 1);

            $server_output = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpcode !== 200) {
                echo "Allow access due to an error: code=$httpcode; message=$server_output\n";
                return true;
            }
            $resp = json_decode($server_output);
            return $resp->status === "ok";
        }

        $token = $_POST['smart-token'];
        if (check_captcha($token)) {
            echo "Passed\n";
        } else {
            $_SESSION['errors'] = "Проверка капчи обязательна!\n";
            return false;
        }


        $resultuser = mysqli_query($link, "SELECT * FROM users WHERE phone = '$this->phoneormail' OR mail = '$this->phoneormail' "); // Проверка на повторение телефона/меила
        $user = mysqli_fetch_assoc($resultuser);  

        if(!empty($user) && password_verify($this->password, $user['password'])) {  //проверяем на наличие такого пользователя и совпадение пароля
            $_SESSION['auth'] = true;
            $_SESSION['id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['phone'] = $user['phone'];
            $_SESSION['mail'] = $user['mail'];
            $_SESSION['success'] = 'Вы успешно авторизовались!';
        } else {
            $_SESSION['errors'] = "Некорректный телефон/mail или пароль!";
        }
    }
}