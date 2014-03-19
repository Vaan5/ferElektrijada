<?php	
require_once 'inc/global.php';

try {
    \dispatcher\DefaultDispatcher::instance()->dispatch();
} catch (opp\model\NotFoundException $e) {
    preusmjeri(\route\Route::get("e404")->generate());
}