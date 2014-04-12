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
    }
}
