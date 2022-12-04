<?php
class registration {

    function __construct() {
        

        
    }
    
    public function actionIndex() 
    {
         // Рендер на страницу регистрации
            view::render('registration','index');        
        
    }

    public function actionReg()
    {
        //получаем данные от формы
        //записываем в массив
        $data = [
            "username"=>$_POST['username'],
            "phone"=>$_POST['phone'],
            "email"=>$_POST['email'],
            "pass"=>$_POST['pass'],
            "repeat_pass"=>$_POST['repeat_pass'],
        ];
        //запускаем валидация пришедших данных
        //а так же проверяем пользователь есть в базе или нет
        $Validation = $this->Validation($data);

        if ($Validation === null)
        {
            //если пользователя нет тогда
            //и он ввел все данные правильно
            // добавление в базу
            $pass = $this->md5pass($data['pass']);

            $objPDO = new DataBase();
            $db = $objPDO->getConnection();
            $sql = 'INSERT INTO `Users` (Names, Phone, Email, Password) 
                    VALUES (:Names, :Phone, :Email, :Password)';

            $result = $db->prepare($sql);
            $result->bindParam(':Names',      $data['username'],             PDO::PARAM_STR);
            $result->bindParam(':Phone',      $data['phone'],                PDO::PARAM_STR);
            $result->bindParam(':Email',      $data['email'],                PDO::PARAM_STR);
            $result->bindParam(':Password',   $pass, PDO::PARAM_STR);
            $result->execute();

            $array = ['Error'=>'false','Text'=>'login'];

        }
        else
        {
            //выдаем ошибку
            $array = ['Error'=>'true','Text'=>$Validation];              
        }

        echo json_encode($array);
    }

    private function Validation($data)
    {
        $errors = [];

        if (!preg_match('/^[a-zA-Z]+$/', $data['username']))
        {
            $errors['Имя/Логин'] = 'Только буквы латинские';
        }
        elseif ($this->Searchuser_bd($data['username']) !== 0)
        {
            $errors['Имя/Логин'] = 'Такое имя уже есть, введите другое';
        }

        if(!preg_match("/^[0-9]{10,10}+$/", $data['phone']))
        {
            $errors['Телефон'] = "Телефон задан в неверном формате Пример: 9991234567";
        }
        elseif ($this->Searchuser_bd($data['phone'],'Phone') !== 0)
        {
            $errors['Телефон'] = 'Такой телефон уже есть, введите другое';
        }

        if (filter_var($data['email'], FILTER_VALIDATE_EMAIL) != $data['email'])
        {
            $errors['Email'] = "E-mail адрес не правильный";
        }
        elseif ($this->Searchuser_bd($data['email'],'Email') !== 0)
        {
            $errors['Email'] = 'Такое email уже есть, введите другое';
        }

        if (!is_string($data['pass']) ||
            $data['pass'] == '' ||
            strlen($data['pass']) < 8
        )
        {
            $errors['Пароль'] = 'Пароль должен быть не меньше 8 символов';
        }


        if($data['pass'] !== $data['repeat_pass'])
        {
            $errors['repeat_password'] = 'Пароли не совподают';
        }

        if( count($errors) != 0)
        {
            return ($errors);
        }
    }

    private function Searchuser_bd($data, $bd = 'Names')
    {
        $data = trim($data);
        $count = 1;
        $result_count = true;
        $objPDO = new DataBase();
        $db = $objPDO->getConnection();
        $sql = "SELECT COUNT(*) FROM `Users` WHERE `$bd` = :NameLocal";
        $result = $db->prepare($sql);
        $result->bindParam(':NameLocal', $data, PDO::PARAM_STR);
        $result->execute();

        while ($row = $result->fetch())
        {
            $count = $row['COUNT(*)'];
        }
        if($count == 0)
        {
            $result_count = 0;
        }
        return $result_count;
    }

    private function md5pass($pass)
    {
        $password = $pass;                                    // Сам пароль
        $hash = md5($password);                               // Хешируем первоначальный пароль
        $salt = "yaWqttrgh435dafITVOkZMhTXSGqOVM6cnRL1DkJ";   // Соль
        $saltedHash = md5($hash . $salt);                     // Складываем старый хеш с солью и
                                                              // пропускаем через функцию md5()
        return $saltedHash;
    }
}