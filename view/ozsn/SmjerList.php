<?php

namespace view\ozsn;
use app\view\AbstractView;

class SmjerList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $smjerovi;
    
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
				"action" => "displaySmjer"
			))
		)); ?>

		<br><br>
		
		<div class="panel panel-default">
			<div class="panel-heading">Popis smjerova</div>

			<table class="table">
				<thead>
					<tr>
						<th>Naziv</th>
						<th>Opcije</th>
					</tr>
				</thead>

				<tbody>
<?php

		if(count($this->smjerovi))
		{
			// Foreach smjer, generate row in table
			foreach($this->smjerovi as $val)
			{
				echo '<form action="';
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'modifySmjer'
				));
				echo '" method="POST">';
				echo '<tr><td><span class="modify-' . $val->idSmjera . '">' . $val->nazivSmjera . '</span><input type="text" class="form-control modifyOn-' . $val->idSmjera . '" style="display:none;" name="nazivSmjera" value="' . $val->nazivSmjera . '"><input type="hidden" name="idSmjera" value="' . $val->idSmjera . '"></td>';
				echo '<td><input type="submit" style="display: none;" class="btn btn-primary modifyOn-' . $val->idSmjera . '" value="Spremi" /><a href="javascript:;" class="editSmjer modify-' . $val->idSmjera . '" data-id="' . $val->idSmjera . '"><span class="glyphicon glyphicon-pencil"></span> Uredi</a> &nbsp; <a class="deleteSmjer modify-' . $val->idSmjera . '" href="';
				
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'deleteSmjer'
				));
				echo '?id=' . $val->idSmjera . '"><span class="glyphicon glyphicon-remove"></span> Obriši</a>';
				echo '</td></tr></form>';
			}
		}
		
		else
		{
?>
						<tr>
							<td class="addSmjer" colspan="2"><i>Ne postoji niti jedan smjer</i></td>
						</tr>
<?php
		}
?>
					<tr class="addSmjer">
						<td colspan="2">
							<a id="addSmjer" href="javascript:;"><span class="glyphicon glyphicon-plus"></span> Dodaj novi smjer</a>
						</td>
					</tr>
					<tr style="display: none;" class="addSmjerOn">
						<form action="
							  <?php echo \route\Route::get('d3')->generate(array(
								"controller" => 'ozsn',
								"action" => 'addSmjer'
							)); ?>							  
							  " method="post">
							<td><input type="text" class="form-control" name="nazivSmjera" placeholder="Upišite naziv smjera"></td>
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
	
	public function setSmjerovi($smjerovi) {
        $this->smjerovi = $smjerovi;
        return $this;
    }

}
