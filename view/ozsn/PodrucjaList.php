<?php

namespace view\ozsn;
use app\view\AbstractView;

class PodrucjaList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $podrucja;
	private $korijenski;
    
    protected function outputHTML() {
        // print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
		
		var_dump($this->podrucja);
		
		// klasican DBM, samo kad nudis idNadredjenog ponudi one korijenske i josh jedan prazan value=""
    ?>
		<?php echo new \view\components\DownloadLinks(array(
			"route" => \route\Route::get("d3")->generate(array(
				"controller" => "ozsn",
				"action" => "displayPodrucje"
			))
		)); ?>

		<br><br>
		
		<div class="panel panel-default">
			<div class="panel-heading">Popis područja</div>

			<table class="table">
				<thead>
					<tr>
						<th>Naziv</th>
						<th>ID nadređenog</th>
						<th>Opcije</th>
					</tr>
				</thead>

				<tbody>
<?php

		if(count($this->podrucja))
		{
			// Foreach smjer, generate row in table
			foreach($this->podrucja as $val)
			{
				echo '<form action="';
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'modifyPodrucje'
				));
				echo '" method="POST">';
				echo '<tr><td><span class="modify-' . $val->idPodrucja . '">' . $val->nazivPodrucja . '</span><input type="text" class="modifyOn-' . $val->idPodrucja . '" style="display:none;" name="nazivPodrucja" value="' . $val->nazivPodrucja . '"><input type="hidden" name="idPodrucja" value="' . $val->idPodrucja . '"></td>';
				echo '<td><span class="modify-' . $val->idPodrucja . '">' . $val->idNadredenog . '</span><select class="modifyOn-' . $val->idPodrucja . '" style="display:none;" name="idNadredenog"><option ';
				if(!$val->idNadredenog) echo 'selected="selected"'; ?>
 value="">Nema nadređenog</option>

<?php
		foreach($this->korijenski as $val2)
		{
			if($val->idPodrucja != $val2->idPodrucja)
			{
				echo '<option value="' . $val->idPodrucja . '"';
				if ($val->idNadredenog && $val->idNadredenog == $val2->idPodrucja)
				{
					echo 'selected="selected"';
				}
				echo '>' . $val2->nazivPodrucja . '</option>';
			}
		}				
				echo '</select></td>';
				echo '<td><input type="submit" style="display: none;" class="btn btn-primary modifyOn-' . $val->idPodrucja . '" value="Spremi" /><a href="javascript:;" class="editPodrucje modify-' . $val->idPodrucja . '" data-id="' . $val->idPodrucja . '">Uredi</a> &nbsp; <a class="deletePodrucje modify-' . $val->idPodrucja . '" href="';
				
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'deletePodrucje'
				));
				echo '?id=' . $val->idPodrucja . '">Obriši</a>';
				echo '</td></tr></form>';
			}
		}
		
		else
		{
?>
						<tr>
							<td class="addPodrucje" colspan="2"><i>Ne postoji niti jedno područje</i></td>
						</tr>
<?php
		}
?>
					<tr class="addPodrucje">
						<td colspan="2">
							<a id="addPodrucje" href="javascript:;"><span class="glyphicon glyphicon-plus"></span> Dodaj novo područje</a>
						</td>
					</tr>
					<tr style="display: none;" class="addPodrucjeOn">
						<form action="
							  <?php echo \route\Route::get('d3')->generate(array(
								"controller" => 'ozsn',
								"action" => 'addPodrucje'
							)); ?>							  
							  " method="post">
							<td><input type="text" name="nazivPodrucja" placeholder="Upišite naziv područja"></td>
							<td>
								<select name="idNadredenog"><option value="">Nema nadređenog</option>

<?php
		foreach($this->korijenski as $val2)
		{
			echo '<option value="' . $val->idPodrucja . '"';
			if ($val->idNadredenog && $val->idNadredenog == $val2->idPodrucja)
			{
				echo 'selected="selected"';
			}
			echo '>' . $val2->nazivPodrucja . '</option>';
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
	
	public function setPodrucja($podrucja) {
		$this->podrucja = $podrucja;
		return $this;
	}

	public function setKorijenski($korijenski) {
		$this->korijenski = $korijenski;
		return $this;
	}

}
