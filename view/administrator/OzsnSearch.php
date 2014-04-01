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
        // print out the form
        echo new \view\components\SimplePersonSearchForm(array(
            "postAction" => \route\Route::get('d3')->generate(array(
                "controller" => 'administrator',
                "action" => 'searchOzsn'
            )),
            "submitButtonText" => "Pretraži",
			"showAllButtonText" => "Prikaži sve članove"
        ));
		
		 // print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
		
		echo new \view\components\ErrorMessage(array(
            "resultMessage" => $this->resultMessage
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