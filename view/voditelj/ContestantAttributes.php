<?php

namespace view\voditelj;
use app\view\AbstractView;

class ContestantAttributes extends AbstractView {
    private $errorMessage;
    private $resultMessage;
	private $podrucjeSudjelovanja;
	private $idSudjelovanja;
	private $idPodrucja;
	private $podrucja;
	private $atributi;
	private $korisnikoviAtributi;

	protected function outputHTML() {
		// print messages if any
		echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
	
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
?>
		<form class="form-horizontal" role="form" action="<?php echo \route\Route::get("d3")->generate(array(
			"controller" => "voditelj",
			"action" => "changeContestantAttributes"
		));?>" method="POST">
			
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
                        <center><input type="submit" class="btn btn-primary" value="Ažuriraj" /></center>
                </form><br>
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

	public function setIdPodrucja($idPodrucja) {
		$this->idPodrucja = $idPodrucja;
		return $this;
	}

}