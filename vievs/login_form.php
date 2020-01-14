<?php
//Запрет прямого доступа к файлу.
if(!isset($lang))
{
    header("Location: /?page=login");
    exit;
}
?>
<form method="POST">
    <div class="loginform">
        <div class="title">	
            <?=$lang=='ru'?'Вход':''?> 
            <?=$lang=='en'?'log in':'' ?>
        </div>
        <div class="instruction">
        <?=$lang=='ru'?'Для входа заполните все поля формы.<br>Нет аккаунта? <a href="?page=registration">Зарегистрируйтесь</a>':''?> 
        <?=$lang=='en'?'Fill in all fields of the form.<br>No account? <a href="?page=registration">Register now</a>':'' ?>
        </div>
        <div class="input-form">
            <input required name="login" id='login' oninput="login_check('<?=$lang?>');" type="text" class="input" placeholder="<?=$lang=='ru'?'Логин':''?> <?=$lang=='en'?'Login':'' ?>" value="<?= $_POST['login']?>">
            <i class="fas"><img src='img/user.png'></i>
        </div>

        <div class='form_info' id='login_info'></div>

        <div class="input-form">
            <input required name="password" id='password'  oninput="password_check('<?=$lang?>');" type="password" class="input" placeholder="<?=$lang=='ru'?'Пароль':''?> <?=$lang=='en'?'Password':'' ?>" value="<?= $_POST['password']?>">
            <i class="fas"><img src='img/pass.png'></i>
        </div>

        <div class='form_info' id='pass_info'></div>

        <div id='pass2_info'></div>
        <div id='email_info'></div>

        <div class='form_info' id='php_message'>

        <?php
        // Если есть сообщения, то вывести их.
        if(count($error_message)>0)
            foreach($error_message as $elem) 
                echo $elem.'<br>';    
        ?>
        
        </div>

        <div class="center">
            <input id='button' name='autorisation' class="btn" value="<?=$lang=='ru'?'отправить':''?> <?=$lang=='en'?'submit':'' ?>" type="submit"  />
        </div>
    </div>
</form>