<?php

namespace view\ozsn;
use app\view\AbstractView;

class TvrtkaAssign extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $tvrtka;
    private $usluge;
    
    protected function outputHTML() {
        // dodaj settere
	// ispisuje TvrtkaAssignForm
	// u njemu kao drop down usluge (na klasican nacin kao i dosad sto smo radili)
	// mozes ispisati i podatke o tvrtci, ali ONI SE NE MOGU MIJENJATI (NE STAVLJATI U FORM)
	// u formi ostala polja su ona iz tablice koristiPruza
	//
	//iznosRacuna DECIMAL(13 , 2 ) NOT NULL,
//    valutaRacuna VARCHAR(3) NOT NULL,    
//    nacinPlacanja VARCHAR(100),
//    napomena VARCHAR(300),
	// valuta je drop down s one tri vrijednosti (HRK default)
	
	//VAŽNO hidden id( od tvrtke) u post obrascu
	
	// PARAMETRIZIRAJ OBRAZAC
		
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
				"action" => 'assignTvrtka'
			)),
			"submitButtonText" => "Spremi promjene",
			"tvrtka" => $this->tvrtka,
			"usluge" => $this->usluge
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
	
	public function setUsluge($usluge) {
        $this->usluge = $usluge;
        return $this;
    }
	
}
