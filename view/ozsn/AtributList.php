<?php

namespace view\ozsn;
use app\view\AbstractView;

class AtributList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $atributi;
    
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
				"action" => "displayAtribut"
			))
		)); ?>

		<br><br>

		<div class="panel panel-default">
			<div class="panel-heading">Popis atributa</div>

			<table class="table">
				<thead>
					<tr>
						<th>Naziv</th>
						<th>Opcije</th>
					</tr>
				</thead>

				<tbody>
<?php

		if(count($this->atributi))
		{
			// Foreach atribut, generate row in table
			foreach($this->atributi as $val)
			{
				echo '<form action="';
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'modifyAtribut'
				));
				echo '" method="POST">';
				echo '<tr><td><span class="modify-' . $val->idAtributa . '">' . $val->nazivAtributa . '</span><input type="text" class="form-control modifyOn-' . $val->idAtributa . '" style="display:none;" name="nazivAtributa" value="' . $val->nazivAtributa . '"><input type="hidden" name="idAtributa" value="' . $val->idAtributa . '"></td>';
				echo '<td><input type="submit" style="display: none;" class="btn btn-primary modifyOn-' . $val->idAtributa . '" value="Spremi" /><a href="javascript:;" class="editAtribut modify-' . $val->idAtributa . '" data-id="' . $val->idAtributa . '">Uredi</a> &nbsp; <a class="deleteAtribut modify-' . $val->idAtributa . '" href="';
				
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'deleteAtribut'
				));
				echo '?id=' . $val->idAtributa . '">Obriši</a>';
				echo '</td></tr></form>';
			}
		}
		
		else
		{
?>
						<tr>
							<td class="addAtribut" colspan="2"><i>Ne postoji ni jedan atribut</i></td>
						</tr>
<?php
		}
?>
					<tr class="addAtribut">
						<td colspan="2">
							<a id="addAtribut" href="javascript:;"><span class="glyphicon glyphicon-plus"></span> Dodaj novi atribut</a>
						</td>
					</tr>
					<tr style="display: none;" class="addAtributOn">
						<form action="
							<?php echo \route\Route::get('d3')->generate(array(
								"controller" => 'ozsn',
								"action" => 'addAtribut'
							));?>
							  " method="post">
							<td><input type="text" class="form-control" name="nazivAtributa" placeholder="Upišite naziv atributa"></td>
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
	
	public function setAtributi($atributi) {
        $this->atributi = $atributi;
        return $this;
    }

}
