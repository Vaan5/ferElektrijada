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
        
		// print out the form
        echo new \view\components\ElektrijadaForm(array(
            "postAction" => \route\Route::get('d3')->generate(array(
                "controller" => 'administrator',
                "action" => 'addElektrijada'
            )),
            "submitButtonText" => "Dodaj elektrijadu"
        ));
		
		 // print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
    }
    
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }

}