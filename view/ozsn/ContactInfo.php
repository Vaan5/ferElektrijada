<?php

namespace view\ozsn;
use app\view\AbstractView;

class ContactInfo extends AbstractView {
    private $errorMessage;
    private $resultMessage;
	private $kontakti;
	private $kontakt;
	private $mobiteli;
	private $mailovi;
    
    protected function outputHTML() {
		// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
		
		// ako su mobiteli ili mailovi === null ispisujes formu sa postojecim kontaktima (svaki nek ima jedan radio bbutton)
		// MOZE SE KLIKNUTI SAMO JEDAN OD NJIH
		// inace (mobiteli ili mailovi nisu null nego array() ili neprazan array)
		// Na neki nacin ispisi osnovne podatke o korisniku i njegove mailove i brojeve mobitela
		// Dodaj downloadLinks

    }
    
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    public function setResultMessage($resultMessage) {
        $this->resultMessage = $resultMessage;
        return $this;
    }
	
	public function setKontakti($kontakti) {
		$this->kontakti = $kontakti;
		return $this;
	}

	public function setKontakt($kontakt) {
		$this->kontakt = $kontakt;
		return $this;
	}

	public function setMobiteli($mobiteli) {
		$this->mobiteli = $mobiteli;
		return $this;
	}

	public function setMailovi($mailovi) {
		$this->mailovi = $mailovi;
		return $this;
	}
}