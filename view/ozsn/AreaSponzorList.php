<?php

namespace view\ozsn;
use app\view\AbstractView;

class AreaSponzorList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $sponzori;
    
    protected function outputHTML() {		
		// Show messages if any
		if($this->resultMessage)
		{
			echo new \view\components\ResultMessage(array(
				"resultMessage" => $this->resultMessage
			));
		}
		
		if($this->errorMessage)
		{
			echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
			));
		}
		
		// Else list sponzori in table
		else
		{
			
?>
			<div class="panel panel-default">
				<div class="panel-heading">Sponzori područja</div>
				
				<table class="table">
				<thead>
					<tr>
						<th>Ime tvrtke</th>
						<th>Adresa</th>
						<th>Opcije</th>
					</tr>
				</thead>
				
				<tbody>
<?php
			// Foreach areaSponzor, generate row in table
			foreach($this->sponzori as $val)
			{
				echo '<tr><td>' . $val->imeTvrtke . '</td><td>' . $val->adresaTvrtke . '</td>';
				echo '<td><a href="';
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'modifyAreaSponzor'
				));
				echo '?id=' . $val->idSponElekPod . '">Uredi</a> &nbsp; <a class="deleteAreaSponzor" href="';
				
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'deleteAreaSponzor'
				));
				echo '?id=' . $val->idSponElekPod . '">Obriši</a></td></tr>';
			}
?>
				</tbody>
			</table>
		</div>

		<a href="<?php echo \route\Route::get('d3')->generate(array(
			"controller" => 'ozsn',
			"action" => 'addAreaSponzor'
		));?>"><span class="glyphicon glyphicon-plus"></span> Dodaj novog sponzora područja</a>
<?php
		}		
		
	
	// samo ispisati osnovne podatke // ime trvtke i adresu iznos i podrucje
	// opcije(nazovi ih kako ti pase) su Dodaj novog, Uredi, i Brisi - odnosi se na add/modify/deleteAreaSponzor
		
	// PAZI var_dumpaj sponzore (NIJE OBJEKTNI OBLIK JER IMAS U NJEMU I sponElekPodrucje, pa mi za modify u get parametar id stavi identifikator od sponelekpod tablice)
    }
    
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    public function setResultMessage($resultMessage) {
        $this->resultMessage = $resultMessage;
        return $this;
    }
    
    public function setSponzori($sponzori) {
	$this->sponzori = $sponzori;
	return $this;
    }
}