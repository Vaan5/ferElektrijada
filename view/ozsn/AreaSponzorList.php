<?php

namespace view\ozsn;
use app\view\AbstractView;

class AreaSponzorList extends AbstractView {
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
	
	// samo ispisati osnovne podatke // ime trvtke i adresu iznos i podrucje
	// opcije(nazovi ih kako ti pase) su Dodaj novog, Uredi, i Brisi - odnosi se na add/modify/deleteAreaSponzor
		
	// PAZI var_dumpaj sponzore (NIJE OBJEKTNI OBLIK JER IMAS U NJEMU I sponElekPodrucje, pa mi za modify u get parametar id stavi identifikator od sponelekpod tablice)
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