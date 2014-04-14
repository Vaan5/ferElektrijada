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
		
    }
    
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    public function setResultMessage($resultMessage) {
        $this->resultMessage = $resultMessage;
        return $this;
    }

}