<?php

namespace view\voditelj;
use app\view\AbstractView;

class MyDisciplines extends AbstractView {
    private $errorMessage;
    private $resultMessage;
	private $podrucja;
	private $disabled;
	
	protected function outputHTML() {
		echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
	
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
?>
		<div class="panel panel-default">
			<div class="panel-heading">Upravljanje Disciplinom</div>

			<table class="table">
				<thead>
					<tr>
						<th>Naziv</th>
						<th>Opcije</th>
					</tr>
				</thead>

				<tbody>
<?php
		if($this->podrucja !== null && count($this->podrucja))
		{
			// Foreach atribut, generate row in table
			foreach($this->podrucja as $val)
			{
				echo "<tr>";
				echo "<td>" . $val->nazivPodrucja . "</td>";
				if (!$this->disabled) {
					echo "<td><a href=\"" . \route\Route::get('d3')->generate(array(
						"controller" => "voditelj",
						"action" => "assignExistingPerson"
					)) .  "?id=" . $val->idPodrucja . "\">Dodaj postojećeg natjecatelja</a>&nbsp;&nbsp;<a href=\"" . 
						\route\Route::get('d3')->generate(array(
						"controller" => "voditelj",
						"action" => "assignNewPerson"
					)) . "?id=" . $val->idPodrucja ."\">Dodaj novog natjecatelja</a></td>";				
				} else {
					echo "<td>Istekao je rok za promjene</td>";
				}
				echo "</tr>";
			}
		}
		else
		{
?>
			<tr>
				<td class="addAtribut" colspan="2"><i>Niste zaduženi ni za jedno područje!</i></td>
			</tr>
<?php
		}
?>
				</tbody>
			</table>
		</div>
<?php

	echo new \view\components\DownloadLinks(array("route" => \route\Route::get("d3")->generate(array(
			"controller" => "voditelj",
			"action" => "displayPodrucja"
		))));
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
	
	public function setDisabled($disabled) {
		$this->disabled = $disabled;
		return $this;
	}
}