<?php

namespace view\voditelj;
use app\view\AbstractView;

class AssignExistingPerson extends AbstractView {
    private $errorMessage;
    private $resultMessage;
	private $idPodrucja;
	private $disabled;
	private $osobe;
	
	protected function outputHTML() {
		// print messages if any
		echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
	
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
	
		if ($this->disabled) {
			echo new \view\components\ErrorMessage(array(
				"errorMessage" => "Istekao rok za unos promjena!"
			));
		}

		if ($this->osobe !== null && count($this->osobe)) {
?>
<form action="<?php echo \route\Route::get('d3')->generate(array(
	"controller"=> "voditelj",
	"action" => "assignExistingPerson"
)) ?>" method ="POST">
	
	<div class="panel panel-default">
			<div class="panel-heading">Dodavanje Članova Tima</div>

			<table class="table">
				<thead>
					<tr>
						<th>Ime</th>
						<th>Prezime</th>
						<th>JMBAG</th>
						<th>Korisničko ime</th>
                                                <th><center>Opcije</center></th>
					</tr>
				</thead>

				<tbody>
	<?php
		 foreach ($this->osobe as $o) {
?>
					<tr>
						<td><?php echo $o->ime; ?></td>
						<td><?php echo $o->prezime; ?></td>
						<td><?php echo $o->JMBAG; ?></td>
						<td><?php echo $o->ferId; ?></td>
                                                <td> <center><input type="checkbox" name="osobe[]" value="<?php echo $o->getPrimaryKey();?>"></center></td>
					</tr>
			 
<?php
		 }
	?>
				</tbody>
			</table>
	</div>
	
	<div>
		<center><p> Odaberite tip natjecanja:
			&nbsp;<input type="radio" name="vrstaPodrucja" value="0" checked> Pojedinačno natjecanje
			&nbsp;<input type="radio" name="vrstaPodrucja" value="1"> Timsko natjecanje
                    </p></center>
	</div>
	
	<input type="hidden" name="idPodrucja" value="<?php echo $this->idPodrucja?>" />
	
	<?php if (!$this->disabled) { ?>
        <center><input type="submit" class="btn btn-primary" value="Dodaj" /></center>
	<?php } ?>
</form>
<?php
		} else {
			echo new \view\components\ErrorMessage(array(
				"errorMessage" => "Ne postoji niti jedna osoba u sustavu!"
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
	
	public function setIdPodrucja($idPodrucja) {
		$this->idPodrucja = $idPodrucja;
		return $this;
	}
	
	public function setDisabled($disabled) {
		$this->disabled = $disabled;
		return $this;
	}
	
	public function setOsobe($osobe) {
		$this->osobe = $osobe;
		return $this;
	}
}