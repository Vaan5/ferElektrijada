<?php

namespace ctl;
use app\controller\Controller;

class Index implements Controller {
    
    private $resultMessage;
    
    private $errorMessage;
    
    private function checkForMessages() {
        switch (get('msg')) {
            case 'logsuccess':
                $this->resultMessage = 'Uspješno ste se prijavili!';
                break;
            case 'accessDenied':
                $this->errorMessage = 'Nemate odgovarajuće ovlasti!';
                break;
            case 'ozsnAddedSucc':
                $this->resultMessage = 'Uspješno dodan član odbora!';
                break;
            case 'elekAddSucc':
                $this->resultMessage = "Uspješno dodana nova Elektrijada!";
                break;
            default:
                $this->resultMessage = null;
                break;
        }
    }
    
    public function display() {
        $this->checkForMessages();
        
        echo new \view\Main(array(
            "body" => new \view\Index(array(
                "varijablaPrenesenaIzControllera" => "Varijabla koju sam mogao staviti da sam htio! => izmijeniti ctl\Index!",
                "resultMessage" => $this->resultMessage,
                "errorMessage" => $this->errorMessage
            )),
            "title" => "Welcome to FER!"
        ));
    }
}