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
		
		
		echo new \view\components\DownloadLinks(array(
			"route" => \route\Route::get("d3")->generate(array(
				"controller" => "ozsn",
				"action" => "displayContactInfo"
			)) . "?idKontakta=" . $this->kontakt->idKontakta,
			"onlyParam" => false
		));

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