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
		
		// list sponzori in table
		if(count($this->sponzori))
		{
			
?>
			<?php echo new \view\components\AddNewLink(array(
				"link" => \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'addAreaSponzor'
				)),
				"buttonText" => 'Dodaj novog područnog sponzora'
			)); ?>
			
			<?php echo new \view\components\DownloadLinks(array(
				"route" => \route\Route::get("d3")->generate(array(
					"controller" => "ozsn",
					"action" => "displayAreaSponzor"
				))
			)); ?>

			<br><br>

			<div class="panel panel-default">
				<div class="panel-heading">Područni sponzori</div>
				
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
<?php
		}
		
		else
		{
			echo new \view\components\ErrorMessage(array(
            "errorMessage" => "Nema ni jednog područnog sponzora!"
			));
			
			echo new \view\components\AddNewLink(array(
				"link" => \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'addAreaSponzor'
				)),
				"buttonText" => 'Dodaj novog područnog sponzora'
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
    
    public function setSponzori($sponzori) {
	$this->sponzori = $sponzori;
	return $this;
    }
}