<?php

namespace view\voditelj;
use app\view\AbstractView;

class ModifyResults extends AbstractView {
    private $errorMessage;
    private $resultMessage;
	private $takmicari;
	private $idPodrucja;
	
	protected function outputHTML() {
		// print messages if any
		echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
	
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));

?>
<form action="<?php echo \route\Route::get('d3')->generate(array(
	"controller" => "voditelj",
	"action" => "modifyResults"
))?>" method="POST">
		<div class="panel panel-default">
			<div class="panel-heading">Članovi tima</div>

			<table class="table">
				<thead>
					<tr>
						<th>Korisničko ime</th>
						<th>Ime</th>
						<th>Prezime</th>
						<th>Vrsta natjecanja</th>
						<th>Rezultat</th>
						<th>Broj sudionika</th>
					</tr>
				</thead>

				<tbody>
<?php
		if($this->takmicari !== null && count($this->takmicari))
		{
			foreach($this->takmicari as $val)
			{
				$ispis = "<tr><td>" . $val->ferId . "</td><td>" . $val->ime . "</td><td>" . $val->prezime . 
						"</td><td>" . ($val->vrstaPodrucja == '1' ? 'Timsko' : 'Pojedinačno') . "</td><td>
							<input type=\"text\" name=\"" . $val->idPodrucjeSudjelovanja . "\" value=\"" . 
						($val->rezultatPojedinacni === NULL ? "" : $val->rezultatPojedinacni) ."\"/></td><td>
							<input type=\"text\" name=\"b" . $val->idPodrucjeSudjelovanja . "\" value=\"" . 
						($val->ukupanBrojSudionika === NULL ? "" : $val->ukupanBrojSudionika) ."\"/>";
				echo $ispis;
			}
		}
		else
		{
?>
						<tr>
							<td class="addAtribut" colspan="6"><i>Ne postoji niti jedan natjecatelj za izabrano područje!</i></td>
						</tr>
<?php
		}
?>
				</tbody>
			</table>
		</div>
	<input type="hidden" name="idPodrucja" value="<?php echo $this->idPodrucja?>"/>
	<center><input type="submit" class="btn btn-primary" value="Spremi" /></center>
</form>
<?php
		echo new \view\components\DownloadLinks(array("route" => \route\Route::get("d3")->generate(array(
			"controller" => "voditelj",
			"action" => "modifyResults"
		)) . "?id=" . $this->idPodrucja,
			"onlyParam" => false));
	}
	
	public function setErrorMessage($errorMessage) {
		$this->errorMessage = $errorMessage;
		return $this;
	}

	public function setResultMessage($resultMessage) {
		$this->resultMessage = $resultMessage;
		return $this;
	}
	
	public function setIdPodrucja($idPodrucja) {
		$this->idPodrucja = $idPodrucja;
		return $this;
	}

	public function setTakmicari($takmicari) {
		$this->takmicari = $takmicari;
		return $this;
	}

}