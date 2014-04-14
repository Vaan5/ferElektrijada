<?php

namespace view\ozsn;
use app\view\AbstractView;

class SponsorsByElektrijadaList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $elektrijade;
    private $sponzori;
    
    protected function outputHTML() {		
	
	// ispisi poruke
	// ako je sponzori === null prikazi post formu sa drop down listom elektrijada
	// parametar je idElektrijade
	// ako je sponzori !== null onda ih ispisi (nema brisanja ni dodavanja ni uredjivanja), ali ispisi vise podataka (moze biti prazan array pa pazi i na to)
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
    public function setElektrijade($elektrijade) {
	$this->elektrijade = $elektrijade;
	return $this;
    }

}