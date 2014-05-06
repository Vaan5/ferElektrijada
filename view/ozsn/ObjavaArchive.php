<?php

namespace view\ozsn;
use app\view\AbstractView;

class ObjavaArchive extends AbstractView {
    private $errorMessage;
    private $resultMessage;
	private $elektrijade;
	private $rezultati;
    
    protected function outputHTML() {
		// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
		
		if($this->rezultati === NULL)
		{
?>
			<form class="form-horizontal" role="form" method="post" action="
				<?php echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'displayObjavaReport'
				));?>">
				
				<div class="form-group">
					<label for="elektrijada" class="col-sm-3 control-label">Odaberi elektrijadu</label>
					<div class="col-sm-9">
						<select name="idElektrijade" class="form-control">
							<option selected="selected" value="">Odaberi...</option>

<?php
		foreach($this->elektrijade as $val)
		{
			echo '<option value="' . $val->idElektrijade . '">' . $val->mjestoOdrzavanja . ' ' . date('Y', strtotime($val->datumPocetka)) . '</option>';
		}
?>					
						</select>
					</div>
				</div>
				
				<center><input type="submit" class="btn btn-primary" value="Prikaži objave" /></center>
			</form>
<?php
		}
		
		else
		{
			if(count($this->rezultati))
			{
	?>

			<?php echo new \view\components\DownloadLinks(array(
				"route" => \route\Route::get("d3")->generate(array(
					"controller" => "ozsn",
					"action" => "displayObjavaReport"
				))
			)); ?>

			<br><br>

			<div class="panel panel-default">
				<div class="panel-heading">Popis objava</div>

				<table class="table">
					<thead>
						<tr>
							<th>Autor</th>
							<th>Medij</th>
							<th>Datum</th>
							<th>Link</th>
							<th>Dokument</th>
						</tr>
					</thead>

					<tbody>
<?php
				// Foreach sponzor, generate row in table
				foreach($this->rezultati as $val)
			{
?>
						<tr>
							<td><?php echo $val->autorIme . ' ' . $val->autorPrezime; ?></td>
							<td><?php echo $val->nazivMedija; ?></td>
							<td><?php echo date('d.m.Y', strtotime($val->datumObjave)); ?></td>
							<td><?php if ($val->link) echo '<a href="'  . $val->link . '" target="_blank">Link</a>'; else echo '<i>Ne postoji</i>'; ?></td>
							<td>
								<?php if ($val->dokument) { ?>
								<a href="<?php echo \route\Route::get('d3')->generate(array(
									"controller" => 'ozsn',
									"action" => 'download'
								));?>?id=<?php echo $val->idObjave; ?>">Preuzmi</a>
								<?php } else echo '<i>Ne postoji</i>'; ?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
<?php
			}
		}
		
		else
		{
			echo new \view\components\ErrorMessage(array(
				"errorMessage" => 'Za ovu elektrijadu ne postoji niti jedna objava!'
			));
		}
	}
		
		// napravi nesh slicno DBM-u
		// dakle ako rezultati nisu postavljeni (null) prikazujes post formu sa drop down om elektrijada
		// inace prikazujes rezultate (ako je prazno polje ispisi poruku)
		// dodaj download linkove i nista vise (ne treba uredjivanje i ostalo...)
    }
    
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    public function setResultMessage($resultMessage) {
        $this->resultMessage = $resultMessage;
        return $this;
    }
	
	public function setElektrijade($elektrijade) {
		$this->elektrijade = $elektrijade;
		return $this;
	}

	public function setRezultati($rezultati) {
		$this->rezultati = $rezultati;
		return $this;
	}
	
}