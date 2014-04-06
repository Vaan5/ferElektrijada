<?php

namespace view\administrator;
use app\view\AbstractView;

class OzsnList extends AbstractView {
    /**
     *
     * @var array 
     */
    private $osobe;
    
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
    
    protected function outputHTML() {
		if($this->errorMessage)
		{
			echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
			));
		}
		
		// Else listOzsn in table
		else
		{
			
?>
			<div class="panel panel-default">
				<div class="panel-heading">Članovi odbora</div>
				
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
			// Foreach Ozsn member, generate row in table
			foreach($this->osobe as $val)
			{
				echo '<tr><td>' . $val->ime . '</td><td>' . $val->prezime . '</td><td>' . $val->ferId . '</td>';
				echo '<td><a href="';
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'administrator',
					"action" => 'modifyOzsn'
				));
				echo '?id=' . $val->idOsobe . '">Uredi</a> &nbsp; <a href="';
				
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'administrator',
					"action" => 'removeOzsnFromCurrentElektrijada'
				));
				echo '?id=' . $val->idOsobe . '">Ukloni iz Odbora</a> &nbsp; <a class="obrisiOzsn" href="';
				
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'administrator',
					"action" => 'deleteOzsn'
				));
				echo '?id=' . $val->idOsobe . '">Obriši</a></td></tr>';
			}
?>
				</tbody>
			</table>
		</div>

		<a href="<?php echo \route\Route::get('d3')->generate(array(
			"controller" => 'administrator',
			"action" => 'searchOzsn'
		));?>">Pretraži članove odbora</a>
<?php
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