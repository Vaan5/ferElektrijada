<?php

namespace view\ozsn;
use app\view\AbstractView;

class FunkcijaList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $funkcije;
    
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
				"action" => "displayFunkcija"
			))
		)); ?>

		<br><br>
		
		<div class="panel panel-default">
			<div class="panel-heading">Popis funkcija</div>

			<table class="table">
				<thead>
					<tr>
						<th>Naziv</th>
						<th>Opcije</th>
					</tr>
				</thead>

				<tbody>
<?php

		if(count($this->funkcije))
		{
			// Foreach Funkcija, generate row in table
			foreach($this->funkcije as $val)
			{
				echo '<form action="';
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'modifyFunkcija'
				));
				echo '" method="POST">';
				echo '<tr><td><span class="modify-' . $val->idFunkcije . '">' . $val->nazivFunkcije . '</span><input type="text" class="modifyOn-' . $val->idFunkcije . '" style="display:none;" name="nazivFunkcije" value="' . $val->nazivFunkcije . '"><input type="hidden" name="idFunkcije" value="' . $val->idFunkcije . '"></td>';
				echo '<td><input type="submit" style="display: none;" class="btn btn-primary modifyOn-' . $val->idFunkcije . '" value="Spremi" /><a href="javascript:;" class="editFunkcija modify-' . $val->idFunkcije . '" data-id="' . $val->idFunkcije . '">Uredi</a> &nbsp; <a class="deleteFunkcija modify-' . $val->idFunkcije . '" href="';
				
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'deleteFunkcija'
				));
				echo '?id=' . $val->idFunkcije . '">Obriši</a>';
				echo '</td></tr></form>';
			}
		}
		
		else
		{
?>
						<tr>
							<td class="addFunkcija" colspan="3"><i>Ne postoji niti jedna funkcija</i></td>
						</tr>
<?php
		}
?>
					<tr class="addFunkcija">
						<td colspan="3">
							<a id="addFunkcija" href="javascript:;"><span class="glyphicon glyphicon-plus"></span> Dodaj novu funkciju</a>
						</td>
					</tr>
					<tr style="display: none;" class="addFunkcijaOn">
						<form action="
							  <?php echo \route\Route::get('d3')->generate(array(
								"controller" => 'ozsn',
								"action" => 'addFunkcija'
							)); ?>							  
							  " method="post">
							<td><input type="text" name="nazivFunkcije" placeholder="Upišite naziv funkcije"></td>
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
	
	public function setFunkcije($funkcije) {
        $this->funkcije = $funkcije;
        return $this;
    }

}