<?php

namespace view\ozsn;
use app\view\AbstractView;

class AreaSponzorAdding extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $sponzori;
    private $podrucja;
    
    protected function outputHTML() {
	// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
	
	// ispis forme za dodavanje
	// sponzore i podrucja kao drop down liste value im je id, a name je idSponzora odnosno idPodrucja
		
    }
    
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    public function setResultMessage($resultMessage) {
        $this->resultMessage = $resultMessage;
        return $this;
    }
    
    public function setSponzori($sponzori) {
	$this->sponzori = $sponzori;
	return $this;
    }

    public function setPodrucja($podrucja) {
	$this->podrucja = $podrucja;
	return $this;
    }
  
}