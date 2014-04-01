<?php

namespace view\administrator;
use app\view\AbstractView;

class OzsnModification extends AbstractView {
    
    private $osoba;
    
    /**
     *
     * @var string 
     */
    private $errorMessage;
    
    protected function outputHTML() {
        /**
         * prikazi onaj isti obrazac koji si koristio za dodavanje clanova odbora (PersonForm) (isto bez zivotopisa)
         * KAD GA BUDES RADIO parametriziraj sve podatke (tako da ih primas od controllera)
         * a zatim ako neki nije postavljen ne ispisujes nista, ako jest prikazes sadrzaj koji sam ti poslao
         * U OBRAZAC STAVI hidden polje s id-em osobe (dohvatis ga iz $osoba), i nazovi to polje
         * ISTO kao i primarni kljuc u bazi podataka dakle idOsobe (ako se ne varam)
         * pogledaj sa var_dump kako izgleda osoba
         * 
         * obrazac treba preusmjeravati na modifyOzsn od administratorovog controllera
         *
         * Josh dodaj jedno dugme(link) koje ce preusmjeriti na controllerovu akciju deleteOzsn
         * predaj mu kao get parametar id osobe cije ce ime biti 'id' (bez navodnika)
         * 
         * ako je errorMessage postavljen ispisi ga
         */
		
		// print out the form
        echo new \view\components\PersonForm(array(
            "postAction" => \route\Route::get('d3')->generate(array(
                "controller" => 'administrator',
                "action" => 'modifyOzsn'
            )),
            "submitButtonText" => "Spremi promjene",
			"osoba" => $this->osoba
        ));
        
        // print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
    }
    
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    public function setOsoba($osoba) {
        $this->osoba = $osoba;
        return $this;
    }

}