<?php

namespace view\administrator;
use app\view\AbstractView;

class OldOzsn extends AbstractView {
    /**
     *
     * @var string 
     */
    private $errorMessage;
    
    /**
     *
     * @var string 
     */
    private $resultMessage;
    /**
     *
     * @var array 
     */
    private $clanovi;
    
    protected function outputHTML() {
        // print messages if any
		echo new \view\components\ResultMessage(array(
			"resultMessage" => $this->resultMessage
		));
				
		echo new \view\components\ErrorMessage(array(
           "errorMessage" => $this->errorMessage
		));
				
		// Else showOldOzsn in table
		if(count($this->clanovi))
		{
			
?>
		<?php echo new \view\components\DownloadLinks(array(
			"route" => \route\Route::get("d3")->generate(array(
				"controller" => "administrator",
				"action" => "listOldOzsn"
			))
		)); ?>

			<br><br>
			
			<div class="panel panel-default">
				<div class="panel-heading">Prošlogodišnji članovi odbora</div>
				
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
			// Foreach oldOzsn member, generate row in table
			foreach($this->clanovi as $val)
			{
				echo '<tr><td>' . $val->ime . '</td><td>' . $val->prezime . '</td><td>' . $val->ferId . '</td>';
				echo '<td><a href="';
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'administrator',
					"action" => 'listOldOzsn'
				));
				echo '?id=' . $val->idOsobe . '"><span class="glyphicon glyphicon-plus"></span> Dodaj u Odbor</a></td></tr>';
			}
?>
					</tbody>
				</table>	
			</div>

			<a href="<?php echo \route\Route::get('d3')->generate(array(
				"controller" => 'administrator',
				"action" => 'listOldOzsn'
			));?>?a=1">Obnovi ovlasti svim članovima odbora</a>
					
<?php
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

    public function setClanovi($clanovi) {
        $this->clanovi = $clanovi;
        return $this;
    }

}