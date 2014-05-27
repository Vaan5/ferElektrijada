<?php

namespace view\ozsn;
use app\view\AbstractView;

class UslugaList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $usluge;
    
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
				"action" => "displayUsluga"
			))
		)); ?>

		<br><br>
		
		<div class="panel panel-default">
			<div class="panel-heading">Popis usluga</div>

			<table class="table">
				<thead>
					<tr>
						<th>Naziv usluge</th>
						<th>Opcije</th>
					</tr>
				</thead>

				<tbody>
<?php

		if(count($this->usluge))
		{
			// Foreach usluga, generate row in table
			foreach($this->usluge as $val)
			{
				echo '<form action="';
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'modifyUsluga'
				));
				echo '" method="POST">';
				echo '<tr><td><span class="modify-' . $val->idUsluge . '">' . $val->nazivUsluge . '</span><input type="text" class="form-control modifyOn-' . $val->idUsluge . '" style="display:none;" name="nazivUsluge" value="' . $val->nazivUsluge . '"><input type="hidden" name="idUsluge" value="' . $val->idUsluge . '"></td>';
				echo '<td><input type="submit" style="display: none;" class="btn btn-primary modifyOn-' . $val->idUsluge . '" value="Spremi" /><a href="javascript:;" class="editUsluga modify-' . $val->idUsluge . '" data-id="' . $val->idUsluge . '"><span class="glyphicon glyphicon-pencil"></span> Uredi</a> &nbsp; <a class="deleteUsluga modify-' . $val->idUsluge . '" href="';
				
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'deleteUsluga'
				));
				echo '?id=' . $val->idUsluge . '"><span class="glyphicon glyphicon-remove"></span> Obriši</a>';
				echo '</td></tr></form>';
			}
		}
		
		else
		{
?>
						<tr>
							<td class="addUsluga" colspan="2"><i>Ne postoji niti jedna usluga</i></td>
						</tr>
<?php
		}
?>
					<tr class="addUsluga">
						<td colspan="2">
							<a id="addUsluga" href="javascript:;"><span class="glyphicon glyphicon-plus"></span> Dodaj novu uslugu</a>
						</td>
					</tr>
					<tr style="display: none;" class="addUslugaOn">
						<form action="
							  <?php echo \route\Route::get('d3')->generate(array(
								"controller" => 'ozsn',
								"action" => 'addUsluga'
							)); ?>							  
							  " method="post">
							<td><input type="text" class="form-control" name="nazivUsluge" placeholder="Upišite naziv usluge"></td>
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
	
	public function setUsluge($usluge) {
        $this->usluge = $usluge;
        return $this;
    }

}
