<?php
/*

*/
class image
{
    function __construct()
    {
        
    }
    function loadimg($nameIMG,$uploaddir,$resize = 0,$lang)
    {
        // Список разрешонных расширений.
        $White_list=array(".gif", ".jpeg", ".jpg", ".png");

        $extension = '.'.strtolower(substr(strrchr($_FILES['upload']['name'], '.'), 1));
        if(!in_array($extension,$White_list))
        {
            if($lang=='ru')
                return 'Допускаются файлы с расширениями: gif, jpg, png';
            if($lang=='en')
                return 'Files with extensions are allowed: gif, jpg, png';
        }
            
        $apend = ($nameIMG . $extension); // Имя, которое будет присвоенно изображению.
        $uploadfile = "$uploaddir$apend"; // Каталог + имя.

        // Проверка размера.
        if ($_FILES['upload']['size'] == 0 || $_FILES['userfile']['size'] >= 20480000) 
        {
            if($lang=='ru')
                return "Размер файла не должен превышать 20 m";
            if($lang=='en')
                return "File size must not exceed 20 m";
        }
            
        // Процесс загрузки изображения
        if (!move_uploaded_file($_FILES['upload']['tmp_name'], $uploadfile))
        {
            if($lang=='ru')
                return "Ошибка загрузки файла, попробуйте еще раз.";
            if($lang=='en')
                return "Error loading file, try again."; 
        }
            

        $size = getimagesize($uploadfile); // Получение размеров изображения.
        if ($size[0] > 5000 || $size[1] > 5000)
        {
            if($lang=='ru')
                return "Размер изображения превышает допустимые нормы (5000 x 5000)";
            if($lang=='en')
                return "Image size exceeds acceptable standards (5000 x 5000)";
        }
            
        
        return $this->resize($uploadfile,$resize, $lang);
        return $nameIMG;
    }

    private function resize($image, $size, $lang)
    {
        list($w_i, $h_i, $type) = getimagesize($image); // Получаем размеры и тип изображения (число)
        $types = array("", "gif", "jpeg", "png"); // Массив с типами изображений.

        $ext = $types[$type]; // Зная "числовой" тип изображения, узнаём название типа.
        if (!$ext) // Выводим ошибку, если формат изображения недопустимый.
        {
            if($lang=='ru') 
                return 'Некорректное изображение'; 
            if($lang=='en') 
                return 'Invalid image';
        }
           
        $func = 'imagecreatefrom' . $ext; // Получаем название функции, соответствующую типу, для создания изображения.
        $img_i = $func($image); // Создаём дескриптор для работы с исходным изображением.

        // Уменьшаем картинку по большему параметру, по ширине или длинне, до указанного значения.
        if($w_i>$h_i) $w_o = $size;
        else $h_o = $size;

        // Пролучаем второй уменьшенный параметр пропорционально. 
        if (!$h_o) $h_o = $w_o / ($w_i / $h_i);
        if (!$w_o) $w_o = $h_o / ($h_i / $w_i);

        // Создаём дескриптор для выходного изображения.
        $img_o = imagecreatetruecolor($w_o, $h_o); 

        // Переносим изображение из исходного в выходное, масштабируя его.
        imagecopyresampled($img_o, $img_i, 0, 0, 0, 0, $w_o, $h_o, $w_i, $h_i); 

        $func = 'image' . $ext; // Получаем функция для сохранения результата.
        $func($img_o, $image); // Сохраняем изображение в тот же файл, что и исходное, возвращая результат этой операции.

        return false;
    }

}