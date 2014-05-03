<?php

namespace view\ozsn;
use app\view\AbstractView;

class ContestantAttributes extends AbstractView {
    private $errorMessage;
    private $resultMessage;
	private $podrucjeSudjelovanja;
	private $idSudjelovanja;
	private $vrsta;
	private $idPodrucja;
	private $podrucja;
	private $atributi;
	private $korisnikoviAtributi;

	protected function outputHTML() {
		echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
	
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
?>
		<form action="<?php echo \route\Route::get("d3")->generate(array(
			"controller" => "ozsn",
			"action" => "changeContestantAttributes"
		));?>" method="POST">
			
		<div class="form-group">
            <label for="rezultatPojedinacni" class="col-sm-3 control-label">Rezultat</label>            
            <div class="col-sm-9">
            <input type="text" name="rezultatPojedinacni" class="form-control" placeholder="Upišite rezultat" <?php if($this->podrucjeSudjelovanja && $this->podrucjeSudjelovanja->rezultatPojedinacni){ echo 'value="' . $this->podrucjeSudjelovanja->rezultatPojedinacni . '"'; } ?> autocomplete="off"  />
            </div>  
        </div>
			
		<div class="form-group">
            <label for="ukupanBrojSudionika" class="col-sm-3 control-label">Ukupno sudionika:</label>            
            <div class="col-sm-9">
            <input type="text" name="ukupanBrojSudionika" class="form-control" placeholder="Upišite broj sudionika" <?php if($this->podrucjeSudjelovanja && $this->podrucjeSudjelovanja->ukupanBrojSudionika){ echo 'value="' . $this->podrucjeSudjelovanja->ukupanBrojSudionika . '"'; } ?> autocomplete="off"  />
            </div>  
        </div>
		
		<div class="form-group">
			<label for="iznosUplate" class="col-sm-3 control-label">Iznos uplate</label>
			<div class="col-sm-9">
				<div class="input-group">
					<input type="text" name="iznosUplate" class="form-control" placeholder="Upišite iznos uplate" <?php if($this->podrucjeSudjelovanja && $this->podrucjeSudjelovanja->iznosUplate) echo 'value="' . $this->podrucjeSudjelovanja->iznosUplate . '"' ?> />
					
					<div class="input-group-btn">
						<select name="valuta" class="form-control btn btn-default" style="width:80px;">
						<option <?php if(!$this->podrucjeSudjelovanja || ($this->podrucjeSudjelovanja && $this->podrucjeSudjelovanja->valuta == 'HRK')) echo 'selected="selected"' ?> value="HRK">HRK</option>
						<option <?php if($this->podrucjeSudjelovanja && $this->podrucjeSudjelovanja->valuta == 'USD') echo 'selected="selected"' ?> value="USD">USD</option>
						<option <?php if($this->podrucjeSudjelovanja && $this->podrucjeSudjelovanja->valuta == 'EUR') echo 'selected="selected"' ?> value="EUR">EUR</option>
						</select>
					</div>
					
					</div>
			</div>
		</div>
<?php		
if ($this->atributi !== null) {
?>	

	<div class="form-group">	
                <label for="atributi" class="col-sm-3 control-label">Atributi</label>
		<div class="col-sm-9">
                <select name="idAtributa[]" class="form-control" multiple>
			<option <?php if(!$this->korisnikoviAtributi && count($this->korisnikoviAtributi)) 
				echo 'selected="selected"'; ?> value=""><?php if(!$this->korisnikoviAtributi && count($this->korisnikoviAtributi)) echo '(prazno)'; else echo '(prazno)'; ?></option>

<?php
		foreach($this->atributi as $val)
		{
			echo '<option value="' . $val->idAtributa . '"';
			if ($this->korisnikoviAtributi && count($this->korisnikoviAtributi) && in_array($val->idAtributa, $this->korisnikoviAtributi))
			{
				echo 'selected="selected"';
			}
			echo '>' . $val->nazivAtributa . '</option>';
		}
?>				
</select></div>
        </div>
	
<?php 
			}
?>
			
			<input type="hidden" name="idS" value="<?php echo $this->idSudjelovanja?>" />
			<input type="hidden" name="idP" value="<?php echo $this->idPodrucja?>" />
			<input type="hidden" name="vrsta" value="<?php echo $this->vrsta?>" />
			<input type="submit" value="Ažuriraj" />
		</form>
<?php
	}
	
	public function setErrorMessage($errorMessage) {
		$this->errorMessage = $errorMessage;
		return $this;
	}

	public function setResultMessage($resultMessage) {
		$this->resultMessage = $resultMessage;
		return $this;
	}

	public function setPodrucjeSudjelovanja($podrucjeSudjelovanja) {
		$this->podrucjeSudjelovanja = $podrucjeSudjelovanja;
		return $this;
	}

	public function setIdSudjelovanja($idSudjelovanja) {
		$this->idSudjelovanja = $idSudjelovanja;
		return $this;
	}

	public function setPodrucja($podrucja) {
		$this->podrucja = $podrucja;
		return $this;
	}

	public function setAtributi($atributi) {
		$this->atributi = $atributi;
		return $this;
	}

	public function setKorisnikoviAtributi($korisnikoviAtributi) {
		$this->korisnikoviAtributi = $korisnikoviAtributi;
		return $this;
	}
	
	public function setVrsta($vrsta) {
		$this->vrsta = $vrsta;
		return $this;
	}

	public function setIdPodrucja($idPodrucja) {
		$this->idPodrucja = $idPodrucja;
		return $this;
	}

}