<?php

/**
 * Register short autoload
 */
spl_autoload_register(function ($className) {
    if (strstr($className, 'App\\') != '') {
        $class = str_replace("\\", '/', $className);
        include  'src/'.$class.'.php';
    }
});

function d($data) {
    echo '<pre>';
    var_dump($data);
    exit();
}
