<?php

namespace view\administrator;
use app\view\AbstractView;

class PersonModification extends AbstractView {
    
    private $osoba;
    
    /**
     *
     * @var string 
     */
    private $errorMessage;
    
    protected function outputHTML() {
        
        // print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
		
		// print out the form
        echo new \view\components\PersonForm(array(
            "postAction" => \route\Route::get('d3')->generate(array(
                "controller" => 'administrator',
                "action" => 'modifyPerson'
            )),
            "submitButtonText" => "Spremi promjene",
            "osoba" => $this->osoba,
        ));
    }
    
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    public function setOsoba($osoba) {
        $this->osoba = $osoba;
        return $this;
    }

}