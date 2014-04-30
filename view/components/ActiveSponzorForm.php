<?php

namespace view\components;
use app\view\AbstractView;

class ActiveSponzorForm extends AbstractView {
    private $route;
    private $submitButtonText;
    private $kategorije;
    private $promocije;
	private $sponzor;
    private $imasponzora;
    private $kategorija;
    private $promocija;

    protected function outputHTML() {
	// kategorije i promocije nek budu drop down list (id u value a name je kao u tablicama id)
	// dodati settere !!!!!!!!!!!!
	// od polja mi trebaju polja imaSponzora (ono donacija i slicno), napomena nek bude textarea
	// za valute stavi drop down s onim valutama koje ante dozvoljava iz triggera
?>
    <form method="post" action="<?php echo $this->route;?>" enctype="multipart/form-data">
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
			<option <?php if(!$this->promocija) echo 'selected="selected"'; ?> value=""><?php if(!$this->promocija) echo 'Odaberi...'; else echo '(prazno)'; ?></option>
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
		<input type="text" name="imeTvrtke" placeholder="Upišite ime tvrtke" <?php if($this->sponzor && $this->sponzor->imeTvrtke) echo 'value="' . $this->sponzor->imeTvrtke . '"' ?> disabled/>
		
		<br><br>
		
		<label for="adresaTvrtke">Adresa tvrtke</label><br>
		<input type="text" name="adresaTvrtke" placeholder="Upišite adresu tvrtke" <?php if($this->sponzor && $this->sponzor->adresaTvrtke) echo 'value="' . $this->sponzor->adresaTvrtke . '"' ?> disabled/>

		<br><br>
		
		<div class="form-group">
			<label for="iznosDonacije" class="col-sm-3 control-label">Iznos donacije</label>
			<div class="col-sm-9">
				<div class="input-group">
					<input type="text" name="iznosDonacije" class="form-control" placeholder="Upišite iznos donacije" <?php if($this->imasponzora && $this->imasponzora->iznosDonacije) echo 'value="' . $this->imasponzora->iznosDonacije . '"' ?> />
					
					<div class="input-group-btn">
						<select name="valutaDonacije" class="form-control btn btn-default" style="width:80px;">
						<option <?php if(!$this->imasponzora || ($this->imasponzora && $this->imasponzora->valutaDonacije == 'HRK')) echo 'selected="selected"' ?> value="HRK">HRK</option>
						<option <?php if($this->imasponzora && $this->imasponzora->valutaDonacije == 'USD') echo 'selected="selected"' ?> value="USD">USD</option>
						<option <?php if($this->imasponzora && $this->imasponzora->valutaDonacije == 'EUR') echo 'selected="selected"' ?> value="EUR">EUR</option>
						</select>
					</div>	
				</div>
			</div>
		</div>
		
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