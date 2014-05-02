<?php

namespace view\ozsn;
use app\view\AbstractView;

class AddExistingTeamLeader extends AbstractView {
    private $errorMessage;
    private $resultMessage;
	private $osobe;
	private $idPodrucja;
    
    protected function outputHTML() {
		echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
		
?>
<form action="<?php echo \route\Route::get('d3')->generate(array(
	"controller" => "ozsn",
	"action" => "addExistingTeamLeader"
))?>" method="POST">
		<div class="panel panel-default">
			<div class="panel-heading">Članovi tima</div>

			<table class="table">
				<thead>
					<tr>
						<th>Korisničko ime</th>
						<th>Ime</th>
						<th>Prezime</th>
						<th>Uloga</th>
						<th>Proglasiti voditeljem</th>
					</tr>
				</thead>

				<tbody>
<?php
		if($this->osobe !== null && count($this->osobe))
		{
			foreach($this->osobe as $val)
			{
				$ispis = "<tr><td>" . $val->ferId . "</td><td>" . $val->ime . "</td><td>" . $val->prezime . 
						"</td><td>" . ($val->uloga == 'O' ? 'Ozsn' : 'Sudionik') . "</td><td>
							<input type=\"checkbox\" name=\"" . $val->idOsobe . "\" value=\"on\"/></td>";
				echo $ispis;
			}
		}
		else
		{
?>
						<tr>
							<td class="addAtribut" colspan="5"><i>Ne postoji niti jedna osoba!</i></td>
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

	public function setOsobe($osobe) {
		$this->osobe = $osobe;
		return $this;
	}

}