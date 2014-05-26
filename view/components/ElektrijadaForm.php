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
	
	private $modifyDates = true;
    
    protected function outputHTML() {
?>
    <form id="elektrijadaForm" class="form-horizontal" role="form" action="<?php echo $this->postAction;?>" method="POST">
        <div class="form-group">
            <label for="mjestoodrzavanja" class="col-sm-3 control-label">Mjesto održavanja</label>
                        <div class="col-sm-8">
			<input type="text" name="mjestoOdrzavanja" class="form-control" placeholder="Upišite mjesto održavanja" <?php if($this->elektrijada && $this->elektrijada->mjestoOdrzavanja){ echo 'value="' . $this->elektrijada->mjestoOdrzavanja . '"'; } ?> />
                        </div>
        </div>                       
        <div class="form-group">
            <label for="drzava" class="col-sm-3 control-label">Država</label>
                        <div class="col-sm-8">
			<input type="text" name="drzava" class="form-control" placeholder="Upišite državu" <?php if($this->elektrijada && $this->elektrijada->drzava){ echo 'value="' . $this->elektrijada->drzava . '"'; } ?> />
                        </div>
        </div>
<?php
		if($this->modifyDates === true) {
?>
        <div class="form-group">
            <label for="datumpocetka" class="col-sm-3 control-label">Datum početka</label>
                        <div class="col-sm-8">
                        <input type="text" name="datumPocetka" placeholder="Upišite datum početka" class="datePicker form-control" <?php if($this->elektrijada && $this->elektrijada->datumPocetka){ echo 'value="' . $this->elektrijada->datumPocetka . '"'; } ?> />
                        </div>
        </div>
        <div class="form-group">
            <label for="datumkraja" class="col-sm-3 control-label">Datum kraja</label>
                        <div class="col-sm-8">
                        <input type="text" name="datumKraja" placeholder="Upišite datum kraja" class="datePicker form-control" <?php if($this->elektrijada && $this->elektrijada->datumKraja){ echo 'value="' . $this->elektrijada->datumKraja . '"'; } ?> />
                        </div>
        </div>
<?php
		}
		
		if($this->modifyDates === true) {
?>
        <div class="form-group">
            <label for="rokzaznanje" class="col-sm-6 control-label">Rok za unos podataka za područje znanja</label>
                        <div class="col-sm-5">
                        <input type="text" name="rokZaZnanje" placeholder="Upišite rok za znanje" class="datePicker form-control" <?php if($this->elektrijada && $this->elektrijada->rokZaZnanje){ echo 'value="' . $this->elektrijada->rokZaZnanje . '"'; } ?> />
                        </div>
        </div>
        <div class="form-group">
            <label for="rokzasport" class="col-sm-6 control-label">Rok za unos podataka za područje sporta</label>
                        <div class="col-sm-5">
                        <input type="text" name="rokZaSport" placeholder="Upišite rok za sport" class="datePicker form-control" <?php if($this->elektrijada && $this->elektrijada->rokZaSport){ echo 'value="' . $this->elektrijada->rokZaSport . '"'; } ?> />
                        </div>
        </div>
<?php
		}
?>
        <div class="form-group">
            <label for="ukupnibrsud" class="col-sm-4 control-label">Ukupni broj sudionika</label>
                        <div class="col-sm-7">           
                        <input type="text" name="ukupanBrojSudionika" class="form-control" placeholder="Upišite broj sudionika" <?php if($this->elektrijada && $this->elektrijada->ukupanBrojSudionika){ echo 'value="' . $this->elektrijada->ukupanBrojSudionika . '"'; } ?> />
                        </div>
        </div>
		
        <div class="form-group">
            <label for="ukupnirez" class="col-sm-4 control-label">Ukupni rezultat</label>
                        <div class="col-sm-7">
                        <input type="text" name="ukupniRezultat" class="form-control" placeholder="Upišite ukupni rezultat" <?php if($this->elektrijada && $this->elektrijada->ukupniRezultat){ echo 'value="' . $this->elektrijada->ukupniRezultat . '"'; } ?> />
                        </div>
        </div>
		
		<?php if($this->elektrijada && $this->elektrijada->idElektrijade){ echo '<input type="hidden" name="idElektrijade" value="' . $this->elektrijada->idElektrijade . '">'; } ?>
        <center><input type="submit" class="btn btn-primary" value="<?php echo $this->submitButtonText;?>" /></center>
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
    
	public function setModifyDates($modifyDates) {
		$this->modifyDates = $modifyDates;
		return $this;
	}

}
