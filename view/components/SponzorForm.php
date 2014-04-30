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
    <form class="form-horizontal" role="form" method="post" action="<?php echo $this->route;?>" enctype="multipart/form-data">
<?php
		if($this->sponzor && $this->sponzor->idSponzora)
		{
			echo '<input type="hidden" name="id" value="' . $this->sponzor->idSponzora . '" />';
		}
?>		
	<div class="form-group">	
                <label for="kategorija" class="col-sm-3 control-label">Kategorija</label>
		<div class="col-sm-9">
                <select name="idKategorijeSponzora" class="form-control">
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
</select></div>
        </div>
	<div class="form-group">
		<label for="nacinPromocije" class="col-sm-3 control-label">Način promocije</label>
		<div class="col-sm-9">
                <select name="idPromocije" class="form-control">
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
		</select></div>
        </div>
        <div class="form-group">
		<label for="imeTvrtke" class="col-sm-3 control-label">Ime tvrtke</label>
		<div class="col-sm-9">
                <input type="text" name="imeTvrtke" class="form-control" placeholder="Upišite ime tvrtke" <?php if($this->sponzor && $this->sponzor->imeTvrtke) echo 'value="' . $this->sponzor->imeTvrtke . '"' ?> />
                </div>
        </div>
	<div class="form-group">	
		<label for="adresaTvrtke" class="col-sm-3 control-label">Adresa tvrtke</label>
		<div class="col-sm-9">
                <input type="text" name="adresaTvrtke" class="form-control" placeholder="Upišite adresu tvrtke" <?php if($this->sponzor && $this->sponzor->adresaTvrtke) echo 'value="' . $this->sponzor->adresaTvrtke . '"' ?> />
                </div>
        </div>
<?php
		if($this->sponzor && $this->sponzor->logotip)
		{
?>
		
        <div class="form-group">        
        <label for="preuzmi" class="col-sm-3 control-label"></label>
            <div class="col-sm-9">
            <a href="<?php echo \route\Route::get('d3')->generate(array(
			"controller" => 'ozsn',
			"action" => 'downloadLogo'
		));?>?id=<?php echo $this->sponzor->idSponzora; ?>">Preuzmi logotip &nbsp;</a>
            <input type="checkbox" name="delete"> Obriši logotip    
            </div>
        </div>
		
		
<?php				
		}

		else
		{
?>              <div class="form-group">
		<label for="logotip" class="col-sm-3 control-label">Logotip</label>
		<div class="col-sm-9">
                <input type="file" name="datoteka" />
                </div>
                </div>
<?php
		}
?>	
		<!--<div class="form-group">
		<label for="iznosDonacije" class="col-sm-3 control-label">Iznos donacije</label>
		<div class="col-sm-9">
                <input type="text" name="iznosDonacije" class="form-control" placeholder="Upišite iznos donacije" <?php if($this->imasponzora && $this->imasponzora->iznosDonacije) echo 'value="' . $this->imasponzora->iznosDonacije . '"' ?> />
                </div>
                </div>
		
                <div class="form-group">
		<label for="valutaDonacije" class="col-sm-3 control-label">Valuta donacije</label>
		<div class="col-sm-9">
                <select name="valutaDonacije" class="form-control">
			<option <?php if(!$this->imasponzora || ($this->imasponzora && $this->imasponzora->valutaDonacije == 'HRK')) echo 'selected="selected"' ?> value="HRK">HRK</option>
			<option <?php if($this->imasponzora && $this->imasponzora->valutaDonacije == 'USD') echo 'selected="selected"' ?> value="USD">USD</option>
			<option <?php if($this->imasponzora && $this->imasponzora->valutaDonacije == 'EUR') echo 'selected="selected"' ?> value="EUR">EUR</option>
		</select>
                </div>
                </div>-->
		
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
		
                <div class="form-group">
		<label for="napomena" class="col-sm-3 control-label">Napomena</label>
		<div class="col-sm-9">
                <textarea name="napomena" class="form-control"><?php if($this->imasponzora && $this->imasponzora->napomena) echo $this->imasponzora->napomena; ?></textarea>
		</div>
                </div>
			
                <center><input type="submit" class="btn btn-primary" value="<?php echo $this->submitButtonText; ?>" /></center>
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