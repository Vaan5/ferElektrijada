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
				"action" => 'addContact'
			)),
			"submitButtonText" => "Spremi promjene",
			"kontakt" => $this->kontakt,
			"sponzori" => $this->sponzori,
			"tvrtke" => $this->tvrtke,
			"mediji" => $this->mediji,
			"mobiteli" => $this->mobiteli,
			"mailovi" => $this->mailovi
		));
	
	// VAŽNO NA AKCIJU FORME NAKALEMI idKONTAKTA OBAVEZNO !!!! get("id")
	// OBAVEZNO dodaj ga i u formu kao hidden polje id (parametriziraj -> zbog dodavanja kontakata)
	
	// mailovi i mobiteli, na isti nacin kao i kod dodavanja, ispisi tablicu nek su name-ovi oblika mob1,2,3,.. i mail1,2,3,....
	// s desne strane omoguci i opciju brisi (u tom slucaju BITNO s javascriptom moraš izbrisati taj input field - da mi se ne posalje na
	// server + BITNO !!!!!!!!!!!!! preuredi sve ostale id-eve tako da idu po redu 1,2,3.... inace nece radit ispravno), 
	// te ispod svega dodajnovi
	// 
	// svaki zapis u tablici nek je mali post obrazac, u kojem kao hidden atribut zapisi idBroja ili idAdrese
	// tvrtke i sponzori i mediji nek je drop down meni - po defaultu nek je oznacen onaj koji ti pise u $kontakt (ako ga ima),
	// name im je idSponzora i slicno, a value stavljas kljuceve + dodaj jedan posebni value "" - kako smo i prije radili
		
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