<?php

namespace view\ozsn;
use app\view\AbstractView;

class AtributList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $atributi;
    
    protected function outputHTML() {
        if(count($this->atributi))
		{
?>
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
			// Foreach atribut, generate row in table
			foreach($this->atributi as $val)
			{
				echo '<form action="modifyAtribut" method="POST">';
				echo '<tr><td><span id="span-' . $val->idAtributa . '">' . $val->nazivAtributa . '</span><input type="text" id="input-' . $val->idAtributa . '" style="display:none;" name="nazivAtributa" value="' . $val->nazivAtributa . '"><input type="hidden" name="idAtributa" value="' . $val->idAtributa . '"></td>';
				echo '<td><input type="submit" style="display: none;" class="btn btn-primary" id="submit-' . $val->idAtributa . '" value="Spremi" /><a href="javascript:;" class="editAtribut" id="uredi-' . $val->idAtributa . '" data-id="' . $val->idAtributa . '">Uredi</a> &nbsp; <a class="deleteAtribut" id="delete-' . $val->idAtributa . '" href="';
				
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'deleteAtribut'
				));
				echo '?id=' . $val->idAtributa . '">Obriši</a>';
				echo '</td></tr></form>';
			}
?>
						<tr class="addAtribut">
							<td colspan="2">
								<a class="addAtribut" id="addAtribut" href="javascript:;"><span class="glyphicon glyphicon-plus"></span> Dodaj novi atribut</a>
							</td>
						</tr>
<?php
		}
		
		else
		{
?>
			<input type="button" id="addAtribut" class="btn btn-primary addAtribut" value="Dodaj novi atribut">
			
			<div class="panel panel-default addAtribut_form" style="display:none;">
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
			$this->errorMessage = "Ne postoji niti jedan atribut";
		}
		?>
						<tr style="display: none;" class="addAtribut_form">
							<form action="addAtribut" method="post">
								<td><input type="text" name="nazivAtributa" placeholder="Upišite naziv atributa"></td>
								<td><input type="submit" class="btn btn-primary" value="Dodaj" /></td>
							</form>
						</tr>
						
					</tbody>
				</table>
			</div>
<?php		
		// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
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
