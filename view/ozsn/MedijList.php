<?php

namespace view\ozsn;
use app\view\AbstractView;

class MedijList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $mediji;
    
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
			<div class="panel-heading">Popis medija</div>

			<table class="table">
				<thead>
					<tr>
						<th>Naziv</th>
						<th>Opcije</th>
					</tr>
				</thead>

				<tbody>
<?php

		if(count($this->mediji))
		{
			// Foreach medij, generate row in table
			foreach($this->mediji as $val)
			{
				echo '<form action="modifyMedij" method="POST">';
				echo '<tr><td><span class="modify-' . $val->idMedija . '">' . $val->nazivMedija . '</span><input type="text" class="modifyOn-' . $val->idMedija . '" style="display:none;" name="nazivMedija" value="' . $val->nazivMedija . '"><input type="hidden" name="idMedija" value="' . $val->idMedija . '"></td>';
				echo '<td><input type="submit" style="display: none;" class="btn btn-primary modifyOn-' . $val->idMedija . '" value="Spremi" /><a href="javascript:;" class="editMedij modify-' . $val->idMedija . '" data-id="' . $val->idMedija . '">Uredi</a> &nbsp; <a class="deleteMedij modify-' . $val->idMedija . '" href="';
				
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'deleteMedij'
				));
				echo '?id=' . $val->idMedija . '">Obriši</a>';
				echo '</td></tr></form>';
			}
		}
		
		else
		{
?>
						<tr>
							<td class="addMedij" colspan="2"><i>Ne postoji niti jedan medij</i></td>
						</tr>
<?php
		}
?>
					<tr class="addMedij">
						<td colspan="2">
							<a id="addMedij" href="javascript:;"><span class="glyphicon glyphicon-plus"></span> Dodaj novi medij</a>
						</td>
					</tr>
					<tr style="display: none;" class="addMedijOn">
						<form action="addMedij" method="post">
							<td><input type="text" name="nazivMedija" placeholder="Upišite naziv medija"></td>
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
    
    public function setMediji($mediji) {
	$this->mediji = $mediji;
	return $this;
    }

}
