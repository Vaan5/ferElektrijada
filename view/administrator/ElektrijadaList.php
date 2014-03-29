<?php

namespace view\administrator;
use app\view\AbstractView;

class ElektrijadaList extends AbstractView {
    /**
     *
     * @var array of objects 
     */
    private $elektrijade;
    
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
         * neka sadrzi godinu odrzavanja , mjesto, drzavu i jedan link na uredjivanje (mozes ga staviti recimo da klikom na godinu se uredjuje)
         * u linku dodaj kao get parametar id elektrijade (preusmjeri  na odgovarajucu akciju controllera Administrator)
         * GET PARAMETAR SE MORA ZVATI 'id' (bez navodnika)
         * 
         * Da vidis kako tocno izgleda varijabla elektrijada ukucaj
         * var_dump($this->elektrijada);
         * var_dump($this->elektrijada);
         * var_dump($this->elektrijada);
         * die();
         * 
         * PRIJE ISPISA PRVO POGLEDAJ JE LI ERRORMESSAGE != NULL AKO JE SAMO ISPISI (bilo koristeci ErrorMessage view ili bez njega)
         * da nije upisana niti jedna elektrijada
         * 
         * Ako je postavljena resultMessage ispisi ju koristeci ResultMessage pogled
         * 
         */
    }
    
    public function setElektrijade($elektrijade) {
        $this->elektrijade = $elektrijade;
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