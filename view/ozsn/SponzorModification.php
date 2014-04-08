<?php

namespace view\ozsn;
use app\view\AbstractView;

class SponzorModification extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $kategorije;
    private $promocije;
    private $sponzor;
    private $imasponzora;
    private $kategorija;
    private $promocija;
    
    protected function outputHTML() {
	// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
	
	// ispis forme za mijenjanje (pazi na enctype zbog slike)
	// u kategorije i promocije ti dajem sve moguce kategorije i promocije
	// u sponzor su podaci iz tablice sponzor
	// u imasponzora su podaci iz imasponzora (koristis napomenu, iznosDonacije i valutu)
	// u kategorija i promocija su ucitane njegova kategorija i promocija (ako ih nije odabrao tu stoji null)
	// imasponzora također moze biti prazan objekt (primarni kljuc mu je nula) - samo ostavi prazne kucice
	
	// VAŽNO: prije same forme u ovom pogledu prikazi sliku (putanja je u logotip od sponzora) - ako je NULL logotip na određeni način reci da nema slike
	// VAŽNO: FORMU DOBRO PARAMETRIZIRAJ TAKO DA JE MOZES KORISTITI I ZA DODAVANJE I ZA MIJENJANJE
	// VAŽNO: POLJE OD file-a mora imati name="datoteka"
	// VAŽNO u formu dodaj opcinalni radio button brisi logotip value neka je "delete" - (PARAMETRIZIRAJ TAJ radio button) -> prikaz samo ako već postoji stari logotip
		
    }
    
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    public function setResultMessage($resultMessage) {
        $this->resultMessage = $resultMessage;
        return $this;
    }
    
    public function setKategorije($kategorije) {
	$this->kategorije = $kategorije;
	return $this;
    }

    public function setPromocije($promocije) {
	$this->promocije = $promocije;
	return $this;
    }
    
    public function setSponzor($sponzor) {
	$this->sponzor = $sponzor;
	return $this;
    }

    public function setImasponzora($imasponzora) {
	$this->imasponzora = $imasponzora;
	return $this;
    }

    public function setKategorija($kategorija) {
	$this->kategorija = $kategorija;
	return $this;
    }

    public function setPromocija($promocija) {
	$this->promocija = $promocija;
	return $this;
    }

}