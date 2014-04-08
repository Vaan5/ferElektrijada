<?php

namespace view\ozsn;
use app\view\AbstractView;

class GodStudList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $godine;
    
    protected function outputHTML() {
		// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
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

		if(count($this->godine))
		{
			// Foreach GodStud, generate row in table
			foreach($this->godine as $val)
			{
				echo '<form action="modifyGodStud" method="POST">';
				echo '<tr><td><span class="modify-' . $val->idGodStud . '">' . $val->studij . '</span><input type="text" class="modifyOn-' . $val->idGodStud . '" style="display:none;" name="studij" value="' . $val->studij . '"><input type="hidden" name="idGodStud" value="' . $val->idGodStud . '"></td>';
				echo '<td><span class="modify-' . $val->idGodStud . '">' . $val->godina . '</span><input type="text" class="modifyOn-' . $val->idGodStud . '" style="display:none;" name="godina" value="' . $val->godina . '">';
				echo '<td><input type="submit" style="display: none;" class="btn btn-primary modifyOn-' . $val->idGodStud . '" value="Spremi" /><a href="javascript:;" class="editGodStud modify-' . $val->idGodStud . '" data-id="' . $val->idGodStud . '">Uredi</a> &nbsp; <a class="deleteGodStud modify-' . $val->idGodStud . '" href="';
				
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'deleteGodStud'
				));
				echo '?id=' . $val->idGodStud . '">Obriši</a>';
				echo '</td></tr></form>';
			}
		}
		
		else
		{
?>
						<tr>
							<td class="addGodStud" colspan="3"><i>Ne postoji ni jedan zapis o godini studija</i></td>
						</tr>
<?php
		}
?>
					<tr class="addGodStud">
						<td colspan="3">
							<a id="addGodStud" href="javascript:;"><span class="glyphicon glyphicon-plus"></span> Dodaj novu godinu studija</a>
						</td>
					</tr>
					<tr style="display: none;" class="addGodStudOn">
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