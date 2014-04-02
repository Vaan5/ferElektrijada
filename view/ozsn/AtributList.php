<?php

namespace view\ozsn;
use app\view\AbstractView;

class AtributList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $atributi;
    
    protected function outputHTML() {
        if(count($this->atributi))
		{
?>
			<div class="panel panel-default">
				<div class="panel-heading">Popis atributa</div>
				
				<table class="table">
				<thead>
					<tr>
						<th>Naziv</th>
						<th>Opcije</th>
					</tr>
				</thead>
				
				<tbody>
<?php
			// Foreach atribut, generate row in table
			foreach($this->atributi as $val)
			{
				echo '<tr><td>' . $val->nazivAtributa . '</td><td>';
				echo '<td><a href="';
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'modifyAtribut'
				));
				echo '?id=' . $val->idAtributa . '">Uredi</a> &nbsp; <a href="';
				
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'deleteAtribut'
				));
				echo '?id=' . $val->idAtributa . '">Obriši</a>';
			}
			
			echo '</tbody></table></div>';

		}
		
		else
		{
			$this->errorMessage = "Ne postoji niti jedan atribut";
		}
		
		// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
    }
    
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    public function setResultMessage($resultMessage) {
        $this->resultMessage = $resultMessage;
        return $this;
    }
	
	public function setAtributi($atributi) {
        $this->atributi = $atributi;
        return $this;
    }

}
