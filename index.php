<?php

session_start();

if($_GET['act']=='exit')
  unset($_SESSION['user']);

// Для нового пользователя по язык умолчаию Русский.
if(!isset($_SESSION['language']))
  $_SESSION['language']='ru';

if(isset($_GET['ln']))
{
  // Если указан имеющийся в системе язык, то устанавливаем его, иначе остается язык умолчаию.
  if($_GET['ln']=='ru' || $_GET['ln']=='en')
    $_SESSION['language']=$_GET['ln'];
}
  

if(isset($_GET['page']))
  $url = 'page='.$_GET['page'];

$lang=$_SESSION['language'];

require_once('class\registration.php');
$login=new registration();

// Авторизация
if($_GET['page']=='login')
{
  if($_POST['autorisation'] && $_POST['login']!='' && $_POST['password']!='')
  {
    // Проверка на иньекции внутри класса!
    $error_message=$login->login_user($_POST['login'],$_POST['password'],$lang);
  }

  // Если авторизация прошла успешно, и был создан массив 'user' в сесии, то перенаправить на страницу пользователя.
  if(isset($_SESSION['user']))
  {
    header("Location: ?page=user");
    exit;
  }
}

// Регистрация
if($_GET['page']=='registration')
{
  if($_POST['registration'] && $_POST['login']!='' && $_POST['email']!='' && $_POST['password']!='' && $_POST['pass_repit']!='')
  {
    // Проверка в том числе и на иньекции внутри класса!
    $error_message=$login->registration_user($_POST['login'],$_POST['email'],$_POST['password'],$_POST['pass_repit'],$lang);

    // После успешной регистрации перейти на страницу пользователя.
    if(count($error_message)==0)
    {
      header("Location: ?page=user");
      exit;
    }
  }
}


// Если на страницу 'user', попал не зарегистрированный пользователь перенаправить его.
if($_GET['page']=='user')
{
  if(!isset($_SESSION['user']))
  {
    header("Location: ?page=login");
    exit;
  }
}

?>
<!DOCTYPE html>
<html lang="<?=$lang?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Регистрация</title>
    <link rel="stylesheet" type="text/css" href="css/form.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <script src="js\registration_form.js"></script>
</head>
<nav class="top-menu">
  <ul class="menu-main">

    <?php 
    //Если пользователь не авторизирован отображать регистрацию/авторизацию,иначе отобразить кнопку выход.
    if(!isset($_SESSION['user'])) { ?>
      <li><a class='passiv top_a'  href="?page=registration"><?=$lang=='ru'?'Регистрация':''?> <?=$lang=='en'?'Registration':'' ?></a></li>
      <li><a class='passiv top_a'  href="?page=login"><?=$lang=='ru'?'Вход':''?> <?=$lang=='en'?'Log in':'' ?></a></li>
    <?php }
    else
    {
      ?>
      <li><a class='passiv top_a'  href="?act=exit"><?=$lang=='ru'?'Выход':''?> <?=$lang=='en'?'Exit':'' ?></a></li>
    <?php } ?>

    <li><a class='<?=($lang=='ru'?'active':'passiv')?>' href="?<?=$url?>&ln=ru">RU</a></li>
    <li><a class='<?=($lang=='en'?'active':'passiv')?>' href="?<?=$url?>&ln=en">EN</a></li>
  </ul>
</nav>

<body>
<?php 

if($_GET['page']=='registration')
    include('vievs\registration_form.php'); 

if($_GET['page']=='login')
    include('vievs\login_form.php'); 

if($_GET['page']=='user')
    include('vievs\user.php'); 

?>
</body>

</html>