<?php

namespace view\components;
use app\view\AbstractView;

class ObjavaForm extends AbstractView {
	private $route;
    private $submitButtonText;
    private $mediji;
    private $elektrijade;
	private $objaveOElektrijadi;
	private $objava;

    protected function outputHTML() {
?>
    <form class="form-horizontal" role="form" method="post" action="<?php echo $this->route;?>" enctype="multipart/form-data">
		<div class="form-group">	
			<label for="elektrijada" class="col-sm-3 control-label">Elektrijade</label>
			<div class="col-sm-9">
				<select name="idElektrijade[]" multiple="multiple" class="form-control">
<?php
		foreach($this->elektrijade as $val)
		{
			echo '<option value="' . $val->idElektrijade . '"';
			if($this->objaveOElektrijadi)
			{
				foreach($this->objaveOElektrijadi as $val2)
				{
					if ($val2->idElektrijade == $val->idElektrijade)
					{
						echo 'selected="selected"';
					}
				}
			}
			echo '>' . $val->mjestoOdrzavanja . ' ' . date('Y', strtotime($val->datumPocetka)) . '</option>';
		}
?>					
				</select>
				<span style="font-size: 12px">NAPOMENA: Za višestruki odabir držite tipku CTRL i mišem kliknite na željene elektrijade</span>
			</div>
        </div>
		
		<div class="form-group">	
			<label for="medij" class="col-sm-3 control-label">Medij</label>
			<div class="col-sm-9">
				<select name="idMedija" class="form-control">
					<option <?php if(!$this->objava || ($this->objava && !$this->objava->idMedija)) echo 'selected="selected"'; ?> value=""><?php if(!$this->objava || ($this->objava && !$this->objava->idMedija)) echo 'Odaberi...'; else echo '(prazno)'; ?></option>
<?php
		foreach($this->mediji as $val)
		{
			echo '<option value="' . $val->idMedija . '"';
			if ($this->objava && $this->objava->idMedija == $val->idMedija)
			{
				echo 'selected="selected"';
			}
			echo '>' . $val->nazivMedija . '</option>';
		}
?>					
				</select>
			</div>
		</div>
		
		<div class="form-group">
			<label for="datumObjave" class="col-sm-3 control-label">Datum objave</label>
			<div class="col-sm-9">
				<input type="text" name="datumObjave" placeholder="Upišite datum objave" class="datePicker form-control" <?php if($this->objava && $this->objava->datumObjave) echo 'value="' . $this->objava->datumObjave . '"'; ?> />
			</div>
		</div>
		
		<div class="form-group">
			<label for="autorIme" class="col-sm-3 control-label">Ime autora</label>
			<div class="col-sm-9">
				<input type="text" name="autorIme" class="form-control" placeholder="Upišite ime autora" <?php if($this->objava && $this->objava->autorIme) echo 'value="' . $this->objava->autorIme . '"' ?> />
			</div>
		</div>
		
		<div class="form-group">
			<label for="autorPrezime" class="col-sm-3 control-label">Prezime autora</label>
			<div class="col-sm-9">
				<input type="text" name="autorPrezime" class="form-control" placeholder="Upišite prezime autora" <?php if($this->objava && $this->objava->autorPrezime) echo 'value="' . $this->objava->autorPrezime . '"' ?> />
			</div>
		</div>
		
		<div class="form-group">
			<label for="link" class="col-sm-3 control-label">Link</label>
			<div class="col-sm-9">
				<input type="text" name="link" class="form-control" placeholder="Upišite link (sa http://)" <?php if($this->objava && $this->objava->link) echo 'value="' . $this->objava->link . '"' ?> />
			</div>
		</div>
		
<?php
		if($this->objava && $this->objava->dokument)
		{
?>
		
        <div class="form-group">        
        <label for="preuzmi" class="col-sm-3 control-label">Dokument</label>
            <div style="margin-top:6px;" class="col-sm-9">
                <a href="<?php echo \route\Route::get('d3')->generate(array(
			"controller" => 'ozsn',
			"action" => 'download'
		));?>?id=<?php echo $this->objava->idObjave; ?>">Preuzmi dokument</a> &nbsp; 
            <input type="checkbox" name="delete"> Obriši dokument    
            </div>
        </div>
		
		
<?php				
		}

		else
		{
?>		<div class="form-group">
			<label for="dokument" class="col-sm-3 control-label">Dokument</label>
			<div class="col-sm-9">
				<input style="margin-top:7px;" type="file" name="datoteka" />
			</div>
		</div>
		<?php } ?>
		
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
    
    public function setMediji($mediji) {
	$this->mediji = $mediji;
	return $this;
    }

    public function setElektrijade($elektrijade) {
	$this->elektrijade = $elektrijade;
	return $this;
    }
	
	public function setObjaveOElektrijadi($objaveOElektrijadi) {
	$this->objaveOElektrijadi = $objaveOElektrijadi;
	return $this;
    }

    public function setObjava($objava) {
	$this->objava = $objava;
	return $this;
    }
}