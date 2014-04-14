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
    <form method="post" action="<?php echo $this->route;?>">

	<label for="disciplina">Odaberite disciplinu</label><br>
		<select name="idPodrucja">
			<option value="">Odaberi...</option>
<?php
		foreach($this->podrucja as $val)
		{
			echo '<option value="' . $val->idPodrucja . '"';
			echo '>' . $val->nazivPodrucja . '</option>';
		}
?>					
		</select><br><br>
		
	<label for="elektrijada">Odaberite elektrijadu</label><br>
		<select name="idElektrijade">
			<option value="">Odaberi...</option>
<?php
		foreach($this->elektrijade as $val)
		{
			echo '<option value="' . $val->idElektrijade . '"';
			echo '>' . $val->mjestoOdrzavanja . " " . $val->datumPocetka . '</option>';
		}
?>					
		</select><br><br>
	
		
	<input type="checkbox" name="ime">Ime<br/>
	<input type="checkbox" name="prezime">Prezime<br/>
	<input type="checkbox" name="mail">E-mail<br/>
	<input type="checkbox" name="brojMob">Broj mobitela<br/>
	<input type="checkbox" name="ferId">Korisničko ime<br/>
	<input type="checkbox" name="JMBAG">JMBAG<br/>
	<input type="checkbox" name="brOsobne">Broj osobne<br/>
	<input type="checkbox" name="brPutovnice">Broj putovnice<br/>
	<input type="checkbox" name="osobnaVrijediDo">Datum isteka osobne iskaznice<br/>
	<input type="checkbox" name="putovnicaVrijediDo">Datum isteka putovnice<br/>
	<input type="checkbox" name="uloga">Uloga<br/>
	<input type="checkbox" name="MBG">Matični broj osigurane osobe<br/>
	<input type="checkbox" name="OIB">OIB<br/>
	
	<input type="checkbox" name="nazivAtributa">Atribut<br/>
	<input type="checkbox" name="velicina">Veličina majice<br/>
	<input type="checkbox" name="studij">Studij<br/>
	<input type="checkbox" name="godina">Godina<br/>
	<input type="checkbox" name="nazivSmjera">Smjer<br/>
	<input type="checkbox" name="nazivZavoda">Naziv zavoda<br/>
	<input type="checkbox" name="skraceniNaziv">Skraćeni naziv zavoda<br/>
	<input type="checkbox" name="naziv">Radno mjesto<br/>
	
	<input type="checkbox" name="brojBusa">Redni broj autobusa<br/>
	<input type="checkbox" name="brojSjedala">Broj sjedala<br/>
	<input type="checkbox" name="napomena">Napomena uz putovanje<br/>
	
	<input type="checkbox" name="tip">Student / Djelatnik<br/>
	<input type="checkbox" name="rezultatPojedinacni">Postignuti rezultat<br/>
	<input type="checkbox" name="ukupanBrojSudionika">Ukupan broj sudionika<br/>

	<?php echo new DownloadOptionsForm();?>	
	
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

    public function setElektrijade($elektrijade) {
	$this->elektrijade = $elektrijade;
	return $this;
    }
}