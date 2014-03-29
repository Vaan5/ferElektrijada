<?php

namespace view\administrator;
use app\view\AbstractView;

class ElektrijadaAdding extends AbstractView {
    /**
     *
     * @var string 
     */
    private $errorMessage;
    
    protected function outputHTML() {
        /*
         * Trebas prikazati odgovarajuci obrazac
         * i predati mu odgovarajuce parametre
         */
    }
    
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }

}