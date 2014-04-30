<?php

namespace view\voditelj;
use app\view\AbstractView;

class TeamMembers extends AbstractView {
    private $errorMessage;
    private $resultMessage;
	private $takmicari;
	private $idPodrucja;
	private $disabled;


    protected function outputHTML() {
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
	
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
		
?>
		<div class="panel panel-default">
			<div class="panel-heading">Članovi tima</div>

			<table class="table">
				<thead>
					<tr>
						<th>Korisničko ime</th>
						<th>Ime</th>
						<th>Prezime</th>
						<th>JMBAG</th>
						<th>Rezultat</th>
						<th>Opcije</th>
					</tr>
				</thead>

				<tbody>
<?php

		if($this->takmicari !== null && count($this->takmicari))
		{
			foreach($this->takmicari as $val)
			{
				$ispis = "<tr><td>" . $val->ferId . "</td><td>" . $val->ime . "</td><td>" . $val->prezime . 
						"</td><td>" . $val->JMBAG . "</td><td>" . $val->rezultatPojedinacni . "</td>";
				$ispis .= "<td><a href=\"" . \route\Route::get('d3')->generate(array(
					"controller" => "voditelj",
					"action" => "modifyContestant"
				)) . "?id=" . $this->idPodrucja ."&idO=". $val->idOsobe ."\">Uredi</a></td></tr>";
				echo $ispis;
			}
		}
		else
		{
?>
						<tr>
							<td class="addAtribut" colspan="2"><i>Ne postoji niti jedan antjecatelj za izabrano područje!</i></td>
						</tr>
<?php
		}
?>
				</tbody>
			</table>
		</div>
<?php

		echo new \view\components\DownloadLinks(array(
			"route" => \route\Route::get("d3")->generate(array(
														"controller" => "voditelj",
														"action" => "displayTeam"
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

	public function setTakmicari($takmicari) {
		$this->takmicari = $takmicari;
		return $this;
	}
	
	public function setIdPodrucja($idPodrucja) {
		$this->idPodrucja = $idPodrucja;
		return $this;
	}

	public function setDisabled($disabled) {
		$this->disabled = $disabled;
		return $this;
	}

}