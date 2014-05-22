<?php

namespace view\ozsn;
use app\view\AbstractView;

class HallOfFame extends AbstractView {
    private $errorMessage;
    private $resultMessage;
	private $rezultati;
    
    protected function outputHTML() {
		// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
		
		$brPodrucja = NULL;
		
		// izgeneriraj prikaz rezultata: dakle ukupan za cijelu elektrijadu, ukupan za znanje i ostale korijenske discipline (idNadredjene  = NULL)
		// + za svako podrucje rezultat
		// ISPITAJ KRAJNJE SLUCAJEVE
		
		// Separate all elektrijade into another array
		foreach($this->rezultati as $val)
		{
			if(!isset($elektrijade[$val->datumPocetka]))
			{
				$elektrijade[$val->datumPocetka] = array(
					"idElektrijade" => $val->idElektrijade,
					"mjestoOdrzavanja" => $val->mjestoOdrzavanja,
					"datumPocetka" => $val->datumPocetka,
					"datumKraja" => $val->datumKraja,
					"ukupniRezultat" => $val->ukupniRezultat,
					"rokZaZnanje" => $val->rokZaZnanje,
					"rokZaSport" => $val->rokZaSport,
					"drzava" => $val->drzava,
					"ukupanBrojSudionika" => $val->ukupanBrojSudionika
				);
			}
			
			if(isset($val->idPodrucja))
			{
				if(!isset($brPodrucja[$val->idElektrijade])) $brPodrucja[$val->idElektrijade] = 0;
				$brPodrucja[$val->idElektrijade]++;
			}
		}
		
		foreach($elektrijade as $val)
		{
?>
		<div class="panel panel-default">
			<div class="panel-heading"><?php echo $val["mjestoOdrzavanja"] . ' ' . date('Y', strtotime($val["datumPocetka"])); ?></div>
			
			<div class="panel-body">
				<div style="float:left;padding-right:200px;">
					<p><b>Mjesto:</b> <?php echo $val["mjestoOdrzavanja"] . ', ' . $val["drzava"]; ?></p>
					<p><b>Datum početka:</b> <?php echo date('d.m.Y', strtotime($val["datumPocetka"])); ?></p>
					<p><b>Datum kraja:</b> <?php echo date('d.m.Y', strtotime($val["datumKraja"])); ?></p>
				</div>
				<div style="float:left;">
					<p><b>Broj sudionika:</b> <?php echo $val["ukupanBrojSudionika"]; ?></p>
					<p><b>Ukupni rezultat:</b> <?php echo $val["ukupniRezultat"]; ?></p>
				</div>
			</div>
<?php
			if(isset($brPodrucja[$val["idElektrijade"]]))
			{
?>
			<table class="table">
				<thead>
					<tr>
						<th>Disciplina</th>
						<th>Broj ekipa</th>
						<th>Rezultat</th>
						<th>Slika</th>
					</tr>
					
					<tbody>
<?php
				foreach($this->rezultati as $val2)
				{
					if($val2->idElektrijade == $val["idElektrijade"] && $val2->idNadredjenog == NULL)
					{
						echo '<tr><td>' . $val2->nazivPodrucja . '</td><td>' . $val2->ukupanBrojEkipa . '</td><td>' . $val2->rezultatGrupni . '</td><td>';
						if($val2->slikaLink) echo '<a class="fancyboxLoader" href="' . $val2->slikaLink . '">Prikaži</a>';
						else echo '<i>Ne postoji</i>';
						echo '</td></tr>';
						
						foreach($this->rezultati as $val3)
						{
							if($val3->idElektrijade == $val2->idElektrijade && $val3->idNadredjenog == $val2->idPodrucja)
							{
								echo '<tr><td>&nbsp; <img width="13px" style="margin-top:-6px;" src="../assets/img/subcategory.png"> ' . $val3->nazivPodrucja . '</td><td>' . $val3->ukupanBrojEkipa . '</td><td>' . $val3->rezultatGrupni . '</td><td>';
								if($val3->slikaLink) echo '<a class="fancyboxLoader" href="' . $val3->slikaLink . '">Prikaži</a>';
								else echo '<i>Ne postoji</i>';
								echo '</td></tr>';
							}
						}
					}
				}	
?>
					</tbody>
				</thead>
			</table>
<?php		} ?>
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
	
	public function setRezultati($rezultati) {
		$this->rezultati = $rezultati;
		return $this;
	}

}