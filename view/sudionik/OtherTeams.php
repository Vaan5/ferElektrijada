<?php

namespace view\sudionik;
use app\view\AbstractView;

class OtherTeams extends AbstractView {
    private $errorMessage;
    private $resultMessage;
	private $podrucja;
	private $takmicari;
	private $podrucje;
	private $voditelji;
	private $osoba;

    protected function outputHTML() {
		// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
	
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
               
		if ($this->podrucja) {
			// choose your competition area
			if (count($this->podrucja)) {
				?>
				<form class="form-horizontal" role="form" method="post" action="<?php echo \route\Route::get('d3')->generate(array(
					"controller" => "sudionik",
					"action" => "displayOtherTeams"
				));?>">
					<div class="form-group">	
								<label for="podrucje" class="col-sm-3 control-label">Odaberite disciplinu:</label>
						<div class="col-sm-9">
								<select name="idPodrucja" class="form-control">
							<option value="">Odaberi...</option>

				<?php
						foreach($this->podrucja as $val)
						{
							echo '<option value="' . $val->idPodrucja . '"';
							echo '>' . $val->nazivPodrucja . '</option>';
						}
				?>					
				</select></div>
						</div>
					<center><input type="submit" class="btn btn-primary" value="Prikaži" /></center>
				</form>
				<?php
			} else {
				echo new \view\components\ErrorMessage(array(
				   "errorMessage" => "Nije zabilježeno Vaše učešće niti u jednoj disciplini!"
				));
			}
		} else {
			// show results
?>
		<div class="panel panel-default">
			<div class="panel-heading">Članovi tima</div>

			<table class="table">
				<thead>
					<tr>
						<th>Ime</th>
						<th>Prezime</th>
						<th>Rezultat</th>
					</tr>
				</thead>

				<tbody>
<?php

		if(count($this->takmicari))
		{
			// Foreach atribut, generate row in table
			foreach($this->takmicari as $val)
			{
				$ispis = "<tr><td>" . $val->ime . "</td><td>" . $val->prezime . "</td><td>" . $val->rezultatPojedinacni . "</td></tr>";
				if ($val->idOsobe == $this->osoba->idOsobe)
					$ispis = "<b>" . $ispis . "</b>";
				echo $ispis;
			}
			
			if (count($this->voditelji)) {
?>
				<thead>
					<tr>
						<th class="addAtribut" colspan="3"><i><center>Voditelji</center></i></td>
					</tr>
				</thead>
<?php
				foreach($this->voditelji as $val)
				{
					$ispis = "<tr><td>" . $val->ime . "</td><td>" . $val->prezime . "</td><td>&nbsp;</td></tr>";
					if ($val->idOsobe == $this->osoba->idOsobe)
						$ispis = "<b>" . $ispis . "</b>";
					echo $ispis;
				}
			}
		}
		else
		{
?>
						<tr>
							<td class="addAtribut" colspan="3"><i>Ne postoji ni jedan atribut</i></td>
						</tr>
<?php
		}
?>
				</tbody>
			</table>
		</div>
<?php

		echo new \view\components\DownloadLinks(array("route" => \route\Route::get("d3")->generate(array(
			"controller" => "sudionik",
			"action" => "displayOtherTeams"
		))));
			?>
				<a href="<?php echo \route\Route::get('d3')->generate(array(
					"controller" => "sudionik",
					"action" => "displayOtherTeams"))?>">Povratak</a>
			<?php
		}
    }
    
    public function setErrorMessage($errorMessage) {
		$this->errorMessage = $errorMessage;
		return $this;
    }

    public function setResultMessage($resultMessage) {
		$this->resultMessage = $resultMessage;
		return $this;
    }
	
	public function setPodrucja($podrucja) {
		$this->podrucja = $podrucja;
		return $this;
	}

	public function setTakmicari($takmicari) {
		$this->takmicari = $takmicari;
		return $this;
	}

	public function setPodrucje($podrucje) {
		$this->podrucje = $podrucje;
		return $this;
	}

	public function setVoditelji($voditelji) {
		$this->voditelji = $voditelji;
		return $this;
	}

	public function setOsoba($osoba) {
		$this->osoba = $osoba;
		return $this;
	}
}