<?php

namespace view\ozsn;
use app\view\AbstractView;

class ActiveSponzorList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $sponzori;
    
    protected function outputHTML() {
	// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
	
	// samo ispisati osnovne podatke // ime trvtke i adresu
	// opcije(nazovi ih kako ti pase) su Uredi, i Brisi - odnosi se na modify/deleteActiveSponzor  (NE TREBA NAM DODAVANJE) -to se radi preko modifySponzor
		
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
}