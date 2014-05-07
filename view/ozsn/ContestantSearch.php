<?php

namespace view\ozsn;
use app\view\AbstractView;

class ContestantSearch extends AbstractView {

    private $osobe;
    private $errorMessage;
    private $resultMessage;
    
    protected function outputHTML() {
		// print messages if any
		echo new \view\components\ResultMessage(array(
			"resultMessage" => $this->resultMessage
		));
		echo new \view\components\ErrorMessage(array(
			"errorMessage" => $this->errorMessage
		));
		
		if ($this->osobe !== null) {
			if (count($this->osobe)) {
?>
			<div class="panel panel-default">
				<div class="panel-heading">Sudionici</div>
				
				<table class="table">
				<thead>
					<tr>
						<th>Ime</th>
						<th>Prezime</th>
						<th>FerID</th>
						<th>Opcije</th>
					</tr>
				</thead>
				
				<tbody>
<?php
			foreach($this->osobe as $val)
			{
				echo '<tr><td>' . $val->ime . '</td><td>' . $val->prezime . '</td><td>' . $val->ferId . '</td>';
				echo '<td><a href="';
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'modifyContestant'
				));
				echo '?id=' . $val->idOsobe . '">Uredi</a> &nbsp; <a href="';
				
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'deleteContestant'
				));
				echo '?id=' . $val->idOsobe . '">Obriši</a></td></tr>';
			}
?>
				</tbody>
			</table>
		</div>
<?php
			echo new \view\components\DownloadLinks(array(
				"route" => \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'searchContestants'
				))
			));
			} else {
				echo new \view\components\ErrorMessage(array(
					"errorMessage" => "Ne postoji niti jedan sudionik koji odgovara parametrima pretrage!"
				));
			}
		} else {
			// display form
			echo new \view\components\MediumPersonSearchForm(array(
				"postAction" => \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'searchContestants'
				)),
				"submitButtonText" => "Pretraži",
				"showAllButtonText" => "Prikaži sve sudionike",
				"allController" => 'ozsn',
				"allAction" => "searchContestants"
			));
		}
    }
    
    public function setOsobe($osobe) {
        $this->osobe = $osobe;
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