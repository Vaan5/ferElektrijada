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
        <p>Mjesto održavanja: &nbsp;
			<input type="text" name="mjestoOdrzavanja" placeholder="Upišite mjesto održavanja" <?php if($this->elektrijada && $this->elektrijada->mjestoOdrzavanja){ echo 'value="' . $this->elektrijada->mjestoOdrzavanja . '"'; } ?> />
        </p>
		<p>Država: &nbsp;
			<input type="text" name="drzava" placeholder="Upišite državu" <?php if($this->elektrijada && $this->elektrijada->drzava){ echo 'value="' . $this->elektrijada->drzava . '"'; } ?> />
        </p>
        <p>Datum početka: &nbsp;
            <input type="text" name="datumPocetka" placeholder="Upišite datum početka" class="datePicker" <?php if($this->elektrijada && $this->elektrijada->datumPocetka){ echo 'value="' . $this->elektrijada->datumPocetka . '"'; } ?> />
        </p>
		<p>Datum kraja: &nbsp;
            <input type="text" name="datumKraja" placeholder="Upišite datum kraja" class="datePicker" <?php if($this->elektrijada && $this->elektrijada->datumKraja){ echo 'value="' . $this->elektrijada->datumKraja . '"'; } ?> />
        </p>
		<p>Ukupni rezultat: &nbsp;
            <input type="text" name="ukupniRezultat" placeholder="Upišite ukupni rezultat" <?php if($this->elektrijada && $this->elektrijada->ukupniRezultat){ echo 'value="' . $this->elektrijada->ukupniRezultat . '"'; } ?> />
        </p>
                <p>Rok za unos podataka za područje znanja: &nbsp;
            <input type="text" name="rokZaZnanje" placeholder="Upišite rok za znanje" class="datePicker" <?php if($this->elektrijada && $this->elektrijada->rokZaZnanje){ echo 'value="' . $this->elektrijada->rokZaZnanje . '"'; } ?> />
        </p>
                <p>Rok za unos podataka za područje sporta: &nbsp;
            <input type="text" name="rokZaSport" placeholder="Upišite rok za sport" class="datePicker" <?php if($this->elektrijada && $this->elektrijada->rokZaSport){ echo 'value="' . $this->elektrijada->rokZaSport . '"'; } ?> />
        </p>
                <p>Ukupan broj sudionika: &nbsp;
            <input type="text" name="ukupanBrojSudionika" placeholder="Upišite broj sudionika" <?php if($this->elektrijada && $this->elektrijada->ukupanBrojSudionika){ echo 'value="' . $this->elektrijada->ukupanBrojSudionika . '"'; } ?> />
        </p>
		
		<?php if($this->elektrijada && $this->elektrijada->idElektrijade){ echo '<input type="hidden" name="idElektrijade" value="' . $this->elektrijada->idElektrijade . '">'; } ?>
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
