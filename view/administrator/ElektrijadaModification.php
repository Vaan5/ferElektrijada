<?php

namespace view\administrator;
use app\view\AbstractView;

class ElektrijadaModification extends AbstractView {
    
    private $elektrijada;
    
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
        echo new \view\components\ElektrijadaForm(array(
            "postAction" => \route\Route::get('d3')->generate(array(
                "controller" => 'administrator',
                "action" => 'modifyElektrijada'
            )),
            "submitButtonText" => "Spremi promjene",
			"elektrijada" => $this->elektrijada
        ));
    }
    
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    public function setElektrijada($elektrijada) {
        $this->elektrijada = $elektrijada;
        return $this;
    }

}