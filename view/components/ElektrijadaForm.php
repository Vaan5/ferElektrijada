<?php

namespace view\components;
use app\view\AbstractView;

class ElektrijadaForm extends AbstractView {
    /**
     *
     * @var string url of the script to handle this form data
     */
    private $postAction;
    
    /**
     *
     * @var string submit button text
     */
    private $submitButtonText;
    
    /**
     *
     * @var object 
     */
    private $elektrijada;
    
    protected function outputHTML() {
        /*
         * Ispuni odgovarajuca polja (dodaj input) i prvo provjeri da li je $elektrijada postavljena
         * ako jest onda popuni polja s postojecim podacima (prilikom ispisa koristi __ funkciju iz pomocna.php)
         * ako nije -> PAZI DA NE ISPISES U POLJA BAS NISTA, cak niti ''
         */
?>
    <form action="<?php echo $this->postAction;?>" method="POST">
        
        <input type="submit" class="btn btn-primary" value="<?php echo $this->submitButtonText;?>" />
    </form>
<?php
    }
    
    public function setPostAction($postAction) {
        $this->postAction = $postAction;
        return $this;
    }

    public function setSubmitButtonText($submitButtonText) {
        $this->submitButtonText = $submitButtonText;
        return $this;
    }
    
    public function setElektrijada($elektrijada) {
        $this->elektrijada = $elektrijada;
        return $this;
    }
    
}
