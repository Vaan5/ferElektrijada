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
    
    private function checkMessages() {
        switch(get("msg")) {
            case 'succ':
                $this->resultMessage = "Uspješno izmijenjena postojeća Elektrijada!";
                break;
            case 'succo':
                $this->resultMessage = "Uspješno ažurirani podaci o članu odbora!";
                break;
            case 'del':
                $this->resultMessage = "Uspješno izbrisana Elektrijada!";
                break;
            case 'err':
                $this->errorMessage = "Zahtjevani zapis ne postoji!";
                break;
            case 'delo':
                $this->errorMessage = "Uspješno izbrisan član odbora!";
                break;
            case 'derr':
                $this->errorMessage = "Dogodila se pogreška prilikom brisanja! Pokušajte ponovno!";
                break;
            case 'noel':
                $this->errorMessage = "Članove možete dodavati samo za trenutno aktivnu elektrijadu! Najprije stvorite novu elektrijadu!";
                break;
            case 'param':
                $this->errorMessage = "Popunite parametre pretrage!";
                break;
            case 'remSucc':
                $this->resultMessage = "Osoba uklonjena iz ovogodišnjeg odbora!";
                break;
            default:
                break;
        }
    }
    
    /**
     * Displays all attributes in database
     */
    public function displayAtribut() {
        $this->checkRole();
        $this->checkMessages();
        
        $atribut = new \model\DBAtribut();
        try {
            $atributi = $atribut->getAllAtributes();
            if (!count($atributi))
                $this->errorMessage = "Ne postoji niti jedan zapis atributa!";
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $this->errorMessage = $handler;
        }

        echo new \view\Main(array(
            "body" => new \view\ozsn\AtributList(array(
                "errorMessage" => $this->errorMessage,
                "resultMessage" => $this->resultMessage,
                "atributi" => $atributi
            )),
            "title" => "Lista atributa"
        ));
    }
    
    /**
     * Inserts new data into database via post request
     */
    public function addAtribut() {
        
    }
    
    /**
     * Modifies attribute data via post request
     */
    public function modifyAtribut() {
        
    }
    
    /**
     * Deletes attribute via get request
     */
    public function deleteAtribut() {
        
    }
}
