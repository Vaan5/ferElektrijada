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
		
		echo new \view\components\AddNewLink(array(
			"link" => "javascript:;",
			"class" => "dodajVoditelja",
			"buttonText" => 'Dodaj voditelja'
		));
		
?>
<div style="float:left;">          
<form class="form-inline addForm" role="form" method="POST" action="<?php echo \route\Route::get('d3')->generate(array(
									"controller" => "ozsn",
									"action" => "addTeamLeader"
								)) ?>">
			<div  style="display:none; float:left;" class="input-append dodajVoditeljaOn">
			<div class="form-group">
				<select class="form-control" name="idDolazak">
                        
<?php
			if (count($this->podrucja)) {
				foreach($this->podrucja as $val)
				{
					echo '<option value="' . $val->idPodrucja . '"';
					echo '>' . $val->nazivPodrucja . '</option>';
				}
			}
?>					
				</select>
                        </div>
			<div class="form-group">	
                            <select name="type" class="form-control chooseType">
					<option value="novi">Novi korisnik</option>
					<option value="postojeci">Postojeći korisnik</option>
				</select>
                        </div>
				<input type="submit" class="btn btn-default" value="Dodaj">
			</div>
    </form>	
</div>
<!--<br><br>-->

<?php
		
		if ($this->voditelji !== null && count($this->voditelji)) {
                        echo '<div style="float:right;">'.new \view\components\DownloadLinks(array(
				"route" => \route\Route::get("d3")->generate(array(
					"controller" => "ozsn",
					"action" => "displayTeamLeaders"
				)))).'</div>';
?>	

		<div style="clear:both;" class="panel panel-default">
			<div class="panel-heading">Voditelji Disciplina</div>

			<table class="table">
				<thead>
					<tr>
						<th>Disciplina</th>
						<th>Korisnik</th>
						<th>Ime</th>
						<th>Prezime</th>
						<th>JMBAG</th>
						<th>Tip</th>
						<th>Opcije</th>
					</tr>
				</thead>

				<tbody>
<?php
		if($this->voditelji !== null && count($this->voditelji))
		{
			foreach($this->voditelji as $val)
			{
				$ispis = "<tr><td>" . $val->nazivPodrucja . "<td>" . $val->ferId . "</td><td>" . $val->ime . "</td><td>" . $val->prezime . 
						"</td><td>" . $val->JMBAG . "</td><td>" . ($val->tip == "S" ? "Student" : ($val->tip == "D" ? "Djelatnik" : "Ozsn")) . "</td>";
				$ispis .= "<td style=".'"width:140px;"'."><a href=\"" . \route\Route::get('d3')->generate(array(
					"controller" => "ozsn",
					"action" => "modifyTeamLeader"
				)) . "?idS=". $val->idSudjelovanja . "&idA=". $val->idImaAtribut ."\">".'<span class="glyphicon glyphicon-pencil"></span>'." Uredi</a>&nbsp;";
				$ispis .= "&nbsp;<a class=\"deleteTeamLeader\" href=\"" . \route\Route::get('d3')->generate(array(
					"controller" => "ozsn",
					"action" => "removeTeamLeader"
				)) . "?idA=" . $val->idImaAtribut . "&idS=". $val->idSudjelovanja ."\">".'<span class="glyphicon glyphicon-remove"></span>'." Obriši</a></td></tr>";
				echo $ispis;
			}
		}
		
		else
		{
?>
						<tr>
							<td class="addAtribut" colspan="7"><i>Ne postoji niti jedan voditelj!</i></td>
						</tr>
<?php
		}
?>
				</tbody>
			</table>
		</div>



<?php
		}
		
		else
		{
			echo new \view\components\ErrorMessage(array(
				"errorMessage" => "Ne postoji niti jedan zapis o voditeljima područja"
			));
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