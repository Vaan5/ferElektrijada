<?php

namespace view\ozsn;
use app\view\AbstractView;

class NacinPromocijeList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $nacini;
    
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
				"action" => "displayNacinPromocije"
			))
		)); ?>

		<br><br>
		
		<div class="panel panel-default">
			<div class="panel-heading">Popis načina promocije</div>

			<table class="table">
				<thead>
					<tr>
						<th>Naziv</th>
						<th>Opcije</th>
					</tr>
				</thead>

				<tbody>
<?php

		if(count($this->nacini))
		{
			// Foreach način promocije, generate row in table
			foreach($this->nacini as $val)
			{
				echo '<form action="';
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'modifyNacinPromocije'
				));
				echo '" method="POST">';
				echo '<tr><td><span class="modify-' . $val->idPromocije . '">' . $val->tipPromocije . '</span><input type="text" class="form-control modifyOn-' . $val->idPromocije . '" style="display:none;" name="tipPromocije" value="' . $val->tipPromocije . '"><input type="hidden" name="idPromocije" value="' . $val->idPromocije . '"></td>';
				echo '<td><input type="submit" style="display: none;" class="btn btn-primary modifyOn-' . $val->idPromocije . '" value="Spremi" /><a href="javascript:;" class="editNacinPromocije modify-' . $val->idPromocije . '" data-id="' . $val->idPromocije . '"><span class="glyphicon glyphicon-pencil"></span> Uredi</a> &nbsp; <a class="deleteNacinPromocije modify-' . $val->idPromocije . '" href="';
				
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'deleteNacinPromocije'
				));
				echo '?id=' . $val->idPromocije . '"><span class="glyphicon glyphicon-remove"></span> Obriši</a>';
				echo '</td></tr></form>';
			}
		}
		
		else
		{
?>
						<tr>
							<td class="addNacinPromocije" colspan="2"><i>Ne postoji niti jedan način promocije</i></td>
						</tr>
<?php
		}
?>
					<tr class="addNacinPromocije">
						<td colspan="2">
							<a id="addNacinPromocije" href="javascript:;"><span class="glyphicon glyphicon-plus"></span> Dodaj novi način promocije</a>
						</td>
					</tr>
					<tr style="display: none;" class="addNacinPromocijeOn">
						<form action="
							  <?php echo \route\Route::get('d3')->generate(array(
								"controller" => 'ozsn',
								"action" => 'addNacinPromocije'
							)); ?>							  
							  " method="post">
							<td><input type="text" class="form-control" name="tipPromocije" placeholder="Upišite tip promocije"></td>
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
	
	public function setNacini($nacini) {
        $this->nacini = $nacini;
        return $this;
    }

}
