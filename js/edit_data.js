function visible(id,id_message,lang)
{
    if(document.getElementById(id).className=='invisible')
    {
        document.getElementById(id).className='visible';

        if(lang=='ru')
            document.getElementById(id_message).innerHTML='Скрыть';
        if(lang=='en')
            document.getElementById(id_message).innerHTML='Hide';

        return;
    }
        
    if(document.getElementById(id).className=='visible')
    {
        document.getElementById(id).className='invisible';
        
        if(lang=='ru')
            document.getElementById(id_message).innerHTML='Редактировать данные страницы';
        if(lang=='en')
            document.getElementById(id_message).innerHTML='Edit Page Data';

        return;
    }
        
}

// Провекра строки на коррекность имени/фамилии.
function text_only(lang,value,info)
{
    var text=document.getElementById(value).value;

    if(!text.match(/^[a-zа-яё]+$/i))
    {
        if(lang=='ru')
            document.getElementById(info).innerHTML = "Допускаются только буквы!"; 
        if(lang=='en')
            document.getElementById(info).innerHTML = "Only letters!"; 
    }
    else
    document.getElementById(info).innerHTML = ""; 
}