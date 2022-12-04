<?php

class users
{

    public function actionIndex() 
    {
      // Рендер главной страницы портала
      view::render('users','index');        
    }
    public function actionClose()
    {
        unset($_SESSION['user']);
        header ('Location: /');
        //view::render('login','index');

    }

    public function actionEdit()
    {
        $array = [];
        $objPDO = new DataBase();
        $db = $objPDO->getConnection();
        $sql = "SELECT * FROM Users";
        $result = $db->prepare($sql);
        $result->execute();

        while ($row = $result->fetch())
        {
            $array = $row;
        }

        echo json_encode($array);

    }

    public function actionUpdate()
    {
        //получаем данные от формы
        //записываем в массив
        $data = [
            "id"=>$_POST['id'],
            "username"=>$_POST['username'],
            "phone"=>$_POST['phone'],
            "email"=>$_POST['email'],
            "pass"=>$_POST['pass']
        ];

        //запускаем валидация пришедших данных
        $Validation = $this->Validation($data);

        $objPDO = new DataBase();
        $db = $objPDO->getConnection();

        if ($Validation === null)
        {
            if (($data['pass'] !== "**********") AND ($data['pass'] !== ""))
            {
                $pass = $this->md5pass($data['pass']);

                //делаем update
                $sql = ("UPDATE Users SET 
                                            Names=:Names, 
                                            Phone=:Phone, 
                                            Email=:Email, 
                                            Password=:Password
                                            WHERE ID=:ID");
                $result = $db->prepare($sql);
                $result->bindParam(':ID',         $data['id'],             PDO::PARAM_STR);
                $result->bindParam(':Names',      $data['username'],             PDO::PARAM_STR);
                $result->bindParam(':Phone',      $data['phone'],                PDO::PARAM_STR);
                $result->bindParam(':Email',      $data['email'],                PDO::PARAM_STR);
                $result->bindParam(':Password',   $pass, PDO::PARAM_STR);
            }
            else
            {
                //делаем update
                $sql = ("UPDATE Users SET 
                                            Names=:Names, 
                                            Phone=:Phone, 
                                            Email=:Email 
                                            WHERE ID=:ID");
                $result = $db->prepare($sql);
                $result->bindParam(':ID',         $data['id'],             PDO::PARAM_STR);
                $result->bindParam(':Names',      $data['username'],             PDO::PARAM_STR);
                $result->bindParam(':Phone',      $data['phone'],                PDO::PARAM_STR);
                $result->bindParam(':Email',      $data['email'],                PDO::PARAM_STR);
            }

            $result->execute();

            $array = ['Error'=>'false','Text'=>'users'];

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

        if (!is_string($data['pass']) ||
            $data['pass'] == '' ||
            strlen($data['pass']) < 8
        )
        {
            $errors['Пароль'] = 'Пароль должен быть не меньше 8 символов';
        }

        if( count($errors) != 0)
        {
            return ($errors);
        }
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
