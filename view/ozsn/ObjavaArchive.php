<?php

namespace view\ozsn;
use app\view\AbstractView;

class ObjavaArchive extends AbstractView {
    private $errorMessage;
    private $resultMessage;
	private $elektrijade;
	private $rezultati;
    
    protected function outputHTML() {
		// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
		
		// napravi nesh slicno DBM-u
		// dakle ako rezultati nisu postavljeni (null) prikazujes post formu sa drop down om elektrijada
		// inace prikazujes rezultate (ako je prazno polje ispisi poruku)
		// dodaj download linkove i nista vise (ne treba uredjivanje i ostalo...)
    }
    
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    public function setResultMessage($resultMessage) {
        $this->resultMessage = $resultMessage;
        return $this;
    }
	
	public function setElektrijade($elektrijade) {
		$this->elektrijade = $elektrijade;
		return $this;
	}

	public function setRezultati($rezultati) {
		$this->rezultati = $rezultati;
		return $this;
	}
	
}