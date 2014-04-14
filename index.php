<?php	
require_once 'inc/global.php';

try {
    \dispatcher\DefaultDispatcher::instance()->dispatch();
} catch (\app\model\NotFoundException $e) {
    preusmjeri(\route\Route::get("e404")->generate());
} catch (\PDOException $e) {
    preusmjeri(\route\Route::get("e404")->generate());
} catch (\Exception $e) {
    preusmjeri(\route\Route::get("e404")->generate());
}