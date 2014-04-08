<?php

require_once 'inc/pomocna.php';
require_once "lib/fluentpdo/FluentPDO/FluentPDO.php";
require_once "lib/fpdf/fpdf.php";
//require_once "lib/tfpdf/tfpdf.php";
require_once "lib/excel/Classes/PHPExcel.php";
//require_once "lib/excel/primjer2.php";

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