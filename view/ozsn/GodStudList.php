<?php

namespace view\ozsn;
use app\view\AbstractView;

class GodStudList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $godine;
    
    protected function outputHTML() {
        if(count($this->godine))
		{
?>
			<div class="panel panel-default">
				<div class="panel-heading">Popis godina studija</div>
				
				<table class="table">
					<thead>
						<tr>
							<th>Studij</th>
							<th>Godina</th>
							<th>Opcije</th>
						</tr>
					</thead>

					<tbody>
<?php
			// Foreach atribut, generate row in table
			foreach($this->godine as $val)
			{
				echo '<form action="modifyGodStud" method="POST">';
				echo '<tr><td><span id="span-' . $val->idGodStud . '">' . $val->studij . '</span><input type="text" id="input-' . $val->idGodStud . '" style="display:none;" name="studij" value="' . $val->studij . '"><input type="hidden" name="idGodStud" value="' . $val->idGodStud . '"></td>';
				echo '<tr><td><span id="span-' . $val->idGodStud . '">' . $val->godina . '</span><input type="text" id="input-' . $val->idGodStud . '" style="display:none;" name="godina" value="' . $val->godina . '"><input type="hidden" name="idGodStud" value="' . $val->idGodStud . '"></td>';
				echo '<td><input type="submit" style="display: none;" class="btn btn-primary" id="submit-' . $val->idGodStud . '" value="Spremi" /><a href="javascript:;" class="editGodStud" id="uredi-' . $val->idGodStud . '" data-id="' . $val->idGodStud . '">Uredi</a> &nbsp; <a class="deleteGodStud" id="delete-' . $val->idGodStud . '" href="';
				
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'deleteGodStud'
				));
				echo '?id=' . $val->idGodStud . '">Obriši</a>';
				echo '</td></tr></form>';
			}
?>
						<tr class="addGodStud">
							<td colspan="3">
								<a class="addGodStud" id="addGodStud" href="javascript:;"><span class="glyphicon glyphicon-plus"></span> Dodaj novi atribut</a>
							</td>
						</tr>
<?php
		}
		
		else
		{
?>
			<input type="button" id="addGodStud" class="btn btn-primary addGodStud" value="Dodaj novu godinu studija">
			
			<div class="panel panel-default addGodStud_form" style="display:none;">
				<div class="panel-heading">Popis godina studija</div>
				
				<table class="table">
					<thead>
						<tr>
							<th>Studij</th>
							<th>Godina</th>
							<th>Opcije</th>
						</tr>
					</thead>

					<tbody>

<?php
			$this->errorMessage = "Ne postoji niti jedna godina studija";
		}
		?>
						<tr style="display: none;" class="addGodStud_form">
							<form action="addGodStud" method="post">
								<td><input type="text" name="studij" placeholder="Upišite naziv studija"></td>
								<td><input type="text" name="godina" placeholder="Upišite godinu studija"></td>
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
	
	public function setGodine($godine) {
        $this->godine = $godine;
        return $this;
    }

}