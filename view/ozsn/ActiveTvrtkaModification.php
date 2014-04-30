<?php

namespace view\ozsn;
use app\view\AbstractView;

class ActiveTvrtkaModification extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $tvrtka;
    private $koristiPruza;
    private $usluge;
    private $usluga;

    
    protected function outputHTML() {
	// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
	
	// FORMA IMA HIDDEN Atribut id koji je jednak id od koristiPruza -> pazi to je razlicito od onog sto mi treba kod assignActiveTvrtka
	// DODAJ SETTERE
	// NE MOGU SE MIJENJATI PODACI O TVRTCI (NJIH SAMO ISPISI IZVAN FORME)
	// ispisi formu za mijenjanje aktivnih tvrtki (nek ima opciju za brisanje - saljes get(id));
	// mijenjati se mogu samo podaci iz koristipruza + idUsluge
		
		// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
		
		echo new \view\components\TvrtkaAssignForm(array(
			"route" => \route\Route::get('d3')->generate(array(
				"controller" => 'ozsn',
				"action" => 'modifyActiveTvrtka'
			)),
			"submitButtonText" => "Spremi promjene",
			"tvrtka" => $this->tvrtka,
			"koristiPruza" => $this->koristiPruza,
			"usluge" => $this->usluge,
			"usluga" => $this->usluga
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
	
	public function setTvrtka($tvrtka) {
        $this->tvrtka = $tvrtka;
        return $this;
    }
	
	public function setKoristiPruza($koristiPruza) {
        $this->koristiPruza = $koristiPruza;
        return $this;
    }
	
	public function setUsluge($usluge) {
        $this->usluge = $usluge;
        return $this;
    }
	
	public function setUsluga($usluga) {
        $this->usluga = $usluga;
        return $this;
    }

}