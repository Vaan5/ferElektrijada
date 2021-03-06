<?php

namespace view\ozsn;
use app\view\AbstractView;

class AddContact extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $sponzori;
    private $tvrtke;
    private $mediji;
    
    protected function outputHTML() {
	
	echo new \view\components\ErrorMessage(array(
	    "errorMessage" => $this->errorMessage
	));
	
	echo new \view\components\ResultMessage(array(
	    "resultMessage" => $this->resultMessage
	));
	
	echo new \view\components\KontaktOsobeForm(array(
		"postAction" => \route\Route::get('d3')->generate(array(
			"controller" => 'ozsn',
			"action" => 'addContact'
		)),
		"submitButtonText" => "Dodaj kontakt osobu",
		"sponzori" => $this->sponzori,
		"tvrtke" => $this->tvrtke,
		"mediji" => $this->mediji
	)).'<br>';
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

    public function setTvrtke($tvrtke) {
        $this->tvrtke = $tvrtke;
        return $this;
    }
    
    public function setMediji($mediji) {
	$this->mediji = $mediji;
	return $this;
    }

}