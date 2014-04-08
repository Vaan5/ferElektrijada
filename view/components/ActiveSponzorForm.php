<?php

namespace view\components;
use app\view\AbstractView;

class ActiveSponzorForm extends AbstractView {
    private $route;
    private $submitButtonText;
    private $kategorije;
    private $promocije;

    protected function outputHTML() {
	// kategorije i promocije nek budu drop down list (id u value a name je kao u tablicama id)
	// dodati settere !!!!!!!!!!!!
	// od polja mi trebaju polja imaSponzora (ono donacija i slicno), napomena nek bude textarea
	// za valute stavi drop down s onim valutama koje ante dozvoljava iz triggera
?>

<?php
    }
    
    
    

}