<?php

namespace view\ozsn;
use app\view\AbstractView;

class VelMajiceList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $velicine;
    
    protected function outputHTML() {
        if(count($this->velicine))
		{
?>
			<div class="panel panel-default">
				<div class="panel-heading">Popis veličina majica</div>
				
				<table class="table">
				<thead>
					<tr>
						<th>Veličina</th>
						<th>Opcije</th>
					</tr>
				</thead>
				
				<tbody>
				<form action="modifyVelMajice" method="POST"> 
<?php
			// Foreach atribut, generate row in table
			foreach($this->velicine as $val)
			{
				echo '<tr><td><span id="span-' . $val->idVelicine . '">' . $val->velicina . '</span><input type="text" id="input-' . $val->idVelicine . '" style="display:none;" name="velicina" value="' . $val->velicina . '"><input type="hidden" name="idVelicine" value="' . $val->idVelicine . '"></td>';
				echo '<td><input type="submit" style="display: none;" class="btn btn-primary" id="submit-' . $val->idVelicine . '" value="Spremi" /><a href="javascript:;" class="urediVelicinu" id="uredi-' . $val->idVelicine . '" data-id="' . $val->idVelicine . '">Uredi</a> &nbsp; <a class="obrisiVelicinu" id="obrisi-' . $val->idvelicine . '" href="';
				
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'deleteVelMajice'
				));
				echo '?id=' . $val->idVelicine . '">Obriši</a>';
			}
			
			echo '</td></tr></form></tbody></table></div>';

		}
		
		else
		{
			$this->errorMessage = "Ne postoji niti jedan zapis o veličini majice";
		}
		
		// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
?>		
		<input type="button" id="addVelicina" class="btn btn-primary" value="Dodaj novu veličinu" />			
		<form action="addAtribut" method="post">
			<input type="text" id="addVelicina_input" name="velicina" style="display:none;" placeholder="Upišite veličinu majice">
			<input type="submit" id="addVelicina_submit" style="display: none;" class="btn btn-primary" value="Dodaj" />
		</form>
<?php
    }
    
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    public function setResultMessage($resultMessage) {
        $this->resultMessage = $resultMessage;
        return $this;
    }
	
	public function setVelMajice($velMajice) {
        $this->velMajice = $velMajice;
        return $this;
    }

}
