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
				<form action="modifyAtribut" method="POST"> 
<?php
			// Foreach atribut, generate row in table
			foreach($this->atributi as $val)
			{
				echo '<tr><td><span id="span-' . $val->idAtributa . '">' . $val->nazivAtributa . '</span><input type="text" id="input-' . $val->idAtributa . '" style="display:none;" name="nazivAtributa" value="' . $val->nazivAtributa . '"><input type="hidden" name="idAtributa" value="' . $val->idAtributa . '"></td>';
				echo '<td><input type="submit" style="display: none;" class="btn btn-primary" id="submit-' . $val->idAtributa . '" value="Spremi" /><a href="javascript:;" class="urediAtribut" id="uredi-' . $val->idAtributa . '" data-id="' . $val->idAtributa . '">Uredi</a> &nbsp; <a class="obrisiAtribut" id="obrisi-' . $val->idAtributa . '" href="';
				
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'deleteAtribut'
				));
				echo '?id=' . $val->idAtributa . '">Obriši</a>';
			}
			
			echo '</td></tr></form></tbody></table></div>';

		}
		
		else
		{
			$this->errorMessage = "Ne postoji niti jedan atribut";
		}
		
		// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
?>		
		<input type="button" id="addAtribut" class="btn btn-primary" value="Dodaj novi atribut" />			
		<form action="addAtribut" method="post">
			<input type="text" id="addAtribut_input" name="nazivAtributa" style="display:none;" placeholder="Upišite naziv atributa">
			<input type="submit" id="addAtribut_submit" style="display: none;" class="btn btn-primary" value="Dodaj" />
		</form>
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
	
	public function setAtributi($atributi) {
        $this->atributi = $atributi;
        return $this;
    }

}
