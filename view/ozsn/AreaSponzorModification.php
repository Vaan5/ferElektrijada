<?php

namespace view\ozsn;
use app\view\AbstractView;

class AreaSponzorModification extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $podrucja;
    private $sponelekpod;
    
    protected function outputHTML() {
	// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
	
	// Zasebna forma za ovo
	// od sponzor samo prikazi podatke (ne mogu se mijenjati)
	// u obrascu stavljas podatke od imasponzora
	// padajuci meniji su kategorije i promocije
	// onaj koji je u $kategorija, i $promocija nek bude oznacen (ako postoji)
		
	// ne prikazuj drop down sponzore (jer ih ne moze promijeniti);
    }
    
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    public function setResultMessage($resultMessage) {
        $this->resultMessage = $resultMessage;
        return $this;
    }
    
    public function setPodrucja($podrucja) {
	$this->podrucja = $podrucja;
	return $this;
    }
    
    public function setSponelekpod($sponelekpod) {
	$this->sponelekpod = $sponelekpod;
	return $this;
    }

}