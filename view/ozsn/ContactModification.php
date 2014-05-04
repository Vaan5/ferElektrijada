<?php

namespace view\ozsn;
use app\view\AbstractView;

class ContactModification extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $kontakt;
    private $tvrtke;
    private $sponzori;
    private $mediji;
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
		
		echo new \view\components\KontaktOsobeForm(array(
			"postAction" => \route\Route::get('d3')->generate(array(
				"controller" => 'ozsn',
				"action" => 'modifyContact'
			)) . "?id=" . $this->kontakt->idKontakta,
			"submitButtonText" => "Spremi promjene",
			"kontakt" => $this->kontakt,
			"sponzori" => $this->sponzori,
			"tvrtke" => $this->tvrtke,
			"mediji" => $this->mediji,
			"mobiteli" => $this->mobiteli,
			"mailovi" => $this->mailovi
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
    
    public function setKontakt($kontakt) {
	$this->kontakt = $kontakt;
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
    
    public function setMobiteli($mobiteli) {
	$this->mobiteli = $mobiteli;
	return $this;
    }

    public function setMailovi($mailovi) {
	$this->mailovi = $mailovi;
	return $this;
    }
}