<?php

namespace view\ozsn;
use app\view\AbstractView;

class ActiveSponzorList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $sponzori;
    
    protected function outputHTML() {
		// Show messages if any
		echo new \view\components\ResultMessage(array(
			"resultMessage" => $this->resultMessage
		));

		echo new \view\components\ErrorMessage(array(
			"errorMessage" => $this->errorMessage
		));
		
		// list sponzori in table
		if(count($this->sponzori))
		{
			
?>
		<?php echo new \view\components\AddNewLink(array(
				"link" => \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'addSponzor'
				)) . "?m=1",
				"buttonText" => 'Dodaj novog sponzora'
			)); ?>
			<?php echo new \view\components\DownloadLinks(array(
				"route" => \route\Route::get("d3")->generate(array(
					"controller" => "ozsn",
					"action" => "displayActiveSponzor"
				))
			)); ?>

			<br><br>

			<div class="panel panel-default">
				<div class="panel-heading">Ovogodišnji sponzori</div>
				
				<table class="table">
				<thead>
					<tr>
						<th>Tvrtka</th>
						<th>Adresa tvrtke</th>
						<th>Kategorija</th>
						<th>Način</th>
						<th>Iznos</th>
						<th>Opcije</th>
					</tr>
				</thead>
				
				<tbody>
<?php
			// Foreach activeSponzor, generate row in table
			foreach($this->sponzori as $val)
			{
?>
						<tr>
							<td><?php echo $val->imeTvrtke; ?></td>
							<td><?php echo $val->adresaTvrtke; ?></td>
							<td><?php echo $val->tipKategorijeSponzora; ?></td>
							<td><?php echo $val->tipPromocije; ?></td>
							<td><?php echo $val->iznosDonacije . ' ' . $val->valutaDonacije; ?></td>
<?php
				echo '<td><a href="';
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'modifyActiveSponzor'
				));
				echo '?id=' . $val->idSponzora . '"><span class="glyphicon glyphicon-pencil"></span> Uredi</a> &nbsp; <a class="deleteActiveSponzor" href="';
				
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'deleteActiveSponzor'
				));
				echo '?id=' . $val->idSponzora . '"><span class="glyphicon glyphicon-remove"></span> Obriši</a></td></tr>';
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
            "errorMessage" => "Ne postoji niti jedan ovogodišnji sponzor!"
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