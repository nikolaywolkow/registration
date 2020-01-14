// Проверка e-mail на корректность.
function email_check(lang)
{
    var email=document.getElementById('email').value;
    var div_id="email_info";

    if(!email.match(/^[0-9a-z-\.]+\@[0-9a-z-]{2,}\.[a-z]{2,}$/i))
    {
        if(lang=='ru')
            document.getElementById(div_id).innerHTML = "E-mail указан не корректно! <br>";
        if(lang=='en')
            document.getElementById(div_id).innerHTML = "E-mail is incorrect! <br>";
    }
    else
        document.getElementById(div_id).innerHTML = "";
    
    disable_button();
}

// Проверка логина.
function login_check(lang) 
{
    var login=document.getElementById('login').value;
    var div_id="login_info";

    document.getElementById(div_id).innerHTML="";

    if(login.length<5)
    {
        if(lang=='ru')
            document.getElementById(div_id).innerHTML += "Логин должен быть не менее 5-ти символов! <br>"; 
        if(lang=='en')
            document.getElementById(div_id).innerHTML += "Login must be at least 5 characters! <br>"; 
    }                
        

    if(login[0].search( /[A-z]/g ))
    {
        if(lang=='ru')
            document.getElementById(div_id).innerHTML += "Логин должен начинаться с буквы! <br>"; 
        if(lang=='en')
            document.getElementById(div_id).innerHTML += "The first character of the login must be a letter! <br>"; 
    }

    if(!login.match(/^[a-z0-9]+$/i))
    {
        if(lang=='ru')
            document.getElementById(div_id).innerHTML += "Только латинские буквы и цифры* <br>"; 
        if(lang=='en')
            document.getElementById(div_id).innerHTML += "Latin letters and numbers only* <br>"; 
    } 
    
    disable_button();
}

// Проверка параметров пароля.
function password_check(lang)
{
    var password=document.getElementById('password').value;
    var div_id="pass_info";

    document.getElementById(div_id).innerHTML="";

    if(password.length<8)
    {
        if(lang=='ru')
            document.getElementById(div_id).innerHTML += "Пароль должен быть не менее 8-ми символов! <br>"; 
        if(lang=='en')
            document.getElementById(div_id).innerHTML += "Password must be at least 8 characters! <br>"; 
    }                
        
    if(!password.replace( /\D/g, ''))
    {
        if(lang=='ru')
            document.getElementById(div_id).innerHTML += "Пароль должен содержать минимум 1 цифру! <br>"; 
        if(lang=='en')
            document.getElementById(div_id).innerHTML += "Password must contain at least 1 digits! <br>"; 
    }
        
    if(isFinite(password))
    {
        if(lang=='ru')
            document.getElementById(div_id).innerHTML += "Пароль должен содержать буквы! <br>"; 
        if(lang=='en')
            document.getElementById(div_id).innerHTML += "Password must contain letters! <br>"; 
    }
        
    disable_button();
}

// Проверка повторного пароля на равенство.
function password_repit_check(lang) 
{
    var valueX = document.getElementById('password').value;
    var valueY = document.getElementById('pass_repit').value;
    var div_id="pass2_info";

    if (valueX != valueY)
    {
        if(lang=='ru')
            document.getElementById(div_id).innerHTML = "Пароли не совпадают!";
        if(lang=='en')
            document.getElementById(div_id).innerHTML = "Passwords do not match!";
    }
    
    if (valueX == valueY)
        document.getElementById(div_id).innerHTML = "";
    
    disable_button();
}

// Проверяет есть ли замечания пользователю по вводимым полям, если да, то кнопка не активна.
function disable_button()
{
    var active_button=true;
    let div_id = ["login_info", "pass_info", "pass2_info",'email_info'];

    for(var i=0;i<div_id.length;i++)
    {
        if(document.getElementById(div_id[i]).innerHTML!='')
        {
            active_button=false;
            break;
        }
    }
    
    if(active_button)
        document.getElementById("button").disabled = false;
    else
        document.getElementById("button").disabled = true;
}