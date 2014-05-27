<?php

namespace view\sudionik;
use app\view\AbstractView;

class MyTeam extends AbstractView {
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
					"action" => "displayMyTeam"
				));?>">
					<div class="form-group">	
								<label for="podrucje" class="col-sm-3 control-label">Odaberite disciplinu</label>
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

		echo new \view\components\DownloadLinks(array("route" => \route\Route::get("d3")->generate(array(
			"controller" => "sudionik",
			"action" => "displayMyTeam"
		))));
?>
		<a class="btn btn-primary" href="<?php echo \route\Route::get('d3')->generate(array(
			"controller" => "sudionik",
			"action" => "displayMyTeam"))?>">Povratak</a>

		<br><br>

		<div class="panel panel-default">
			<div class="panel-heading">Članovi tima</div>

			<table class="table">
				<thead>
					<tr>
						<th>Ime</th>
						<th>Prezime</th>
						<th>Vrsta Natjecanja</th>
						<th>Rezultat</th>
					</tr>
				</thead>

				<tbody>
<?php

		if(count($this->takmicari))
		{
			// Show voditelji
			if (count($this->voditelji)) {
				foreach($this->voditelji as $val)
				{
					$ispis = "<tr style=\"background-color:#F1F8FF;\"><td>" . $val->ime . "</td><td>" . $val->prezime . "</td><td colspan=\"2\"><center><b>VODITELJ</b></center></td></tr>";
					if ($val->idOsobe == $this->osoba->idOsobe)
						$ispis = "<b>" . $ispis . "</b>";
					echo $ispis;
				}				
			}
			
			// Foreach takmicar, generate row in table
			foreach($this->takmicari as $val)
			{
				$ispis = "<tr><td>" . $val->ime . "</td><td>" . $val->prezime . "</td><td>" . 
						($val->vrstaPodrucja == '1' ? 'Timsko' : 'Pojedinačno') . "</td><td>" . $val->rezultatPojedinacni . "</td></tr>";
				if ($val->idOsobe == $this->osoba->idOsobe)
					$ispis = "<b>" . $ispis . "</b>";
				echo $ispis;
			}
		}
		else
		{
?>
						<tr>
							<td class="addAtribut" colspan="4"><i>Ne postoji ni jedan član tima</i></td>
						</tr>
<?php
		}
?>
				</tbody>
			</table>
		</div>
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