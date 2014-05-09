<?php

namespace view\reports\forms;
use app\view\AbstractView;

class BusCompetitorsForm extends AbstractView {
    private $route;
    private $submitButtonText;
    private $elektrijade;
    
    protected function outputHTML() {
?>
    <form class="form-inline" role="form" method="post" action="<?php echo $this->route;?>">
        <center><div class="form-group">
      
	<div class="form-group">		
        <label class="sr-only" for="elektrijada">Elektrijada</label>
            <select name="idElektrijade" class="form-control">
			<option value="">Odaberi elektrijadu</option>
<?php
		foreach($this->elektrijade as $val)
		{
			echo '<option value="' . $val->idElektrijade . '"';
			echo '>' . $val->mjestoOdrzavanja . " " . $val->datumPocetka . '</option>';
		}
?>					
        </select>
        </div><br/><br/>
	<div class="checkbox">
            <label>
                <input type="checkbox" name="ime"> Ime &nbsp;
            </label>
        </div>
	<div class="checkbox">
            <label>
                <input type="checkbox" name="prezime"> Prezime &nbsp;
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="mail"> E-mail &nbsp;
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="brojMob"> Broj mobitela &nbsp;
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="ferId"> Korisničko ime &nbsp;
            </label>
        </div>
       <div class="checkbox">
            <label>
                <input type="checkbox" name="JMBAG"> JMBAG &nbsp;
            </label>
        </div><br><br>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="brOsobne"> Broj osobne &nbsp;
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="brPutovnice"> Broj putovnice &nbsp;
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="osobnaVrijediDo"> Datum isteka osobne iskaznice &nbsp;
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="putovnicaVrijediDo"> Datum isteka putovnice &nbsp;
            </label>
        </div><br><br>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="uloga"> Uloga &nbsp;
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="MBG"> Matični broj osigurane osobe &nbsp;
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="OIB"> OIB &nbsp;
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="aktivanDokument"> Aktivan dokument &nbsp;
            </label>
        </div>
     	<div class="checkbox">
            <label>
                <input type="checkbox" name="tip"> Student / Djelatnik &nbsp;
            </label>
        </div><br><br><br>

	<?php echo new DownloadOptionsForm();?>	
	
        <input type="submit" class="btn btn-primary" value="<?php echo $this->submitButtonText; ?>" /></center>
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
    

    public function setElektrijade($elektrijade) {
	$this->elektrijade = $elektrijade;
	return $this;
    }
}