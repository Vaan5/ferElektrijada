<?php

namespace view\ozsn;
use app\view\AbstractView;

class TvrtkaList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $tvrtke;
    
    protected function outputHTML() {
        // dodaj settere
        // + ispis errorMessage i resultMessage
	// KLASICNI DBM + dodaj opciju pored Pridruzi Elektrijadi (akcija assignTvrtka) -> get parametar id
		
		// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
?>
		<div class="panel panel-default">
			<div class="panel-heading">Popis tvrtki</div>

			<table class="table">
				<thead>
					<tr>
						<th>Ime tvrtke</th>
						<th>Adresa</th>
						<th>Opcije</th>
					</tr>
				</thead>

				<tbody>
<?php

		if(count($this->tvrtke))
		{
			// Foreach Tvrtka, generate row in table
			foreach($this->tvrtke as $val)
			{
				echo '<form action="modifyTvrtka" method="POST">';
				echo '<tr><td><span class="modify-' . $val->idTvrtka . '">' . $val->imeTvrtke . '</span><input type="text" class="modifyOn-' . $val->idTvrtke . '" style="display:none;" name="imeTvrtke" value="' . $val->imeTvrtke . '"><input type="hidden" name="idTvrtke" value="' . $val->idTvrtke . '"></td>';
				echo '<td><span class="modify-' . $val->idTvrtke . '">' . $val->adresaTvrtke . '</span><input type="text" class="modifyOn-' . $val->idTvrtke . '" style="display:none;" name="adresaTvrtke" value="' . $val->adresaTvrtke . '">';
				echo '<td><input type="submit" style="display: none;" class="btn btn-primary modifyOn-' . $val->idTvrtke . '" value="Spremi" /><a href="javascript:;" class="editTvrtka modify-' . $val->idTvrtke . '" data-id="' . $val->idTvrtke . '">Uredi</a> &nbsp; <a class="deleteTvrtka modify-' . $val->idTvrtke . '" href="';
				
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'deleteTvrtka'
				));
				echo '?id=' . $val->idTvrtke . '">Obriši</a>';
				echo '</td></tr></form>';
			}
		}
		
		else
		{
?>
						<tr>
							<td class="addTvrtka" colspan="3"><i>Ne postoji niti jedna tvrtka</i></td>
						</tr>
<?php
		}
?>
					<tr class="addTvrtka">
						<td colspan="3">
							<a id="addTvrtka" href="javascript:;"><span class="glyphicon glyphicon-plus"></span> Dodaj novu tvrtku</a>
						</td>
					</tr>
					<tr style="display: none;" class="addTvrtkaOn">
						<form action="addTvrtka" method="post">
							<td><input type="text" name="imeTvrtke" placeholder="Upišite ime tvrtke"></td>
							<td><input type="text" name="adresaTvrtke" placeholder="Upišite adresu tvrtke"></td>
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
    
    public function setTvrtke($tvrtke) {
	$this->tvrtke = $tvrtke;
	return $this;
    }
}
