<?php

namespace view\ozsn;
use app\view\AbstractView;

class VelMajiceList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $velicine;
    
    protected function outputHTML() {
		// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
?>
		<?php echo new \view\components\DownloadLinks(array(
			"route" => \route\Route::get("d3")->generate(array(
				"controller" => "ozsn",
				"action" => "displayVelMajice"
			))
		)); ?>

		<br><br>
		
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

		if(count($this->velicine))
		{
			// Foreach velicina, generate row in table
			foreach($this->velicine as $val)
			{
				echo '<form action="';
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'modifyVelMajice'
				));
				echo '" method="POST">';
				echo '<tr><td><span class="modify-' . $val->idVelicine . '">' . $val->velicina . '</span><input type="text" class="modifyOn-' . $val->idVelicine . '" style="display:none;" name="velicina" value="' . $val->velicina . '"><input type="hidden" name="idVelicine" value="' . $val->idVelicine . '"></td>';
				echo '<td><input type="submit" style="display: none;" class="btn btn-primary modifyOn-' . $val->idVelicine . '" value="Spremi" /><a href="javascript:;" class="editVelMajice modify-' . $val->idVelicine . '" data-id="' . $val->idVelicine . '">Uredi</a> &nbsp; <a class="deleteVelMajice modify-' . $val->idVelicine . '" href="';
				
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'deleteVelMajice'
				));
				echo '?id=' . $val->idVelicine . '">Obriši</a>';
				echo '</td></tr></form>';
			}
		}
		
		else
		{
?>
						<tr>
							<td class="addVelMajice" colspan="2"><i>Ne postoji ni jedna veličina majice</i></td>
						</tr>
<?php
		}
?>
					<tr class="addVelMajice">
						<td colspan="2">
							<a id="addVelMajice" href="javascript:;"><span class="glyphicon glyphicon-plus"></span> Dodaj novu veličinu majice</a>
						</td>
					</tr>
					<tr style="display: none;" class="addVelMajiceOn">
						<form action="
							  <?php echo \route\Route::get('d3')->generate(array(
								"controller" => 'ozsn',
								"action" => 'addVelMajice'
							)); ?>							  
							  " method="post">
							<td><input type="text" name="velicina" placeholder="Upišite veličinu majice"></td>
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
	
	public function setVelicine($velicine) {
        $this->velicine = $velicine;
        return $this;
    }

}
