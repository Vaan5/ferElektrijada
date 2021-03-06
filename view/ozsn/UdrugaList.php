<?php

namespace view\ozsn;
use app\view\AbstractView;

class UdrugaList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $udruge;
    
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
				"action" => "displayUdruga"
			))
		)); ?>

		<br><br>
		
		<div class="panel panel-default">
			<div class="panel-heading">Popis udruga</div>

			<table class="table">
				<thead>
					<tr>
						<th>Naziv udruge</th>
						<th>Opcije</th>
					</tr>
				</thead>

				<tbody>
<?php

		if(count($this->udruge))
		{
			// Foreach udruga, generate row in table
			foreach($this->udruge as $val)
			{
				echo '<form action="';
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'modifyUdruga'
				));
				echo '" method="POST">';
				echo '<tr><td><span class="modify-' . $val->idUdruge . '">' . $val->nazivUdruge . '</span><input type="text" class="form-control modifyOn-' . $val->idUdruge . '" style="display:none;" name="nazivUdruge" value="' . $val->nazivUdruge . '"><input type="hidden" name="idUdruge" value="' . $val->idUdruge . '"></td>';
				echo '<td><input type="submit" style="display: none;" class="btn btn-primary modifyOn-' . $val->idUdruge . '" value="Spremi" /><a href="javascript:;" class="editUdruga modify-' . $val->idUdruge . '" data-id="' . $val->idUdruge . '"><span class="glyphicon glyphicon-pencil"></span> Uredi</a> &nbsp; <a class="deleteUdruga modify-' . $val->idUdruge . '" href="';
				
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'deleteUdruga'
				));
				echo '?id=' . $val->idUdruge . '"><span class="glyphicon glyphicon-remove"></span> Obriši</a>';
				echo '</td></tr></form>';
			}
		}
		
		else
		{
?>
						<tr>
							<td class="addUdruga" colspan="2"><i>Ne postoji niti jedna udruga</i></td>
						</tr>
<?php
		}
?>
					<tr class="addUdruga">
						<td colspan="2">
							<a id="addUdruga" href="javascript:;"><span class="glyphicon glyphicon-plus"></span> Dodaj novu udrugu</a>
						</td>
					</tr>
					<tr style="display: none;" class="addUdrugaOn">
						<form action="
							  <?php echo \route\Route::get('d3')->generate(array(
								"controller" => 'ozsn',
								"action" => 'addUdruga'
							)); ?>							  
							  " method="post">
							<td><input type="text" class="form-control" name="nazivUdruge" placeholder="Upišite naziv udruge"></td>
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
	
	public function setUdruge($udruge) {
        $this->udruge = $udruge;
        return $this;
    }

}
