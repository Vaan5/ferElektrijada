<?php

namespace view\administrator;
use app\view\AbstractView;

class OldOzsn extends AbstractView {
    /**
     *
     * @var string 
     */
    private $errorMessage;
    
    /**
     *
     * @var array 
     */
    private $clanovi;
    
    protected function outputHTML() {
        /*
         * prvo provjeri je li errorMessage postavljen ako je ispisi ga i ne ispisuj nista vise
         * ako nije proiteriraj kroz polje i generiraj listu sa imenom, prezimenom, korisnickim imenom clana odbora
         * plus pored svakog stavi link koji ce ga dodati kao aktivnog clana za ovu godinu (parametar get zahtjeva neka se zove id
         *  a akcija controllera listOldOzsn)
         * 
         * Na kraju stavi (dugme / link) koje ce preusmjeriti isto na listOldOzsn a sa get parametrom a=1
         * tu cu dodati sve clanove od prosle godine 
         * 
         */
    }
    
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }
    
    public function setClanovi($clanovi) {
        $this->clanovi = $clanovi;
        return $this;
    }

}