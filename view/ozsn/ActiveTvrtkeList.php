<?php

namespace view\ozsn;
use app\view\AbstractView;

class ActiveTvrtkeList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $tvrtke;
    
    protected function outputHTML() {
		// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
		
		if(count($this->tvrtke))
		{
			
?>
			<div class="panel panel-default">
				<div class="panel-heading">Popis aktivnih tvrtki</div>
				
				<table class="table">
				<thead>
					<tr>
						<th>Ime tvrtke</th>
						<th>Usluga</th>
						<th>Iznos</th>
						<th>Opcije</th>
					</tr>
				</thead>
				
				<tbody>
<?php
			// Foreach tvrtka, generate row in table
			foreach($this->tvrtke as $val)
			{
?>
					<tr>
						<td><?php echo $val->imeTvrtke; ?></td>
						<td><?php echo $val->nazivUsluge; ?></td>
						<td><?php echo $val->iznosRacuna . ' ' . $val->valutaRacuna; ?></td>
						<td><a href="					
<?php
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'modifyActiveTvrtka'
				));
				echo '?id=' . $val->idKoristiPruza . '">Uredi</a> &nbsp; <a class="obrisiActiveTvrtku" href="';
				
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'ozsn',
					"action" => 'deleteActiveTvrtka'
				));
				echo '?id=' . $val->idKoristiPruza . '">Obriši</a>';
			}
			
			echo '</tbody></table></div>';
		}
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
