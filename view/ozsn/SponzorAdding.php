<?php

namespace view\ozsn;
use app\view\AbstractView;

class SponzorAdding extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $kategorije;
    private $promocije;
    
    protected function outputHTML() {
	// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
	
		// print out the form
		echo new \view\components\SponzorForm(array(
			"route" => \route\Route::get('d3')->generate(array(
				"controller" => 'ozsn',
				"action" => 'addSponzor'
			)),
			"submitButtonText" => "Dodaj sponzora",
			"kategorije" => $this->kategorije,
			"promocije" => $this->promocije
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
    
    public function setKategorije($kategorije) {
	$this->kategorije = $kategorije;
	return $this;
    }

    public function setPromocije($promocije) {
	$this->promocije = $promocije;
	return $this;
    }
  
}