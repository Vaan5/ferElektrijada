<?php

namespace view\ozsn;
use app\view\AbstractView;

class ModifyContestant extends AbstractView {
    private $errorMessage;
    private $resultMessage;
	private $podrucjeSudjelovanja;
	private $sudjelovanje;
	private $imaatribut;
	private $imaAtribute;
	private $podrucjaSudjelovanja;
	private $smjerovi;
	private $zavodi;
	private $velicine;
	private $mjesta;
	private $godine;
	private $mjesto;
	private $godina;
	private $zavod;
	private $velicina;
	private $smjer;
	private $osoba;
	private $podrucja;
	private $atributi;
	private $korisnikovaPodrucja;

	protected function outputHTML() {
		// print messages if any
		echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
	
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));

		echo new \view\components\PersonForm(array(
			"postAction" => \route\Route::get('d3')->generate(array(
				"controller" => "ozsn",
				"action" => "modifyContestant"
			)),
			"submitButtonText" => "Ažuriraj",
			"osoba" => $this->osoba,
			"prikazSpola" => true,
			"showDelete" => false,
			"sudjelovanje" => $this->sudjelovanje,
			"showCV" => true,
			"radnaMjesta" => $this->mjesta,
			"velicine" => $this->velicine,
			"godine" => $this->godine,
			"smjerovi" => $this->smjerovi,
			"zavodi" => $this->zavodi,
			"velicina" => $this->velicina,
			"godina" => $this->godina,
			"smjer" => $this->smjer,
			"radnoMjesto" => $this->mjesto,
			"zavod" => $this->zavod,
			"showSubmit" => true,
			"showDropDown" => true,
			"controllerCV" => "ozsn",
			"idPodrucja" => $this->podrucjeSudjelovanja->idPodrucja,
			"showTip" => true,
			"podrucjeSudjelovanja" => $this->podrucjeSudjelovanja,
			"showPassword" => false
		));	
		
		// another form for attribute data
		
?>
<form class="form-horizontal" role="form" action="<?php echo \route\Route::get("d3")->generate(array(
	"controller" => "ozsn",
	"action" => "changeContestantAttributes"
));?>" method="GET">
<?php
if ($this->korisnikovaPodrucja !== null) {
?>
	
	<div class="form-group">	
                <label for="podrucja" class="col-sm-3 control-label">Disciplina</label>
		<div class="col-sm-9">
                <select name="idP" class="form-control">
			<option value="">Odaberi...</option>

<?php
		foreach($this->korisnikovaPodrucja as $val)
		{
			echo '<option value="' . $val->idPodrucja . '"';
			echo '>' . $val->nazivPodrucja . '</option>';
		}
?>					
</select></div>
        </div>
	
<?php }
?>
    <br><div class="col-sm-3"> </div><div class="col sm-9"><input type="hidden" name="idS" value="<?php echo $this->sudjelovanje->getPrimaryKey()?>">
	<input type="radio" name="vrsta" value="0"> Pojedinačno natjecanje &nbsp;
        <input type="radio" name="vrsta" value="1"> Timsko natjecanje</div><br>
        <center><input type="submit" class="btn btn-primary" value="Ažuriranje Atributa" /></center>
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

	public function setSudjelovanje($sudjelovanje) {
		$this->sudjelovanje = $sudjelovanje;
		return $this;
	}

	public function setSmjerovi($smjerovi) {
		$this->smjerovi = $smjerovi;
		return $this;
	}

	public function setZavodi($zavodi) {
		$this->zavodi = $zavodi;
		return $this;
	}

	public function setVelicine($velicine) {
		$this->velicine = $velicine;
		return $this;
	}

	public function setMjesta($mjesta) {
		$this->mjesta = $mjesta;
		return $this;
	}

	public function setGodine($godine) {
		$this->godine = $godine;
		return $this;
	}
	
	public function setMjesto($mjesto) {
		$this->mjesto = $mjesto;
		return $this;
	}

	public function setGodina($godina) {
		$this->godina = $godina;
		return $this;
	}

	public function setZavod($zavod) {
		$this->zavod = $zavod;
		return $this;
	}

	public function setVelicina($velicina) {
		$this->velicina = $velicina;
		return $this;
	}

	public function setSmjer($smjer) {
		$this->smjer = $smjer;
		return $this;
	}
	
	public function setOsoba($osoba) {
		$this->osoba = $osoba;
		return $this;
	}
	
	public function setImaatribut($imaatribut) {
		$this->imaatribut = $imaatribut;
		return $this;
	}

	public function setImaAtribute($imaAtribute) {
		$this->imaAtribute = $imaAtribute;
		return $this;
	}

	public function setPodrucjaSudjelovanja($podrucjaSudjelovanja) {
		$this->podrucjaSudjelovanja = $podrucjaSudjelovanja;
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

	public function setKorisnikovaPodrucja($korisnikovaPodrucja) {
		$this->korisnikovaPodrucja = $korisnikovaPodrucja;
		return $this;
	}

}