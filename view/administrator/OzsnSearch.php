<?php

namespace view\administrator;
use app\view\AbstractView;

class OzsnSearch extends AbstractView {
    /**
     *
     * @var string 
     */
    private $errorMessage;
    
    /**
     *
     * @var string 
     */
    private $resultMessage;
    
    protected function outputHTML() {
        /*
         * prikazi obrazac za pretrazivanje
         * 
         * ako je postavljeno nesto od error ili result message ispisi ih
         */
    }
    
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    public function setResultMessage($resultMessage) {
        $this->resultMessage = $resultMessage;
        return $this;
    }

}