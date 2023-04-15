<?php

if (!empty($errors)) {
    $i = 1;
    foreach ($errors as $error) {
        echo esc($error);
        if($i!= count($errors)){
            echo '<br>';
        }
        $i++;
    }
}
