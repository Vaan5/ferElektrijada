<?php

namespace view\ozsn;
use app\view\AbstractView;

class ContactSearch extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $kontakti;
    private $tvrtke;
    private $sponzori;
    private $mediji;
    
    protected function outputHTML() {
		// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
	
		if($this->kontakti !== NULL)
		{
			if (count($this->kontakti)) {
				
				echo new \view\components\AddNewLink(array(
					"link" => \route\Route::get('d3')->generate(array(
						"controller" => 'ozsn',
						"action" => 'addContact'
					)),
					"buttonText" => 'Dodaj kontakt osobu'
				));
				
				echo new \view\components\DownloadLinks(array(
					"route" => \route\Route::get('d3')->generate(array(
						"controller" => 'ozsn',
						"action" => 'searchContacts'
					))
				));
?>

			<br><br>

			<div class="panel panel-default">
				<div class="panel-heading">Kontakt osobe</div>
				
				<table class="table">
				<thead>
					<tr>
						<th>Ime</th>
						<th>Prezime</th>
						<th>Telefon</th>
						<th>Radno mjesto</th>
						<th>Opcije</th>
					</tr>
				</thead>
				
				<tbody>
<?php
			foreach($this->kontakti as $val)
			{
				echo '<tr><td>' . $val->imeKontakt . '</td><td>' . $val->prezimeKontakt . '</td><td>' . $val->telefon . '</td><td>' . $val->radnoMjesto . '</td>';
				echo '<td><a href="';
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'displayContactInfo'
				));
				echo '?idKontakta=' . $val->idKontakta . '"><span class="glyphicon glyphicon-list-alt"></span> Prikaži detalje</a> &nbsp; ';
				echo '<a href="';
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'modifyContact'
				));
				echo '?id=' . $val->idKontakta . '"><span class="glyphicon glyphicon-pencil"></span> Uredi</a> &nbsp; <a class="deleteContact" href="';
				
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'deleteContact'
				));
				echo '?id=' . $val->idKontakta . '"><span class="glyphicon glyphicon-remove"></span> Obriši</a></td></tr>';
			}
?>
				</tbody>
			</table>
		</div>
<?php
			}
			
			else {
				echo new \view\components\ErrorMessage(array(
					"errorMessage" => "Ne postoji niti jedna osoba koja odgovara parametrima pretrage!"
				));
			}
		}
		else
		{
			echo new \view\components\ContactSearchForm(array(
				"postAction" => \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'searchContacts'
				)),
				"submitButtonText" => "Pretraži",
				"kontakti" => $this->kontakti,
				"tvrtke" => $this->tvrtke,
				"sponzori" => $this->sponzori,
				"mediji" => $this->mediji
			));
		}

    }
    
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    public function setResultMessage($resultMessage) {
        $this->resultMessage = $resultMessage;
        return $this;
    }
	
	public function setKontakti($kontakti) {
        $this->kontakti = $kontakti;
        return $this;
    }

    public function setTvrtke($tvrtke) {
        $this->tvrtke = $tvrtke;
        return $this;
    }
	
	public function setSponzori($sponzori) {
        $this->sponzori = $sponzori;
        return $this;
    }

    public function setMediji($mediji) {
        $this->mediji = $mediji;
        return $this;
    }
}