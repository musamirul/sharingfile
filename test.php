<?php
     $name = $_GET['folderName'];
    $items = array();
    $file_data = scandir($name);
    foreach($file_data as $file)
    {
        if($file === '.' or $file === '..')
        {
            continue;
        }
            else
        {
            $items[] = $name. '/' . $file;

    }
    }

    print_r($items);
?>