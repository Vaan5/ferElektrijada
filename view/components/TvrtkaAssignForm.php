<?php

namespace view\components;
use app\view\AbstractView;

class TvrtkaAssignForm extends AbstractView {
    private $route;
    private $submitButtonText;
	private $tvrtka;
	private $koristiPruza;
	private $usluge;
	private $usluga;

    protected function outputHTML() {
?>		
		<form id="tvrtkaAssignForm" class="form-horizontal" role="form" method="post" action="<?php echo $this->route;?>" enctype="multipart/form-data">
<?php
		if($this->koristiPruza && $this->koristiPruza->idKoristiPruza)
		{
			echo '<input type="hidden" name="id" value="' . $this->koristiPruza->idKoristiPruza . '" />';
		}
		
		else
		{
			echo '<input type="hidden" name="id" value="' . $this->tvrtka->idTvrtke . '" />';
		}
?>              <div class="form-group">
                <label for="imetvrtke" class="col-sm-3 control-label">Ime tvrtke</label>		
                <div class="col-sm-9">
                <span class="form-control"><?php echo $this->tvrtka->imeTvrtke; ?></span>
                </div></div>
                <div class="form-group">
                <label for="adresatvrtke" class="col-sm-3 control-label">Adresa tvrtke</label>		
                <div class="col-sm-9">
                <span class="form-control"><?php echo $this->tvrtka->adresaTvrtke; ?></span>
                </div></div>
		
		<div class="form-group">
			<label for="usluga" class="col-sm-3 control-label">Usluga</label>
			<div class="col-sm-9">
				<select name="idUsluge" class="form-control">
					<option <?php if(!$this->usluga) echo 'selected="selected"'; ?> value=""><?php if(!$this->usluga) echo 'Odaberi...'; else echo '(prazno)'; ?></option>

<?php
		foreach($this->usluge as $val)
		{
			echo '<option value="' . $val->idUsluge . '"';
			if ($this->usluga && $this->usluga->idUsluge == $val->idUsluge)
			{
				echo 'selected="selected"';
			}
			echo '>' . $val->nazivUsluge . '</option>';
		}
?>					
				</select>
			</div>
        </div>
		
		<div class="form-group">
			<label for="iznosRacuna" class="col-sm-3 control-label" style="float:left;">Iznos računa</label>
			<div class="col-sm-9">
				<div class="input-group">
					<input type="text" name="iznosRacuna" class="form-control" placeholder="Upišite iznos računa" <?php if($this->koristiPruza && $this->koristiPruza->iznosRacuna) echo 'value="' . $this->koristiPruza->iznosRacuna . '"' ?> />
					
					<div class="input-group-btn">
						<select name="valutaRacuna" class="form-control btn btn-primary" style="width:80px;">
						<option <?php if(!$this->koristiPruza || ($this->koristiPruza && $this->koristiPruza->valutaDonacije == 'HRK')) echo 'selected="selected"' ?> value="HRK">HRK</option>
						<option <?php if($this->koristiPruza && $this->koristiPruza->valutaDonacije == 'USD') echo 'selected="selected"' ?> value="USD">USD</option>
						<option <?php if($this->koristiPruza && $this->koristiPruza->valutaDonacije == 'EUR') echo 'selected="selected"' ?> value="EUR">EUR</option>
						</select>
					</div>	
				</div>
			</div>
		</div>
		
		<div class="form-group">
			<label for="nacinPlacanja" class="col-sm-3 control-label">Način plačanja</label>
			<div class="col-sm-9">
				<textarea name="nacinPlacanja" class="form-control"><?php if($this->koristiPruza && $this->koristiPruza->nacinPlacanja) echo $this->koristiPruza->nacinPlacanja; ?></textarea>
			</div>
		</div>
		
		<div class="form-group">
			<label for="napomena" class="col-sm-3 control-label">Napomena</label>
			<div class="col-sm-9">
				<textarea name="napomena" class="form-control"><?php if($this->koristiPruza && $this->koristiPruza->napomena) echo $this->koristiPruza->napomena; ?></textarea>
			</div>
		</div>
			
                <center><input type="submit" class="btn btn-primary" value="<?php echo $this->submitButtonText; ?>" /></center>
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
	
	public function setTvrtka($tvrtka) {
        $this->tvrtka = $tvrtka;
        return $this;
    }
	
	public function setKoristiPruza($koristiPruza) {
        $this->koristiPruza = $koristiPruza;
        return $this;
    }
	
	public function setUsluge($usluge) {
        $this->usluge = $usluge;
        return $this;
    }
	
	public function setUsluga($usluga) {
        $this->usluga = $usluga;
        return $this;
    }

}