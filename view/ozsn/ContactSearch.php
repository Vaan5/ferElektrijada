<?php

namespace view\ozsn;
use app\view\AbstractView;

class ContactSearch extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $kontakti;
    private $tvrtke;
    private $sponzori;
    private $mediji;
    
    protected function outputHTML() {
	// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
	
		 echo new \view\components\ContactSearchForm(array(
			"postAction" => \route\Route::get('d3')->generate(array(
				"controller" => 'ozsn',
				"action" => 'searchContacts'
			)),
			"submitButtonText" => "Pretraži",
			"kontakti" => $this->kontakti,
			"tvrtke" => $this->tvrtke,
			"sponzori" => $this->sponzori,
			"mediji" => $this->mediji
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
	
	public function setKontakti($kontakti) {
        $this->kontakti = $kontakti;
        return $this;
    }

    public function setTvrtke($tvrtke) {
        $this->tvrtke = $tvrtke;
        return $this;
    }
	
	public function setSponzori($sponzori) {
        $this->sponzori = $sponzori;
        return $this;
    }

    public function setMediji($mediji) {
        $this->mediji = $mediji;
        return $this;
    }
}