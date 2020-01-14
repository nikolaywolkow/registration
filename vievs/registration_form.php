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
            <?=$lang=='ru'?'Регистрация':''?>
            <?=$lang=='en'?'Registration':'' ?>
        </div>
        <div class="instruction">
        <?=$lang=='ru'?'Для создания аккаунта заполните все поля формы.<br> Есть аккаунт? <a href="?page=login">войдите</a>.':''?> 
        <?=$lang=='en'?'To create an account, fill in all fields of the form.<br> Do you have an account? <a href="?page=login"> log in </a>.':'' ?>
        </div>
        <div class="input-form">
            <input required name="login" id='login' oninput="login_check('<?=$lang?>');" type="text" class="input" placeholder="<?=$lang=='ru'?'Логин':''?> <?=$lang=='en'?'Login':'' ?>" value="<?= $_POST['login']?>">
            <i class="fas"><img src='img/user.png'></i>
        </div>

        <div class='form_info' id='login_info'></div>

        <div class="input-form">
            <input required name="email"  id='email' oninput="email_check('<?=$lang?>');" type="text" class="input" placeholder="E-mail" value="<?= $_POST['email']?>">
            <i class="fas"><img src='img/email.png'></i>
        </div>

        <div class='form_info' id='email_info'></div>

        <div class="input-form">
            <input required name="password" id='password' oninput="password_check('<?=$lang?>');" type="password" class="input" placeholder="<?=$lang=='ru'?'Пароль':''?> <?=$lang=='en'?'Password':'' ?>" value="<?= $_POST['password']?>">
            <i class="fas"><img src='img/pass.png'></i>
        </div>

        <div class='form_info' id='pass_info'></div>

        <div class="input-form">
            <input required name="pass_repit" id='pass_repit' oninput="password_repit_check('<?=$lang?>');" type="password" class="input" placeholder="<?=$lang=='ru'?'Повторите пароль':''?> <?=$lang=='en'?'Repeat password':'' ?>" value="<?= $_POST['pass_repit']?>">
            <i class="fas"><img src='img/pass.png'></i>
        </div>

        <div class='form_info' id='pass2_info'></div>

        <div class='form_info' id='php_message'>

        <?php
        if(count($error_message)>0)
            foreach($error_message as $elem) 
                echo $elem.'<br>';    
        ?>
        
        </div>

        <div class="center">
            <input id='button' name='registration' class="btn" value="<?=$lang=='ru'?'отправить':''?> <?=$lang=='en'?'submit':'' ?>" type="submit"  />
        </div>
    </div>
</form>



