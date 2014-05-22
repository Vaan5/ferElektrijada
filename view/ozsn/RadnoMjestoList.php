<?php

namespace view\ozsn;
use app\view\AbstractView;

class RadnoMjestoList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $naziv;
    
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
				"action" => "displayRadnoMjesto"
			))
		)); ?>

		<br><br>
		
		<div class="panel panel-default">
			<div class="panel-heading">Popis radnih mjesta</div>

			<table class="table">
				<thead>
					<tr>
						<th>Naziv</th>
						<th>Opcije</th>
					</tr>
				</thead>

				<tbody>
<?php

		if(count($this->naziv))
		{
			// Foreach naziv, generate row in table
			foreach($this->naziv as $val)
			{
				echo '<form action="';
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'modifyRadnoMjesto'
				));
				echo '" method="POST">';
				echo '<tr><td><span class="modify-' . $val->idRadnogMjesta . '">' . $val->naziv . '</span><input type="text" class="form-control modifyOn-' . $val->idRadnogMjesta . '" style="display:none;" name="naziv" value="' . $val->naziv . '"><input type="hidden" name="idRadnogMjesta" value="' . $val->idRadnogMjesta . '"></td>';
				echo '<td><input type="submit" style="display: none;" class="btn btn-primary modifyOn-' . $val->idRadnogMjesta . '" value="Spremi" /><a href="javascript:;" class="editRadnoMjesto modify-' . $val->idRadnogMjesta . '" data-id="' . $val->idRadnogMjesta . '">Uredi</a> &nbsp; <a class="deleteRadnoMjesto modify-' . $val->idRadnogMjesta . '" href="';
				
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'deleteRadnoMjesto'
				));
				echo '?id=' . $val->idRadnogMjesta . '">Obriši</a>';
				echo '</td></tr></form>';
			}
		}
		
		else
		{
?>
						<tr>
							<td class="addRadnoMjesto" colspan="2"><i>Ne postoji niti jedno radno mjesto</i></td>
						</tr>
<?php
		}
?>
					<tr class="addRadnoMjesto">
						<td colspan="2">
							<a id="addRadnoMjesto" href="javascript:;"><span class="glyphicon glyphicon-plus"></span> Dodaj novo radno mjesto</a>
						</td>
					</tr>
					<tr style="display: none;" class="addRadnoMjestoOn">
						<form action="
							  <?php echo \route\Route::get('d3')->generate(array(
								"controller" => 'ozsn',
								"action" => 'addRadnoMjesto'
							)); ?>							  
							  " method="post">
							<td><input type="text" class="form-control" name="naziv" placeholder="Upišite naziv radnog mjesta"></td>
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
	
	public function setNaziv($naziv) {
        $this->naziv = $naziv;
        return $this;
    }

}
