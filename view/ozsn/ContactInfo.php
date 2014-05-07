<?php

namespace view\ozsn;
use app\view\AbstractView;

class ContactInfo extends AbstractView {
    private $errorMessage;
    private $resultMessage;
	private $kontakti;
	private $kontakt;
	private $mobiteli;
	private $mailovi;
    
    protected function outputHTML() {
		// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
		
		// ako su mobiteli ili mailovi === null ispisujes formu sa postojecim kontaktima (svaki nek ima jedan radio bbutton)
		// MOZE SE KLIKNUTI SAMO JEDAN OD NJIH
		// inace (mobiteli ili mailovi nisu null nego array() ili neprazan array)
		// Na neki nacin ispisi osnovne podatke o korisniku i njegove mailove i brojeve mobitela
		// Dodaj downloadLinks
		
		
		var_dump($this->kontakt);
		var_dump($this->mailovi);
		var_dump($this->mobiteli);
		if($this->mailovi === NULL || $this->mobiteli === NULL)
		{
			echo new \view\components\DownloadLinks(array(
				"route" => \route\Route::get("d3")->generate(array(
					"controller" => "ozsn",
					"action" => "displayContactInfo"
				))
			)); 
?>

			<br><br>

			<div class="panel panel-default">
				<div class="panel-heading">Kontakt osobe</div>

				<table class="table">
					<thead>
						<tr>
							<th>Ime</th>
							<th>Prezime</th>
							<th>Radno mjesto</th>
							<th>Telefon</th>
							<th>Opcije</th>
						</tr>
					</thead>

					<tbody>
<?php
			foreach($this->kontakti as $val)
			{
				echo '<tr><td>' . $val->imeKontakt . '</td>';
				echo '<td>' . $val->prezimeKontakt . '</td>';
				echo '<td>' . $val->radnoMjesto . '</td>';
				echo '<td>' . $val->telefon . '</td>';
				echo '<td><a href="javascript:;" class="prikaziDetalje" data-id="' . $val->idKontakta . '">Prikaži detalje</a>';
			}
			
			echo '</tbody></table></div>';
		}
		
		else		
		{
			echo '<h2>' . $this->kontakt->imeKontakt . ' ' . $this->kontakt->prezimeKontakt . '</h2>';
?>
					<b>Radno mjesto:</b> <?php echo $this->kontakt->radnoMjesto; ?><br>
					<b>Telefon:</b> <?php echo $this->kontakt->telefon; ?>
					
					<?php if(count($this->mobiteli)) { ?>
					<table>
						<tr>
							<td valign="top"><b>Brojevi mobitela: &nbsp;</b></td>
							<td>
<?php
				foreach($this->mobiteli as $val)
				{
					echo $val->broj . '<br>';
				}
?>
							</td>
						</tr>
					</table>
					<?php } else { ?>
					
					<br><b>Brojevi mobitela: </b> <i>Nema brojeva</i>
					
					<?php } ?>
					
					<?php if(count($this->mailovi)) { ?>
					<table>
						<tr>
							<td valign="top"><b>E-mail adrese: &nbsp;</b></td>
							<td>
<?php
				foreach($this->mailovi as $val)
				{
					echo $val->email . '<br>';
				}
?>
							</td>
						</tr>
					</table>
					<?php } else { ?>
					
					<br><b>E-mail adrese: </b> <i>Nema e-mail adresa</i>
					
					<?php } ?>
					
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
	
	public function setKontakti($kontakti) {
		$this->kontakti = $kontakti;
		return $this;
	}

	public function setKontakt($kontakt) {
		$this->kontakt = $kontakt;
		return $this;
	}

	public function setMobiteli($mobiteli) {
		$this->mobiteli = $mobiteli;
		return $this;
	}

	public function setMailovi($mailovi) {
		$this->mailovi = $mailovi;
		return $this;
	}
}