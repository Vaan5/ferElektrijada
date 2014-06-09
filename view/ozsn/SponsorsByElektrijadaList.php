<?php

namespace view\ozsn;
use app\view\AbstractView;

class SponsorsByElektrijadaList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $elektrijade;
    private $sponzori;
    
    protected function outputHTML() {	
		// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
		
		if($this->sponzori === NULL)
		{
?>
			<form class="form-horizontal" role="form" method="post" action="
				<?php echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'displaySponzorsByElektrijada'
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
				
				<center><input type="submit" class="btn btn-primary" value="Prikaži sponzore" /></center>
			</form>
<?php
		}
		
		else
		{
			if(count($this->sponzori))
			{
	?>

			<?php echo new \view\components\DownloadLinks(array(
				"route" => \route\Route::get("d3")->generate(array(
					"controller" => "ozsn",
					"action" => "displaySponzorsByElektrijada"
				))
			)); ?>

			<br><br>

			<div class="panel panel-default">
				<div class="panel-heading">Popis sponzora</div>

				<table class="table">
					<thead>
						<tr>
							<th>Tvrtka</th>
							<th>Kategorija</th>
							<th>Način</th>
							<th>Sponzorira</th>
							<th>Iznos</th>
						</tr>
					</thead>

					<tbody>
<?php
				// Foreach sponzor, generate row in table
				foreach($this->sponzori as $val)
				{
?>
						<tr>
							<td><?php echo $val->imeTvrtke; ?></td>
							<td><?php if($val->tipKategorijeSponzora) echo $val->tipKategorijeSponzora; else echo '-'; ?></td>
							<td><?php if($val->tipPromocije) echo $val->tipPromocije; else echo '-'; ?></td>
							<td><?php if($val->idPodrucja) echo $val->nazivPodrucja; else echo 'Elektrijada'; ?></td>
							<td><?php echo $val->iznosDonacije . ' ' . $val->valutaDonacije; ?></td>
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
				"errorMessage" => 'Za ovu elektrijadu ne postoji niti jedan sponzor!'
			));
		}
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
    
    public function setSponzori($sponzori) {
	$this->sponzori = $sponzori;
	return $this;
    }
    public function setElektrijade($elektrijade) {
	$this->elektrijade = $elektrijade;
	return $this;
    }

}