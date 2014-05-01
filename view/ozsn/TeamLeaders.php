<?php

namespace view\ozsn;
use app\view\AbstractView;

class TeamLeaders extends AbstractView {
    private $errorMessage;
    private $resultMessage;
	private $voditelji;
	private $podrucja;
    
    protected function outputHTML() {
		echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
		
		if ($this->voditelji !== null && count($this->voditelji)) {
?>
		<div class="panel panel-default">
			<div class="panel-heading">Voditelji Disciplina</div>

			<table class="table">
				<thead>
					<tr>
						<th>Korisničko ime</th>
						<th>Ime</th>
						<th>Prezime</th>
						<th>JMBAG</th>
						<th>Tip</th>
						<th>Područje</th>
						<th>Opcije</th>
					</tr>
				</thead>

				<tbody>
<?php
		if($this->voditelji !== null && count($this->voditelji))
		{
			foreach($this->voditelji as $val)
			{
				$ispis = "<tr><td>" . $val->ferId . "</td><td>" . $val->ime . "</td><td>" . $val->prezime . 
						"</td><td>" . $val->JMBAG . "</td><td>" . ($val->tip == "S" ? "Student" : ($val->tip == "D" ? "Djelatnik" : "Ozsn")) . "</td><td>" . 
						$val->nazivPodrucja . "</td>";
				$ispis .= "<td><a href=\"" . \route\Route::get('d3')->generate(array(
					"controller" => "ozsn",
					"action" => "modifyTeamLeader"
				)) . "?idS=". $val->idSudjelovanja . "&idA=". $val->idImaAtribut ."\">Uredi</a>&nbsp;";
				$ispis .= "&nbsp;<a href=\"" . \route\Route::get('d3')->generate(array(
					"controller" => "ozsn",
					"action" => "removeTeamLeader"
				)) . "?idA=" . $val->idImaAtribut . "&idS=". $val->idSudjelovanja ."\">Briši</a></td></tr>";
				echo $ispis;
			}
		}
		else
		{
?>
						<tr>
							<td class="addAtribut" colspan="7"><i>Ne postoji niti jedno područje!</i></td>
						</tr>
<?php
		}
?>
						<tr>
							<td class="addAtribut" colspan="7">
								<form action="<?php echo \route\Route::get('d3')->generate(array(
									"controller" => "ozsn",
									"action" => "addTeamLeader"
								)) ?>" method="POST">
									<div class="form-group">	
                <label for="podrucje" class="col-sm-3 control-label">Područje</label>
		<div class="col-sm-9">
                <select name="idDolazak" class="form-control">
<?php
if (count($this->podrucja)) {
		foreach($this->podrucja as $val)
		{
			echo '<option value="' . $val->idPodrucja . '"';
			echo '>' . $val->nazivPodrucja . '</option>';
		}
}
?>					
</select></div>
        </div>
									<input type="submit" value="Dodaj Novog Voditelja"></form></td>
						</tr>
						<tr>
							<td class="addAtribut" colspan="7">
								<form action="<?php echo \route\Route::get('d3')->generate(array(
									"controller" => "ozsn",
									"action" => "addExistingTeamLeader"
								)) ?>" method="POST">
									<div class="form-group">	
                <label for="podrucje" class="col-sm-3 control-label">Područje</label>
		<div class="col-sm-9">
                <select name="idDolazak" class="form-control">
<?php
if (count($this->podrucja)) {
		foreach($this->podrucja as $val)
		{
			echo '<option value="' . $val->idPodrucja . '"';
			echo '>' . $val->nazivPodrucja . '</option>';
		}
}
?>					
</select></div>
        </div>
									<input type="submit" value="Proglasi postojećeg korisnika voditeljem"></form></td>
						</tr>
				</tbody>
			</table>
		</div>


<?php

		echo new \view\components\DownloadLinks(array(
			"route" => \route\Route::get("d3")->generate(array(
														"controller" => "ozsn",
														"action" => "displayTeamLeaders"
													))));
		} else {
?>					<div class="panel panel-default">
			<div class="panel-heading">Voditelji Disciplina</div>

			<table class="table">
						<tr>
							<td class="addAtribut" colspan="7">
								<form action="<?php echo \route\Route::get('d3')->generate(array(
									"controller" => "ozsn",
									"action" => "addTeamLeader"
								)) ?>" method="POST">
									<div class="form-group">	
                <label for="podrucje" class="col-sm-3 control-label">Područje</label>
		<div class="col-sm-9">
                <select name="idDolazak" class="form-control">
<?php
if (count($this->podrucja)) {
		foreach($this->podrucja as $val)
		{
			echo '<option value="' . $val->idPodrucja . '"';
			echo '>' . $val->nazivPodrucja . '</option>';
		}
}
?>					
</select></div>
        </div>
									<input type="submit" value="Dodaj"></form></td>
						</tr>
						<tr>
							<td class="addAtribut" colspan="7">
								<form action="<?php echo \route\Route::get('d3')->generate(array(
									"controller" => "ozsn",
									"action" => "addExistingTeamLeader"
								)) ?>" method="POST">
									<div class="form-group">	
                <label for="podrucje" class="col-sm-3 control-label">Područje</label>
		<div class="col-sm-9">
                <select name="idDolazak" class="form-control">
<?php
if (count($this->podrucja)) {
		foreach($this->podrucja as $val)
		{
			echo '<option value="' . $val->idPodrucja . '"';
			echo '>' . $val->nazivPodrucja . '</option>';
		}
}
?>					
</select></div>
        </div>
									<input type="submit" value="Proglasi postojećeg korisnika voditeljem"></form></td>
						</tr>
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
	
	public function setVoditelji($voditelji) {
		$this->voditelji = $voditelji;
		return $this;
	}

	public function setPodrucja($podrucja) {
		$this->podrucja = $podrucja;
		return $this;
	}

}