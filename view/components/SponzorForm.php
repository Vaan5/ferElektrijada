<?php

namespace view\components;
use app\view\AbstractView;

class SponzorForm extends AbstractView {
    private $route;
    private $submitButtonText;
    private $kategorije;
    private $promocije;
	private $sponzor;
    private $imasponzora;
    private $kategorija;
    private $promocija;

    protected function outputHTML() {
?>
    <form method="post" action="<?php echo $this->route;?>" enctype="multipart/form-data">
<?php
		if($this->sponzor && $this->sponzor->idSponzora)
		{
			echo '<input type="hidden" name="id" value="' . $this->sponzor->idSponzora . '" />';
		}
?>		
		<label for="kategorija">Kategorija</label><br>
		<select name="idKategorijeSponzora">
			<option <?php if(!$this->kategorija) echo 'selected="selected"'; ?> value=""><?php if(!$this->kategorija) echo 'Odaberi...'; else echo '(prazno)'; ?></option>
<?php
		foreach($this->kategorije as $val)
		{
			echo '<option value="' . $val->idKategorijeSponzora . '"';
			if ($this->kategorija && $this->kategorija->idKategorijeSponzora == $val->idKategorijeSponzora)
			{
				echo 'selected="selected"';
			}
			echo '>' . $val->tipKategorijeSponzora . '</option>';
		}
?>					
		</select><br><br>
		
		<label for="nacinPromocije">Način promocije</label><br>
		<select name="idPromocije">
			<option <?php if(!$this->promocija) echo 'selected="selected"'; ?> selected="selected" value=""><?php if(!$this->promocija) echo 'Odaberi...'; else echo '(prazno)'; ?></option>
<?php
		foreach($this->promocije as $val)
		{
			echo '<option value="' . $val->idPromocije . '"';
			if ($this->promocija && $this->promocija->idPromocije == $val->idPromocije)
			{
				echo 'selected="selected"';
			}
			echo '>' . $val->tipPromocije . '</option>';
		}
?>					
		</select><br><br>
		
		<label for="imeTvrtke">Ime tvrtke</label><br>
		<input type="text" name="imeTvrtke" placeholder="Upišite ime tvrtke" <?php if($this->sponzor && $this->sponzor->imeTvrtke) echo 'value="' . $this->sponzor->imeTvrtke . '"' ?> />
		
		<br><br>
		
		<label for="adresaTvrtke">Adresa tvrtke</label><br>
		<input type="text" name="adresaTvrtke" placeholder="Upišite adresu tvrtke" <?php if($this->sponzor && $this->sponzor->adresaTvrtke) echo 'value="' . $this->sponzor->adresaTvrtke . '"' ?> />
		
		<br><br>
<?php
		if($this->sponzor && $this->sponzor->logotip)
		{
?>
		<a href="<?php echo \route\Route::get('d3')->generate(array(
			"controller" => 'ozsn',
			"action" => 'downloadLogo'
		));?>?id=<?php echo $this->sponzor->idSponzora; ?>">Preuzmi logotip</a>
		
		&nbsp; 
		
		<input type="checkbox" name="delete"> Obriši logotip
<?php				
		}

		else
		{
?>
		<label for="logotip">Logotip</label><br>
		<input type="file" class="btn btn-default" name="datoteka" />
<?php
		}
?>	
		<br><br>
		
		<label for="iznosDonacije">Iznos donacije</label><br>
		<input type="text" name="iznosDonacije" placeholder="Upišite iznos donacije" <?php if($this->imasponzora && $this->imasponzora->iznosDonacije) echo 'value="' . $this->imasponzora->iznosDonacije . '"' ?> />
		
		<br><br>
		
		<label for="valutaDonacije">Valuta donacije</label><br>
		<select name="valutaDonacije">
			<option <?php if(!$this->imasponzora || ($this->imasponzora && $this->imasponzora->valutaDonacije == 'HRK')) echo 'selected="selected"' ?> value="HRK">HRK</option>
			<option <?php if($this->imasponzora && $this->imasponzora->valutaDonacije == 'USD') echo 'selected="selected"' ?> value="USD">USD</option>
			<option <?php if($this->imasponzora && $this->imasponzora->valutaDonacije == 'EUR') echo 'selected="selected"' ?> value="EUR">EUR</option>
		</select>
		
		<br><br>
		
		<label for="napomena">Napomena</label><br>
		<textarea name="napomena"><?php if($this->imasponzora && $this->imasponzora->napomena) echo $this->imasponzora->napomena; ?></textarea>
		
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
	
	public function setSponzor($sponzor) {
	$this->sponzor = $sponzor;
	return $this;
    }

    public function setImasponzora($imasponzora) {
	$this->imasponzora = $imasponzora;
	return $this;
    }

    public function setKategorija($kategorija) {
	$this->kategorija = $kategorija;
	return $this;
    }

    public function setPromocija($promocija) {
	$this->promocija = $promocija;
	return $this;
    }

}