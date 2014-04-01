<?php

namespace view\administrator;
use app\view\AbstractView;

class ElektrijadaModification extends AbstractView {
    
    private $elektrijada;
    
    /**
     *
     * @var string 
     */
    private $errorMessage;
    
    protected function outputHTML() {
        /**
         * prikazi onaj isti obrazac koji si koristio za dodavanje elektrijade
         * KAD GA BUDES RADIO parametriziraj sve podatke (tako da ih primas od controllera)
         * a zatim ako neki nije postavljen ne ispisujes nista, ako jest prikazes sadrzaj koji sam ti poslao
         * U OBRAZAC STAVI hiddent polje s id-em elektrijade (dohvatis ga iz $elektrijada), i nazovi to polje
         * ISTO kao i primarni kljuc u bazi podataka dakle idElektrijade
         * pogledaj sa var_dump kako izgleda elektrijada
         * 
         * obrazac treba preusmjeravati na modifyElektrijada od administratorovog controllera
         *
         * Josh dodaj jedno dugme(link) koje ce preusmjeriti na controllerovu akciju deleteElektrijada
         * predaj mu kao get parametar id elektrijade cije ce ime biti 'id' (bez navodnika)
         */
		
		// print out the form
        echo new \view\components\ElektrijadaForm(array(
            "postAction" => \route\Route::get('d3')->generate(array(
                "controller" => 'administrator',
                "action" => 'modifyElektrijada'
            )),
            "submitButtonText" => "Spremi promjene",
			"elektrijada" => $this->elektrijada
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

    public function setElektrijada($elektrijada) {
        $this->elektrijada = $elektrijada;
        return $this;
    }

}