<?php

namespace view\administrator;
use app\view\AbstractView;

class ElektrijadaList extends AbstractView {
    /**
     *
     * @var array of objects 
     */
    private $elektrijade;
    
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
		// print messages if any
		echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
        
		if(count($this->elektrijade))
		{
			
?>		
		<?php echo new \view\components\DownloadLinks(array(
			"route" => \route\Route::get("d3")->generate(array(
				"controller" => "administrator",
				"action" => "displayElektrijada"
			))
		)); ?>

		<br><br>

			<div class="panel panel-default">
				<div class="panel-heading">Popis elektrijada</div>
				
				<table class="table">
				<thead>
					<tr>
						<th>Godina</th>
						<th>Mjesto</th>
						<th>Država</th>
						<th>Opcije</th>
					</tr>
				</thead>
				
				<tbody>
<?php
			// Foreach elektrijada, generate row in table
			foreach($this->elektrijade as $val)
			{
				echo '<tr><td>' . date('Y', strtotime($val->datumPocetka)) . '</td><td>' . $val->mjestoOdrzavanja . '</td><td>' . $val->drzava . '</td>';
				echo '<td><a href="';
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'administrator',
					"action" => 'modifyElektrijada'
				));
				echo '?id=' . $val->idElektrijade . '">Uredi</a> &nbsp; <a class="obrisiElektrijadu" href="';
				
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'administrator',
					"action" => 'doubleCheckAdmin'
				));
				echo '?id=' . $val->idElektrijade . '">Obriši</a>';
			}
			
			echo '</tbody></table></div>';
		}
    }
    
    public function setElektrijade($elektrijade) {
        $this->elektrijade = $elektrijade;
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