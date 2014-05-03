<?php

namespace view\reports\forms;
use app\view\AbstractView;

class DisciplineCompetitorForm extends AbstractView {
    private $route;
    private $submitButtonText;
    private $podrucja;
    private $elektrijade;
    
    protected function outputHTML() {
?>
    <form class="form-inline" role="form" method="post" action="<?php echo $this->route;?>">
        <center><div class="form-group">
        <label class="sr-only" for="podrucja">Podrucje</label>
            <select name="idPodrucja" class="form-control">
			<option value="">Odaberi područje</option>
<?php
		foreach($this->podrucja as $val)
		{
			echo '<option value="' . $val->idPodrucja . '"';
			echo '>' . $val->nazivPodrucja . '</option>';
		}
?>					
        </select>	
        </div>
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
        </div></center><br/><br/>
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
        </div><br><br>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="nazivAtributa"> Atribut &nbsp;
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="velicina"> Veličina majice &nbsp;
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="studij"> Studij &nbsp;
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="godina"> Godina &nbsp;
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="nazivSmjera"> Smjer &nbsp;
            </label>
        </div><br><br>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="nazivZavoda"> Naziv zavoda &nbsp;
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="skraceniNaziv"> Skraćeni naziv zavoda &nbsp;
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="naziv"> Radno mjesto &nbsp;
            </label>
        </div><br><br>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="brojBusa"> Redni broj autobusa &nbsp;
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="brojSjedala"> Broj sjedala &nbsp;
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="napomena"> Napomena uz putovanje &nbsp;
            </label>
        </div><br><br>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="tip"> Student / Djelatnik &nbsp;
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="rezultatPojedinacni"> Postignuti rezultat &nbsp;
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="ukupanBrojSudionika"> Ukupan broj sudionika &nbsp;
            </label>
        </div><br><br>

	<?php echo new DownloadOptionsForm();?>	
	
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

    public function setElektrijade($elektrijade) {
	$this->elektrijade = $elektrijade;
	return $this;
    }
}