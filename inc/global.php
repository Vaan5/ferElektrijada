<?php

require_once 'inc/pomocna.php';
require_once "lib/fluentpdo/FluentPDO/FluentPDO.php";

session_name("sesija");
session_start();

/**
 * autoload-er
 */
spl_autoload_register(function ($className){
    $fileName = './' .str_replace("\\", "/", $className) . '.php';
    if (!is_readable($fileName)) {
        return false;
    }
    
    require_once $fileName;
    
    return true;
});

require_once "inc/route.php";