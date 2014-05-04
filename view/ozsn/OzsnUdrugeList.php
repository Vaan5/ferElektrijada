<?php

namespace view\ozsn;
use app\view\AbstractView;

class OzsnUdrugeList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $sveUdruge;
    private $udrugeKorisnika;
    
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
				"action" => "displayUserUdruge"
			))
		)); ?>

		<br><br>
		
		<div class="panel panel-default">
			<div class="panel-heading">Popis vaših udruga</div>

			<table class="table">
				<thead>
					<tr>
						<th>Naziv udruge</th>
						<th>Opcije</th>
					</tr>
				</thead>

				<tbody>
<?php

		if(count($this->udrugeKorisnika))
		{
			// Foreach Udruga, generate row in table
			foreach($this->udrugeKorisnika as $val)
			{
				echo '<form action="';
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'modifyUdruga'
				));			
				echo '?m=1" method="POST">';
				echo '<tr><td><span class="modify-' . $val->idUdruge . '">' . $val->nazivUdruge . '</span><input type="text" class="modifyOn-' . $val->idUdruge . '" style="display:none;" name="nazivUdruge" value="' . $val->nazivUdruge . '"><input type="hidden" name="idUdruge" value="' . $val->idUdruge . '"></td>';
				echo '<td><input type="submit" style="display: none;" class="btn btn-primary modifyOn-' . $val->idUdruge . '" value="Spremi" /><a href="javascript:;" class="editUdruga modify-' . $val->idUdruge . '" data-id="' . $val->idUdruge . '">Uredi</a> &nbsp; <a class="deleteUdruga modify-' . $val->idUdruge . '" href="';
				
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'deleteUserUdruga'
				));
				echo '?id=' . $val->idUdruge . '">Obriši</a>';
				echo '</td></tr></form>';
			}
		}
		
		else
		{
?>
						<tr>
							<td class="addUdruga" colspan="3"><i>Ne nalazite se u niti jednoj udruzi</i></td>
						</tr>
<?php
		}
?>
					<tr class="addUdruga">
						<td colspan="3">
							<a id="addUdruga" href="javascript:;"><span class="glyphicon glyphicon-plus"></span> Dodaj novu udrugu</a>
						</td>
					</tr>
					<tr style="display: none;" class="addUdrugaOn">
						<form action="
							  <?php echo \route\Route::get('d3')->generate(array(
									"controller" => 'ozsn',
									"action" => 'addUserUdruga'
								));
							  ?>
							  " method="post">
							<td>
								<select name="idUdruge" class="form-control">
<?php
		foreach($this->sveUdruge as $val)
		{
			$pom = false;
			foreach($this->udrugeKorisnika as $val2)
			{
				if($val->idUdruge == $val2->idUdruge) $pom = true;
			}
			if(!$pom) echo '<option value="' . $val->idUdruge . '">' . $val->nazivUdruge . '</option>';
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
    
    public function setSveUdruge($sveUdruge) {
	$this->sveUdruge = $sveUdruge;
	return $this;
    }

    public function setUdrugeKorisnika($udrugeKorisnika) {
	$this->udrugeKorisnika = $udrugeKorisnika;
	return $this;
    }
}
