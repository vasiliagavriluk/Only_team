<?php
class login {

    function __construct() {
        

        
    }
    
    public function actionIndex() 
    {
         // Рендер на страницу Авторизации
            view::render('login','index');        
        
    }

    private function Captcha($captcha)
    {
        $secretKey = "6LdrFVIjAAAAAOMmeFq21bo04PVQxBxZcEfb4juq";
        $ip = $_SERVER['REMOTE_ADDR'];
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify".
                                        "?secret=".$secretKey.
                                        "&response=".$captcha.
                                        "&remoteip=".$ip);
        $responseKeys = json_decode($response,true);
        if(intval($responseKeys["success"]) !== 1) {
            return true;
        }
    }


    public function actionAut()
    {
        $login = $_POST['login'];
        $password = $_POST['password'];
        $captcha = $_POST['capcha'];

        try
        {
            if($this->Captcha($captcha) == true)
            {
                $pass = $this->md5pass($password);
                $objPDO = new DataBase();
                $db = $objPDO->getConnection();

                if (filter_var($login, FILTER_VALIDATE_EMAIL))
                {
                    $sql = 'SELECT * FROM Users WHERE Email = :login AND Password = :password';
                }
                else
                {
                    $sql = 'SELECT * FROM Users WHERE Phone = :login AND Password = :password';
                }

                $result = $db->prepare($sql);
                $result->bindParam(':login',    $login,    PDO::PARAM_STR);
                $result->bindParam(':password', $pass, PDO::PARAM_STR);
                $result->execute();

                $user = $result->fetch();

                if ($user) {
                    $_SESSION['user'] = $user['Names'];

                    $array = ['Error'=>'false','Text'=>'users'];
                }
                else
                {
                    $array = ['Error'=>'true','Text'=>'Вы ввели неправильный логин/пароль'];
                }

                echo json_encode($array);

            }

        
        } 
        catch (Exception $ex) 
        {
           echo($ex->getMessage());         
        }

        
    }

    function md5pass($pass)
    {
            $password = $pass;            // Сам пароль
            $hash = md5($password);            // Хешируем первоначальный пароль
            $salt = "yaWqttrgh435dafITVOkZMhTXSGqOVM6cnRL1DkJ";            // Соль
            $saltedHash = md5($hash . $salt); // Складываем старый хеш с солью и пропускаем через функцию md5()
            return $saltedHash;
    }
    

    

}
