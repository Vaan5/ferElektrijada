<?php

namespace view\ozsn;
use app\view\AbstractView;

class OzsnUdrugeList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $sveUdruge;
    private $udrugeKorisnika;
    
    protected function outputHTML() {
	// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
	
	// Nesto slicno DBM-u 
	// prikazuju se udruge u kojima je trenutni korisnik registriran
	// moze obrisati, i dodati novu (tad nek mu se pokaze drop down lista postojecih udruga)
	// u udrugeKorisnika su one udruge u kojima je vec.
    }
    
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    public function setResultMessage($resultMessage) {
        $this->resultMessage = $resultMessage;
        return $this;
    }
    
    public function setSveUdruge($sveUdruge) {
	$this->sveUdruge = $sveUdruge;
	return $this;
    }

    public function setUdrugeKorisnika($udrugeKorisnika) {
	$this->udrugeKorisnika = $udrugeKorisnika;
	return $this;
    }
}