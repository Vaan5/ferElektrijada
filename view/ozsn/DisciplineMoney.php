<?php

namespace view\ozsn;
use app\view\AbstractView;

class DisciplineMoney extends AbstractView {
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
	"action" => "disciplineMoney"
))?>" method="POST">
		<div class="panel panel-default">
			<div class="panel-heading">Članovi tima</div>

			<table class="table">
				<thead>
					<tr>
						<th>Korisničko ime</th>
						<th>Ime</th>
						<th>Prezime</th>
						<th>Iznos</th>
					</tr>
				</thead>

				<tbody>
<?php
		if($this->osobe !== null && count($this->osobe))
		{
			foreach($this->osobe as $val)
			{
				echo "<tr><td>" . $val->ferId . "</td><td>" . $val->ime . "</td><td>" . $val->prezime . 
						"</td><td>";
?>
						<div class="form-group">
			<div class="col-sm-9">
				<div class="input-group">
					<input type="text" name="<?php echo $val->idPodrucjeSudjelovanja?>" class="form-control" placeholder="Upišite iznos uplate" <?php if($val && $val->iznosUplate) echo 'value="' . $val->iznosUplate . '"' ?> />
					
					<div class="input-group-btn">
						<select name="valuta<?php echo $val->idPodrucjeSudjelovanja?>" class="form-control btn btn-default" style="width:80px;">
						<option <?php if(!$val || ($val && $val->valuta == 'HRK')) echo 'selected="selected"' ?> value="HRK">HRK</option>
						<option <?php if($val && $val->valuta == 'USD') echo 'selected="selected"' ?> value="USD">USD</option>
						<option <?php if($val && $val->valuta == 'EUR') echo 'selected="selected"' ?> value="EUR">EUR</option>
						</select>
					</div>
					
					</div>
			</div>
		</div>
<?php
				echo "</td></tr>";
			}
		}
		else
		{
?>
			<tr>
				<td class="addAtribut" colspan="4"><i>Ne postoji niti jedan natjecatelj za izabrano područje!</i></td>
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
			"controller" => "ozsn",
			"action" => "disciplineMoney"
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
	
	public function setOsobe($osobe) {
		$this->osobe = $osobe;
		return $this;
	}

}