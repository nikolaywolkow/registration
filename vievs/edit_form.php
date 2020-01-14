<?php
//Запрет прямого доступа к файлу.
if(!isset($lang))
{
    header("Location: /?page=login");
    exit;
}

    
?>
<form method="POST" name='upload' ENCTYPE='multipart/form-data'>
    <div class="editform">


        <div class='instruction'>
            <?=$lang=='ru'?'Заполните нужные поля для редактирования.<br>Остальные оставьте пустыми.':''?>
            <?=$lang=='en'?'Fill in the fields for editing.<br>Leave the rest empty.':''?>
        </div>

        <div class="input-form">
            <input name="name" id='name' oninput="text_only('<?=$lang?>','name','name_info');" type="text" class="input" placeholder="<?=$lang=='ru'?'Имя':''?> <?=$lang=='en'?'Name':'' ?>" value="<?= $_POST['name']?>">
            <i class="fas"><img src='img/user.png'></i>
        </div>

        <div class='form_info' id='name_info'></div>

        <div class="input-form">
            <input name="surname" id='surname'  oninput="text_only('<?=$lang?>','surname','surname_info');" type="text" class="input" placeholder="<?=$lang=='ru'?'Фамилия':''?> <?=$lang=='en'?'Surname':'' ?>" value="<?= $_POST['surname']?>">
            <i class="fas"><img src='img/user.png'></i>
        </div>

        <div class='form_info' id='surname_info'></div>

        <div class="input-form">
            <input name="email"  id='email' oninput="email_check('<?=$lang?>');" type="text" class="input" placeholder="E-mail" value="<?= $_POST['email']?>">
            <i class="fas"><img src='img/email.png'></i>
        </div>

        <div class="load_img"><p>
            <?=$lang=='ru'?'Загрузите ваше фото':''?>
            <?=$lang=='en'?'Upload your photo.':''?></p>
            <input name='upload' type='file' accept="image/jpeg">
        </div>

        <div class='form_info' id='email_info'></div>
        <div class='form_info' id='php_message'>

        <?php
        // Если есть сообщения, то вывести их.
        if(count($error_message)>0)
            foreach($error_message as $elem) 
                echo $elem.'<br>'; 
        echo $error_img   
        ?>
        
        </div>

        <div class="center">
            <input id='button' name='content' class="btn" value="<?=$lang=='ru'?'отправить':''?> <?=$lang=='en'?'submit':'' ?>" type="submit"  />
        </div>
    </div>
