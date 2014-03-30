<?php

namespace view\administrator;
use app\view\AbstractView;

class OzsnList extends AbstractView {
    /**
     *
     * @var array 
     */
    private $osobe;
    
    /**
     *
     * @var string 
     */
    private $errorMessage;
    
    /**
     *
     * @var string 
     */
    private $resultMessage;
    
    protected function outputHTML() {
        /* Napravi ispis u obliku tablice
         * neka sadrzi neke temeljne podatke (NIKAKO NE ID) i jedan link na uredjivanje (mozes ga staviti recimo da klikom na godinu se uredjuje)
         * u linku dodaj kao get parametar id osobe (preusmjeri  na odgovarajucu akciju controllera Administrator)
         * GET PARAMETAR SE MORA ZVATI 'id' (bez navodnika)
         * 
         * 
         * 
         * PRIJE ISPISA PRVO POGLEDAJ JE LI ERRORMESSAGE != NULL AKO JE SAMO ISPISI (bilo koristeci ErrorMessage view ili bez njega)
         * da nije pronađen niti jedan član odbora koji zadovoljava parametre pretrage
         * 
         * Ako je postavljena resultMessage ispisi ju koristeci ResultMessage pogled
         * 
         */
    }
    
    public function setOsobe($osobe) {
        $this->osobe = $osobe;
        return $this;
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