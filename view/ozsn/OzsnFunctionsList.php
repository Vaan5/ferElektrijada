<?php

namespace view\ozsn;
use app\view\AbstractView;

class OzsnFunctionsList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $sveFunkcije;
    private $funkcijeKorisnika;
    
    protected function outputHTML() {
	// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
	
	// Nesto slicno DBM-u 
	// Analogno kao OzsnUdrugeList
	// kod brisanja mi saljes id od obavljafunckiju kao get
	// kod dodavanja mi kao post dajes idFunkcije
    }
    
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    public function setResultMessage($resultMessage) {
        $this->resultMessage = $resultMessage;
        return $this;
    }
    
    public function setSveFunkcije($sveFunkcije) {
	$this->sveFunkcije = $sveFunkcije;
	return $this;
    }

    public function setFunkcijeKorisnika($funkcijeKorisnika) {
	$this->funkcijeKorisnika = $funkcijeKorisnika;
	return $this;
    }

}