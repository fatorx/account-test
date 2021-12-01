<?php

require_once '../../autoload.php';

spl_autoload_register(function ($className) {
    if (strstr($className, 'AppTest\\') != '') {
        $class = str_replace("\\", '/', $className);
        include  __DIR__ .'/'.$class.'.php';
    }
});

function runTest($class)
{
    $className = get_class($class);
    $classMethods = get_class_methods($className);

    echo $className."\n\n";
    foreach ($classMethods as $method) {
        if ($method == '__construct') {
            continue;
        }
        $result = $class->{$method}();
        $strResult = $result ? 'OK' : 'NOK';
        echo $method . ' : ' . $strResult . "\n";
    }
    echo "\n\n";
}

runTest(new \AppTest\Account\MethodsTest());
