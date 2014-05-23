<?php

namespace view\ozsn;
use app\view\AbstractView;

class OzsnFunctionsList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $sveFunkcije;
    private $funkcijeKorisnika;
	
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
				"action" => "displayUserFunctions"
			))
		)); ?>

		<br><br>
		
		<div class="panel panel-default">
			<div class="panel-heading">Popis vaših funkcija</div>

			<table class="table">
				<thead>
					<tr>
						<th>Naziv funkcije</th>
						<th>Opcije</th>
					</tr>
				</thead>

				<tbody>
<?php

		if(count($this->funkcijeKorisnika))
		{
			// Foreach Funkcija, generate row in table
			foreach($this->funkcijeKorisnika as $val)
			{
				echo '<form action="';
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'modifyFunkcija'
				));			
				echo '?m=1" method="POST">';
				echo '<tr><td><span class="modify-' . $val->idFunkcije . '">' . $val->nazivFunkcije . '</span><input type="text" class="modifyOn-' . $val->idFunkcije . '" style="display:none;" name="nazivFunkcije" value="' . $val->nazivFunkcije . '"><input type="hidden" name="idFunkcije" value="' . $val->idFunkcije . '"></td>';
				echo '<td><input type="submit" style="display: none;" class="btn btn-primary modifyOn-' . $val->idFunkcije . '" value="Spremi" /><a href="javascript:;" class="editFunkcija modify-' . $val->idFunkcije . '" data-id="' . $val->idFunkcije . '"><span class="glyphicon glyphicon-pencil"></span> Uredi</a> &nbsp; <a class="deleteFunkcija modify-' . $val->idFunkcije . '" href="';
				
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'deleteUserFunction'
				));
				echo '?id=' . $val->idObavljaFunkciju . '"><span class="glyphicon glyphicon-remove"></span> Obriši</a>';
				echo '</td></tr></form>';
			}
		}
		
		else
		{
?>
						<tr>
							<td class="addFunkcija" colspan="3"><i>Nemate niti jednu funkciju</i></td>
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
									"action" => 'addUserFunction'
								));
							  ?>
							  " method="post">
							<td>
								<select name="idFunkcije" class="form-control">
<?php
		foreach($this->sveFunkcije as $val)
		{
			$pom = false;
			foreach($this->funkcijeKorisnika as $val2)
			{
				if($val->idFunkcije == $val2->idFunkcije) $pom = true;
			}
			if(!$pom) echo '<option value="' . $val->idFunkcije . '">' . $val->nazivFunkcije . '</option>';
		}
?>					
								</select>
							</td>
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
    
    public function setSveFunkcije($sveFunkcije) {
	$this->sveFunkcije = $sveFunkcije;
	return $this;
    }

    public function setFunkcijeKorisnika($funkcijeKorisnika) {
	$this->funkcijeKorisnika = $funkcijeKorisnika;
	return $this;
    }

}