<?php

namespace view\ozsn;
use app\view\AbstractView;

class KategorijaList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $kategorije;
    
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
				"action" => "displayKategorija"
			))
		)); ?>

		<br><br>
		
		<div class="panel panel-default">
			<div class="panel-heading">Popis kategorija sponzora</div>

			<table class="table">
				<thead>
					<tr>
						<th>Tip</th>
						<th>Opcije</th>
					</tr>
				</thead>

				<tbody>
<?php

		if(count($this->kategorije))
		{
			// Foreach kategorija, generate row in table
			foreach($this->kategorije as $val)
			{
				echo '<form action="';
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'modifyKategorija'
				));
				echo '" method="POST">';
				echo '<tr><td><span class="modify-' . $val->idKategorijeSponzora . '">' . $val->tipKategorijeSponzora . '</span><input type="text" class="modifyOn-' . $val->idKategorijeSponzora . '" style="display:none;" name="tipKategorijeSponzora" value="' . $val->tipKategorijeSponzora . '"><input type="hidden" name="idKategorijeSponzora" value="' . $val->idKategorijeSponzora . '"></td>';
				echo '<td><input type="submit" style="display: none;" class="btn btn-primary modifyOn-' . $val->idKategorijeSponzora . '" value="Spremi" /><a href="javascript:;" class="editKategorija modify-' . $val->idKategorijeSponzora . '" data-id="' . $val->idKategorijeSponzora . '">Uredi</a> &nbsp; <a class="deleteKategorija modify-' . $val->idKategorijeSponzora . '" href="';
				
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'deleteKategorija'
				));
				echo '?id=' . $val->idKategorijeSponzora . '">Obriši</a>';
				echo '</td></tr></form>';
			}
		}
		
		else
		{
?>
						<tr>
							<td class="addKategorija" colspan="2"><i>Ne postoji niti jedna kategorija sponzora</i></td>
						</tr>
<?php
		}
?>
					<tr class="addKategorija">
						<td colspan="2">
							<a id="addKategorija" href="javascript:;"><span class="glyphicon glyphicon-plus"></span> Dodaj novu kategoriju</a>
						</td>
					</tr>
					<tr style="display: none;" class="addKategorijaOn">
						<form action="
							  <?php echo \route\Route::get('d3')->generate(array(
								"controller" => 'ozsn',
								"action" => 'addKategorija'
							)); ?>							  
							  " method="post">
							<td><input type="text" name="tipKategorijeSponzora" placeholder="Upišite tip kategorije"></td>
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
	
	public function setKategorije($kategorije) {
        $this->kategorije = $kategorije;
        return $this;
    }

}
