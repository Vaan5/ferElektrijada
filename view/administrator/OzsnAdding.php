<?php

namespace view\administrator;
use app\view\AbstractView;

class OzsnAdding extends AbstractView {
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
		
		// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
        
        // print out the form
        echo new \view\components\PersonForm(array(
            "postAction" => \route\Route::get('d3')->generate(array(
                "controller" => 'administrator',
                "action" => 'addOzsn'
            )),
            "submitButtonText" => "Dodaj novog člana"
        ));        
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
