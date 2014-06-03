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
		
		
		echo '<center><div style="width:400px; text-align:right">'.new \view\components\DownloadLinks(array(
			"route" => \route\Route::get("d3")->generate(array(
				"controller" => "ozsn",
				"action" => "displayContactInfo"
			)) . "?idKontakta=" . $this->kontakt->idKontakta,
			"onlyParam" => false
		)).'</div></center>';
                ?><br><br><br><center><div class="well" style="width:400px; text-align:left;">
		<?php echo '<h2 style="margin-top:0px;">' . $this->kontakt->imeKontakt . ' ' . $this->kontakt->prezimeKontakt . '</h2>'; 
?>
                    <h4>Radno mjesto:<span style="color:grey;">  <?php echo $this->kontakt->radnoMjesto; ?></span></h4>
				<h4>Telefon:<span style="color:grey;"> <?php if($this->kontakt->telefon) echo $this->kontakt->telefon; else echo '<i>Nema broja telefona</i>'; ?></span></h4>

				<?php if(count($this->mobiteli)) { ?>
				<table>
					<tr>
						<td valign="top"><h4 style="margin-top:0px;">Brojevi mobitela: &nbsp;</h4></td>
						<td><h4 style="margin-top:0px;"><span style="color:grey;">
<?php
			foreach($this->mobiteli as $val)
			{
				echo $val->broj . '<br>';
			}
?>
						</span></h4></td>
					</tr>
				</table>
				<?php } else { ?>

                                <h4 style="margin-top:0px;">Brojevi mobitela: &nbsp;<span style="color:grey;"><i>Nema brojeva</i></span></h4>

				<?php } ?>

				<?php if(count($this->mailovi)) { ?>
				<table>
					<tr>
						<td valign="top"><h4 style="margin-top:0px;">E-mail adrese: &nbsp;</h4></td>
						<td><h4 style="margin-top:0px;"><span style="color:grey;">
<?php
			foreach($this->mailovi as $val)
			{
				echo '<a href="mailto:'.$val->email.'">'.$val->email.'</a>'.'<br>';
			}
?>
						</span></h4></td>
					</tr>
				</table>
				<?php } else { ?>

                                <h4 style="margin-top:0px;">E-mail adrese:  &nbsp;<span style="color:grey;"><i>Nema e-mail adresa</i></span></h4>
                        </div></center>
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