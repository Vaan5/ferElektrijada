<?php

namespace view\administrator;
use app\view\AbstractView;

class ElektrijadaList extends AbstractView {
    /**
     *
     * @var array of objects 
     */
    private $elektrijade;
    
    /**
     *
     * @var string 
     */
    private $errorMessage;
    
    /**
     *
     * @var string 
     */
    private $resultMessage;
    
    protected function outputHTML() {
        /* Napravi ispis u obliku tablice
         * neka sadrzi godinu odrzavanja , mjesto, drzavu i jedan link na uredjivanje (mozes ga staviti recimo da klikom na godinu se uredjuje)
         * u linku dodaj kao get parametar id elektrijade (preusmjeri  na odgovarajucu akciju controllera Administrator)
         * GET PARAMETAR SE MORA ZVATI 'id' (bez navodnika)
         * 
         * Da vidis kako tocno izgleda varijabla elektrijada ukucaj
         * var_dump($this->elektrijada);
         * var_dump($this->elektrijada);
         * var_dump($this->elektrijada);
         * die();
         * 
         * PRIJE ISPISA PRVO POGLEDAJ JE LI ERRORMESSAGE != NULL AKO JE SAMO ISPISI (bilo koristeci ErrorMessage view ili bez njega)
         * da nije upisana niti jedna elektrijada
         * 
         * Ako je postavljena resultMessage ispisi ju koristeci ResultMessage pogled
         * 
         */
		
		if($this->errorMessage)
		{
			echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
			));
		}
		
		// Else show elektrijade in table
		else
		{
			
?>
			<div class="panel panel-default">
				<div class="panel-heading">Popis elektrijada</div>
				
				<table class="table">
				<thead>
					<tr>
						<th>Godina</th>
						<th>Mjesto</th>
						<th>Država</th>
						<th>Opcije</th>
					</tr>
				</thead>
				
				<tbody>
<?php
			// Foreach Ozsn member, generate row in table
			foreach($this->elektrijade as $val)
			{
				echo '<tr><td>' . date('Y', strtotime($val->datumPocetka)) . '</td><td>' . $val->mjestoOdrzavanja . '</td><td>' . $val->drzava . '</td>';
				echo '<td><a href="';
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'administrator',
					"action" => 'modifyElektrijada'
				));
				echo '?id=' . $val->idElektrijade . '">Uredi</a> &nbsp; <a class="obrisiElektrijadu" href="';
				
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'administrator',
					"action" => 'deleteElektrijada'
				));
				echo '?id=' . $val->idElektrijade . '">Obriši</a>';
			}
			
			echo '</tbody></table></div>';
		}
		
		// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
    }
    
    public function setElektrijade($elektrijade) {
        $this->elektrijade = $elektrijade;
        return $this;
    }
    
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    public function setResultMessage($resultMessage) {
        $this->resultMessage = $resultMessage;
        return $this;
    }

}