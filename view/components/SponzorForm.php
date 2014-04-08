<?php

namespace view\components;
use app\view\AbstractView;

class SponzorForm extends AbstractView {
    private $route;
    private $submitButtonText;
    private $kategorije;
    private $promocije;

    protected function outputHTML() {
?>
    <form method="post" action="<?php echo $this->route;?>" enctype="multipart/form-data">
		<label for="kategorija">Kategorija</label><br>
		<select name="idKategorijeSponzora">
			<option selected="selected" value="" disabled>Odaberi...</option>
<?php
		foreach($this->kategorije as $val)
		{
			echo '<option value="' . $val->idKategorijeSponzora . '">' . $val->tipKategorijeSponzora . '</option>';
		}
?>					
		</select><br><br>
		
		<label for="nacinPromocije">Način promocije</label><br>
		<select name="idPromocije">
			<option selected="selected" value="" disabled>Odaberi...</option>
<?php
		foreach($this->promocije as $val)
		{
			echo '<option value="' . $val->idPromocije . '">' . $val->tipPromocije . '</option>';
		}
?>					
		</select><br><br>
		
		<label for="imeTvrtke">Ime tvrtke</label><br>
		<input type="text" name="imeTvrtke" placeholder="Upišite ime tvrtke" />
		
		<br><br>
		
		<label for="adresaTvrtke">Adresa tvrtke</label><br>
		<input type="text" name="adresaTvrtke" placeholder="Upišite adresu tvrtke" />
		
		<br><br>
		
		<label for="logotip">Logotip</label><br>
		<input type="file" class="btn btn-default" name="datoteka" />
		
		<br><br>
		
		<label for="iznosDonacije">Iznos donacije</label><br>
		<input type="text" name="iznosDonacije" placeholder="Upišite iznos donacije" />
		
		<br><br>
		
		<label for="valutaDonacije">Valuta donacije</label><br>
		<select name="valutaDonacije">
			<option selected="selected" disabled>Odaberi...</option>
			<option value="HRK">HRK</option>
			<option value="USD">USD</option>
			<option value="EUR">EUR</option>
		</select>
		
		<br><br>
		
		<label for="napomena">Napomena</label><br>
		<textarea name="napomena"></textarea>
		
		<br>
			
		<input type="submit" class="btn btn-primary" value="<?php echo $this->submitButtonText; ?>" />
    </form>
<?php
    }
    
    public function setRoute($route) {
        $this->route = $route;
        return $this;
    }

    public function setSubmitButtonText($submitButtonText) {
        $this->submitButtonText = $submitButtonText;
        return $this;
    }
    
    public function setKategorije($kategorije) {
	$this->kategorije = $kategorije;
	return $this;
    }

    public function setPromocije($promocije) {
	$this->promocije = $promocije;
	return $this;
    }    

}