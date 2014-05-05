<?php

namespace view\ozsn;
use app\view\AbstractView;

class ContactList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $kontakti;
    
    protected function outputHTML() {

		echo new \view\components\ResultMessage(array(
			"resultMessage" => $this->resultMessage
		));

		echo new \view\components\ErrorMessage(array(
		"errorMessage" => $this->errorMessage
		));
		
		// listContacts in table
		if(count($this->kontakti))
		{
?>
			<?php echo new \view\components\AddNewLink(array(
				"link" => \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'addContact'
				)),
				"buttonText" => 'Dodaj kontakt osobu'
			)); ?>
			<?php echo new \view\components\DownloadLinks(array(
				"route" => \route\Route::get("d3")->generate(array(
					"controller" => "ozsn",
					"action" => "displayContacts"
				))
			)); ?>

			<br><br>

			<div class="panel panel-default">
				<div class="panel-heading">Kontakti</div>
				
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
			// Foreach Ozsn member, generate row in table
			foreach($this->kontakti as $val)
			{
				echo '<tr><td>' . $val->imeKontakt . '</td><td>' . $val->prezimeKontakt . '</td><td>' . $val->telefon . '</td><td>' . $val->radnoMjesto . '</td>';
				echo '<td><a href="';
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'modifyContact'
				));
				echo '?id=' . $val->idKontakta . '">Uredi</a> &nbsp; <a class="deleteContact" href="';
				
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'deleteContact'
				));
				echo '?id=' . $val->idKontakta . '">Obriši</a></td></tr>';
			}
?>
				</tbody>
			</table>
		</div>
<?php
		}
		
		else
		{
			echo new \view\components\ErrorMessage(array(
				"errorMessage" => "Ne postoji niti jedna kontakt osoba!"
			));
		}
    }
	
	public function setKontakti($kontakti) {
        $this->kontakti = $kontakti;
        return $this;
    }
    
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    public function setResultMessage($resultMessage) {
        $this->resultMessage = $resultMessage;
        return $this;
    }
}
