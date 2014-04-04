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
<?php
			// Foreach atribut, generate row in table
			foreach($this->velicine as $val)
			{
				echo '<form action="modifyVelMajice" method="POST">';
				echo '<tr><td><span id="span-' . $val->id . '">' . $val->velicina . '</span><input type="text" id="input-' . $val->idVelicine . '" style="display:none;" name="velicina" value="' . $val->velicina . '"><input type="hidden" name="idVelicine" value="' . $val->idVelicine . '"></td>';
				echo '<td><input type="submit" style="display: none;" class="btn btn-primary" id="submit-' . $val->idVelicine . '" value="Spremi" /><a href="javascript:;" class="editVelMajice" id="edit-' . $val->idVelicine . '" data-id="' . $val->idVelicine . '">Uredi</a> &nbsp; <a class="deleteVelMajice" id="delete-' . $val->idVelicine . '" href="';
				
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'deleteVelMajice'
				));
				echo '?id=' . $val->idVelicine . '">Obriši</a>';
				echo '</td></tr></form>';
			}
?>
						<tr class="addVelMajice">
							<td colspan="2">
								<a class="addVelMajice" id="addVelMajice" href="javascript:;"><span class="glyphicon glyphicon-plus"></span> Dodaj novu veličinu majice</a>
							</td>
						</tr>
<?php
		}
		
		else
		{
?>
			<input type="button" id="addVelMajice" class="btn btn-primary addAtribut" value="Dodaj novu veličinu majice">
			
			<div class="panel panel-default addVelMajice_form" style="display:none;">
				<div class="panel-heading">Popis veličina majice</div>
				
				<table class="table">
					<thead>
						<tr>
							<th>Veličina</th>
							<th>Opcije</th>
						</tr>
					</thead>

					<tbody>

<?php
			$this->errorMessage = "Ne postoji niti jedan veličina majice";
		}
		?>
						<tr style="display: none;" class="addVelMajice_form">
							<form action="addAtribut" method="post">
								<td><input type="text" name="velicina" placeholder="Upišite veličinu majice"></td>
								<td><input type="submit" class="btn btn-primary" value="Dodaj" /></td>
							</form>
						</tr>
						
					</tbody>
				</table>
			</div>
<?php		
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
	
	public function setVelicine($velicine) {
        $this->velicine = $velicine;
        return $this;
    }

}