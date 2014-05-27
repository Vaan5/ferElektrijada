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

	<form id="areaSponzorForm" method="post" class="form-horizontal" role="form" action="<?php echo $this->route;?>">
<?php
		if(!$this->sponelekpod)
		{
?>              <div class="form-group">
		<label for="sponzor" class="col-sm-3 control-label">Sponzor</label>
		<div class="col-sm-7">
                <select name="idSponzora" class="form-control">
			<option selected="selected" value="" disabled>Odaberi</option>
<?php
		foreach($this->sponzori as $val)
		{
			echo '<option value="' . $val->idSponzora . '">' . $val->imeTvrtke . '</option>';
		}
?>					
                </select></div></div>
		
<?php } ?>
		<div class="form-group">
		<label for="podrucje" class="col-sm-3 control-label">Područje</label>
		<div class="col-sm-7">
                <select name="idPodrucja" class="form-control">
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
                </select></div></div>
		
		<div class="form-group">
			<label for="iznosDonacije" class="col-sm-3 control-label">Iznos donacije</label>
			<div class="col-sm-7">
				<div class="input-group">
					<input type="text" name="iznosDonacije" class="form-control" placeholder="Upišite iznos donacije" <?php if($this->sponelekpod && $this->sponelekpod->iznosDonacije) echo 'value="' . $this->sponelekpod->iznosDonacije . '"' ?> />
					
					<div class="input-group-btn">
						<select name="valutaDonacije" class="form-control btn btn-primary" style="width:80px;">
						<option <?php if(!$this->sponelekpod || ($this->sponelekpod && $this->sponelekpod->valutaDonacije == 'HRK')) echo 'selected="selected"' ?> value="HRK">HRK</option>
						<option <?php if($this->sponelekpod && $this->sponelekpod->valutaDonacije == 'USD') echo 'selected="selected"' ?> value="USD">USD</option>
						<option <?php if($this->sponelekpod && $this->sponelekpod->valutaDonacije == 'EUR') echo 'selected="selected"' ?> value="EUR">EUR</option>
						</select>
					</div>
					
				</div>
			</div>
		</div>
		
		<div class="form-group">
		<label for="napomena" class="col-sm-3 control-label">Napomena</label>
		<div class="col-sm-7">
                <textarea name="napomena" class="form-control"><?php if($this->sponelekpod && $this->sponelekpod->napomena) echo $this->sponelekpod->napomena; ?></textarea>
                </div></div>
		<br>
		
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