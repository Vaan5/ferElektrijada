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