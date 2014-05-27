<?php

namespace view\ozsn;
use app\view\AbstractView;

class ZavodList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $zavodi;
    
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
				"action" => "displayZavod"
			))
		)); ?>

		<br><br>
		
		<div class="panel panel-default">
			<div class="panel-heading">Popis zavoda</div>

			<table class="table">
				<thead>
					<tr>
						<th>Naziv</th>
						<th>Skraćeni naziv</th>
						<th>Opcije</th>
					</tr>
				</thead>

				<tbody>
<?php

		if(count($this->zavodi))
		{
			// Foreach zavod, generate row in table
			foreach($this->zavodi as $val)
			{
				echo '<form action="';
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'modifyZavod'
				));
				echo '" method="POST">';
				echo '<tr><td><span class="modify-' . $val->idZavoda . '">' . $val->nazivZavoda . '</span><input type="text" class="form-control modifyOn-' . $val->idZavoda . '" style="display:none;" name="nazivZavoda" value="' . $val->nazivZavoda . '"><input type="hidden" name="idZavoda" value="' . $val->idZavoda . '"></td>';
				echo '<td><span class="modify-' . $val->idZavoda . '">' . $val->skraceniNaziv . '</span><input type="text" class="form-control modifyOn-' . $val->idZavoda . '" style="display:none;" name="skraceniNaziv" value="' . $val->skraceniNaziv . '">';
				echo '<td><input type="submit" style="display: none;" class="btn btn-primary modifyOn-' . $val->idZavoda . '" value="Spremi" /><a href="javascript:;" class="editZavod modify-' . $val->idZavoda . '" data-id="' . $val->idZavoda . '"><span class="glyphicon glyphicon-pencil"></span> Uredi</a> &nbsp; <a class="deleteZavod modify-' . $val->idZavoda . '" href="';
				
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'deleteZavod'
				));
				echo '?id=' . $val->idZavoda . '"><span class="glyphicon glyphicon-remove"></span> Obriši</a>';
				echo '</td></tr></form>';
			}
		}
		
		else
		{
?>
						<tr>
							<td class="addZavod" colspan="3"><i>Ne postoji ni jedan zavod</i></td>
						</tr>
<?php
		}
?>
					<tr class="addZavod">
						<td colspan="3">
							<a id="addZavod" href="javascript:;"><span class="glyphicon glyphicon-plus"></span> Dodaj novi zavod</a>
						</td>
					</tr>
					<tr style="display: none;" class="addZavodOn">
						<form action="
							  <?php echo \route\Route::get('d3')->generate(array(
								"controller" => 'ozsn',
								"action" => 'addZavod'
							)); ?>							  
							  " method="post">
							<td><input type="text" class="form-control" name="nazivZavoda" placeholder="Upišite naziv zavoda"></td>
							<td><input type="text" class="form-control" name="skraceniNaziv" placeholder="Upišite skraćeni naziv"></td>
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
	
	public function setZavodi($zavodi) {
        $this->zavodi = $zavodi;
        return $this;
    }

}