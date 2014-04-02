<?php

namespace ctl;
use app\controller\Controller;

class Ozsn implements Controller {
    
    private $errorMessage;
    private $resultMessage;
    
    private function checkRole() {
        // you must be logged in, and an Ozsn member with or without leadership
        if (!(\model\DBOsoba::isLoggedIn() && (\model\DBOsoba::getUserRole() !== 'O' || \model\DBOsoba::getUserRole() !== 'OV'))) {
            preusmjeri(\route\Route::get('d1')->generate() . "?msg=accessDenied");
        }
    }
    
    public function displayAtribut() {
        $this->checkRole();
        
        echo new \view\Main(array(
            "body" => new \view\ozsn\AtributList(array(
                "errorMessage" => $this->errorMessage,
                "resultMessage" => $this->resultMessage
            )),
            "title" => ""
        ));
    }
}
