<?php

namespace view\components;
use app\view\AbstractView;

class AreaSponzorForm extends AbstractView {
    private $route;
    private $submitButtonText;
	private $podrucja;
	private $sponzori;
    private $sponelekpod;


    protected function outputHTML() {
?>

	<form method="post" action="<?php echo $this->route;?>">
<?php
		if(!$this->sponelekpod)
		{
?>
		<label for="sponzor">Sponzor</label><br>
		<select name="idSponzora">
			<option selected="selected" value="" disabled>Odaberi</option>
<?php
		foreach($this->sponzori as $val)
		{
			echo '<option value="' . $val->idSponzora . '">' . $val->imeTvrtke . '</option>';
		}
?>					
		</select><br><br>
		
<?php } ?>
		
		<label for="podrucje">Područje</label><br>
		<select name="idPodrucja">
			<option <?php if(!$this->sponelekpod) echo 'selected="selected"'; ?> value=""><?php if(!$this->sponelekpod) echo 'Odaberi...'; else echo '(prazno)'; ?></option>
<?php
		foreach($this->podrucja as $val)
		{
			echo '<option value="' . $val->idPodrucja . '"';
			if ($this->sponelekpod && $this->sponelekpod->idPodrucja == $val->idPodrucja)
			{
				echo 'selected="selected"';
			}
			echo '>' . $val->nazivPodrucja . '</option>';
		}
?>					
		</select><br><br>
		
		<label for="iznosDonacije">Iznos donacije</label><br>
		<input type="text" name="iznosDonacije" placeholder="Upišite iznos donacije" <?php if($this->sponelekpod && $this->sponelekpod->iznosDonacije) echo 'value="' . $this->sponelekpod->iznosDonacije . '"' ?> />
		
		<br><br>
		
		<label for="valutaDonacije">Valuta donacije</label><br>
		<select name="valutaDonacije">
			<option <?php if(!$this->sponelekpod || ($this->sponelekpod && $this->sponelekpod->valutaDonacije == 'HRK')) echo 'selected="selected"' ?> value="HRK">HRK</option>
			<option <?php if($this->sponelekpod && $this->sponelekpod->valutaDonacije == 'USD') echo 'selected="selected"' ?> value="USD">USD</option>
			<option <?php if($this->sponelekpod && $this->sponelekpod->valutaDonacije == 'EUR') echo 'selected="selected"' ?> value="EUR">EUR</option>
		</select>
		
		<br><br>
		
		<label for="napomena">Napomena</label><br>
		<textarea name="napomena"><?php if($this->sponelekpod && $this->sponelekpod->napomena) echo $this->sponelekpod->napomena; ?></textarea>
		
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
	
	public function setPodrucja($podrucja) {
	$this->podrucja = $podrucja;
	return $this;
    }
	
	public function setSponzori($sponzori) {
	$this->sponzori = $sponzori;
	return $this;
    }
    
    public function setSponelekpod($sponelekpod) {
	$this->sponelekpod = $sponelekpod;
	return $this;
    }

}