<?php
/*
В данном классе имеются публичные методы:

registration_user()   // Регистрация нового пользователя. В случае удачной регистрации авторизирует пользователя.
login_user()          // Авторизация пользоватедя. В случае удачной авторизации, создаёт массив в сесии с данными пользоватля, записывая в БД.
edit_user()           // Редактирует данные пользователя.

Данные методы, при получении некорректных данных, возвращают массив ошибок.
*/
class registration
{
    function __construct()
    {
        require_once 'class\conectDB.php';
        require_once 'class\workDB.php';
        $this->db=new workDB($host, $user, $password, $database);
    }

    public function registration_user($login,$email,$password,$repPassword,$lang)
    {
        // Проверка на инекции выполняется регулярным выражением.
        // Запрешены: все виды кавычек, пробелы, скобки, спецсимволы и т.д.
        // Кроме пароля, так как md5() сломает инекцию.

        $message=array();
        
        if($password!=$repPassword)
        {
            if($lang=='ru')
                array_push($message, 'Пароли не совпадают!.');
            if($lang=='en')
                array_push($message, 'Passwords do not match!');
        }

        if($mes=$this->check_email($email,$lang))
            array_push($message, $mes);

        if($mes=$this->isset_email($email,$lang))
            array_push($message, $mes);

        $mes=$this->check_login($login, $lang);
        if($mes)array_push($message, $mes);

        if($this->isset_login($login))
        {
            if($lang=='ru')
                array_push($message, "Логин <u>$login</u> занят. Придумайте другой.");
            if($lang=='en')
                array_push($message, "login <u>$login</u> is busy. Come up with another.");
            
            return $message;
        }

        $mes=$this->check_pass($password, $lang);
        if($mes)array_push($message, $mes);
        
        if(count($message)>0)
            return $message;
        // Если нет сообщений о некорректном вводе, то производит запись в бд

        $this->db->query("INSERT INTO `users` (`id`, `login`, `password`, `email`, `name`, `surname`, `logo`, `date_registration`) 
        VALUES (NULL, '$login', '".md5($password)."', '$email', NULL, NULL, NULL, '".date("Y-m-d H:i:s")."');");

        // Если регистрация прошла успешно, то авторизировать пользователя.
        $this->login_user($login, $password, $email);

    }

    public function login_user($login,$password,$lang)
    {
        $message=array();
        
        // Проверка на корректность логина, и на инъекции.
        $mes=$this->check_login($login, $lang);
        if($mes)array_push($message, $mes);

        $mes=$this->check_pass($password, $lang);
        if($mes)array_push($message, $mes);
        
        if(count($message)>0)
            return $message;

        if(!$this->isset_login($login))
        {
            if($lang=='ru')
                array_push($message, "Пользователь <u>$login</u> не зарегистрирован!");
            if($lang=='en')
                array_push($message, "User <u>$login</u> is not registered!");

            return $message;
        }
            
        $user=$this->db->query("SELECT * FROM `users` WHERE login='$login'");
        $user=mysqli_fetch_array($user);

        // Проверяем пароль.
        if($user['password']!=md5($password))
        {
            if($lang=='ru')
                array_push($message, 'Пароль указан неверно, попробуйте ещё раз.');
            if($lang=='en')
                array_push($message, 'The password is incorrect, try again.');
        }  
        else
            $_SESSION['user']=$user;
        

        return $message;//подумать
    }

    public function edit_user($name,$surname,$email,$lang,$image=null)
    {
        $message=array();
        $id=$_SESSION['user']['id'];
        $login=$_SESSION['user']['login'];

        if($name=='' && $surname='' && $email='')
            return;

        //Если значение пустое или не изменилось то пропустить его запись.
        if($name!='' && $name!=$_SESSION['user']['name'])
        {
            if($mes=$this->text_only($name,$lang))
                array_push($message, $mes);
            else
                $this->update_data($id,'name',$name);
        }

        if($surname!='' && $surname!=$_SESSION['user']['surname'])
        {
            if($mes=$this->text_only($surname,$lang))
                array_push($message, $mes);
            else
                $this->update_data($id,'surname',$surname);
        }

        if($email!='' && $email!=$_SESSION['user']['email'])
        {
            if($mes=$this->isset_email($email,$lang))
                array_push($message, $mes);
            else
            if($mes=$this->check_email($email,$lang))
                array_push($message, $mes);
            else
                $this->update_data($id,'email',$email);
        }
        if($image!=null)
            $this->update_data($id,'logo',$image); 

        $user=$this->db->query("SELECT * FROM `users` WHERE login='$login'");
        $_SESSION['user']=mysqli_fetch_array($user);

        if(count($message)>0)
            return $message;
    }

    // Проверка на наличае почты в бд.
    private function isset_email($email,$lang)
    {
        $result=$this->db->query("SELECT * FROM `users` WHERE email='$email'");
        $result=mysqli_fetch_array($result);

        if($result['email']!=null)
        {
            if($lang=='ru')
                return('E-mail <u>'.$email.'</u> уже используется!');
            if($lang=='en')
                return('E-mail <u>'.$email.'</u> is busy!');
        }
    }
    // Проверка на наличае логина в бд.
    private function isset_login($login)
    {
        $user=$this->db->query("SELECT * FROM `users` WHERE login='$login'");
        $user=mysqli_fetch_array($user);

        if($user['login']==null)
            return false;
        else
            return true;
    }

    // Проверка пароля на длинну и корректность.
    private function check_pass($password,$lang)
    {
        if(is_numeric($password))
        {
            if($lang=='ru')
                return 'Пароль должен содержать буквы!';
            if($lang=='en')
                return 'Password must contain letters!';
        }

        if(strlen($password)<8)
        {
            if($lang=='ru')
                return 'Пароль <u>'.$password.'</u> не корректен!';
            if($lang=='en')
                return 'Password <u>'.$password.'</u> incorrect!';
        }

        return false;
    }

    // Проверка логина на корректность.
    private function check_login($login,$lang)
    {
        if(!preg_match("/[A-z]/i",$login[0]))
        {
            if($lang=='ru')
                return 'Логин должен начинаться с буквы!';
            if($lang=='en')
                return 'The first character of the login must be a letter!';    
        }

        if(!preg_match("/^[a-z\d]{1}[0-9a-z]*[0-9a-z]{1}$/i",$login) || strlen($login)<5)
        {
            if($lang=='ru')
                return 'Логин <u>'.$login.'</u> не некорректен!';
            if($lang=='en')
                return 'Login <u>'.$login.'</u> incorrect!';              
        }
    }

    // Проверка почты на корректность.
    private function check_email($email,$lang)
    {
        if(!preg_match("/^[0-9a-z-\.]+\@[0-9a-z-]{2,}\.[a-z]{2,}$/i",$email))
        {
            if($lang=='ru')
                return ('E-mail <u>'.$email.'</u> не корректен!');
            if($lang=='en')
                return('E-mail <u>'.$email.'</u> incorrect!');
        }
    }

    // Проверка строки на то что в ней только буквы.
    private function text_only($text,$lang)
    {
        echo $text;
        if(strlen($text)<3)
        {
            if($lang=='ru')
                return 'Минимальная длина 2 буквы.';
            if($lang=='en')
                return 'The minimum length is 2 letters.'; 
        }

        if(!preg_match("/^[-a-zа-яё\\s]+$/iu",$text))
        {
            if($lang=='ru')
                return 'Допускаются только буквы!';
            if($lang=='en')
                return 'Only letters!'; 
        }
    }

    // Редактирование таблицы users по указанному полю, для указанного id.
    private function update_data($id,$pole,$value)
    {
        $result=$this->db->query("UPDATE `users` SET `$pole` = '$value' WHERE `users`.`id` = $id;");
    }
    
}