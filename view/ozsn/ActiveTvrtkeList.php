<?php

namespace view\ozsn;
use app\view\AbstractView;

class ActiveTvrtkeList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $tvrtke;
    
    protected function outputHTML() {
        // dodaj settere
        // + ispis errorMessage i resultMessage
	// KLASICNI DBM
	
	// PAZI tvrtke nisu objektno nacinjenje, koristi var_dump da ispises sve podatke-> podaci o tvrtci se ne trebaju moci mijenjati,
	// mogu se mijenjati podaci iz tablice koristipruza
    }
}
