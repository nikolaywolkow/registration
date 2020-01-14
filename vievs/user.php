<?php
// Если пользователь не авторизован, отправить его на авторизация.
if(!isset($_SESSION['user']))
{
    header("Location: /?page=login");
    exit;
}

if($_FILES['upload']['size'] != 0)
{
    $type='img';

    require_once('class/image.php');
    $img = new image();

    // Формируем уникальное имя изображения, по времени и случайному числу.
    $nameIMG = md5(microtime() . rand(0, 1000000));

    /* 
        Указывается: 
        - Имя изображения.
        - Каталог для сохранения изображения.
        - До какого размера его порпорционально сжать, относительно максимальной стороны.

          (для параметра 300 пример: 600 x 300 сжимается до 300 x 150) 

          Плюсами данного подхода является: 
          Экономия памяти сервера и экономия времени пользователя.
          Вместо того, чтобы вывести сооощение о превышении размера,
          изображение будет пропорционально уменьшенно. 
          Но не более (5000x5000) чтоб не создавать большой нагрузки.

          Все проверки на корректность файла внутри метода.
    */
    $error_img=$img->loadimg($nameIMG,'user_img/',300,$lang,$nameIMG);
    if(!$error_img)
        $nameIMG.='.'.strtolower(substr(strrchr($_FILES['upload']['name'], '.'), 1));// Расширение файла.
    else
        $nameIMG=null; // При нарушении заданных параметров, не записывать изображение.
  
}

$error_message=$login->edit_user($_POST['name'],$_POST['surname'],$_POST['email'],$lang,$nameIMG);

$user=$_SESSION['user'];
?>

<script src="js\edit_data.js"></script>
<div class="edit_menu">

    <table>
        <td valign="top">
            <h1><?=$user['login']?></h1>

            
            <img class='avatar' src='<?=$user['logo']!=null?'user_img/'.$user['logo']:'img/no_photo.jpg'?>'>

            <div class="user_data">
                <div class="d-tr">
                    <div class="param"><?=$lang=='ru'?'Имя':''?><?=$lang=='en'?'Name':''?>:</div>
                    <div class="param data"><?=$user['name']!=null?$user['name']:'' ?></div>
                </div>

                <div class="d-tr">
                    <div class="param"><?=$lang=='ru'?'Фамилия':''?><?=$lang=='en'?'Surname':''?>:</div>
                    <div class="param data"><?=$user['surname']!=null?$user['surname']:'' ?></div>
                </div>

                <div class="d-tr">
                    <div class="param">E-mail:</div>
                    <div class="param data"><?=$user['email'] ?></div>
                </div>   
            </div>

            <a id='edit' class='edit' onclick="visible('redaction','edit','<?=$lang?>')"><?=$lang=='ru'?'Редактировать данные страницы':''?><?=$lang=='en'?'Edit Page Data':''?></a>
        </td>

        <td>
            <div id='redaction' class='invisible' >
                <?php include('vievs\edit_form.php'); ?>
            </div>
        </td>
        
    </table>

</div>


<?php 
// Если данные были отправлены показать меню/
if(isset($_POST['name']) || isset($_POST['Surname']) || isset($_POST['email'])){  ?>

<script type="text/javascript">
    window.onload = visible('redaction','edit','<?=$lang?>');
</script>

<?php } ?>