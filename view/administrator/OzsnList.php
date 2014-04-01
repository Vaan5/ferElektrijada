<?php

namespace view\administrator;
use app\view\AbstractView;

class OzsnList extends AbstractView {
    /**
     *
     * @var array 
     */
    private $osobe;
    
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
         * neka sadrzi neke temeljne podatke (NIKAKO NE ID) i jedan link na uredjivanje (mozes ga staviti recimo da klikom na godinu se uredjuje)
         * u linku dodaj kao get parametar id osobe (preusmjeri  na odgovarajucu akciju controllera Administrator)
         * GET PARAMETAR SE MORA ZVATI 'id' (bez navodnika)
         * 
         * 
         * 
         * PRIJE ISPISA PRVO POGLEDAJ JE LI ERRORMESSAGE != NULL AKO JE SAMO ISPISI (bilo koristeci ErrorMessage view ili bez njega)
         * da nije pronađen niti jedan član odbora koji zadovoljava parametre pretrage
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
		
		// Else listOzsn in table
		else
		{
			
?>
			<div class="panel panel-default">
				<div class="panel-heading">Članovi odbora</div>
				
				<table class="table">
				<thead>
					<tr>
						<th>Ime</th>
						<th>Prezime</th>
						<th>FerID</th>
						<th>Opcije</th>
					</tr>
				</thead>
				
				<tbody>
<?php
			// Foreach Ozsn member, generate row in table
			foreach($this->osobe as $val)
			{
				echo '<tr><td>' . $val->ime . '</td><td>' . $val->prezime . '</td><td>' . $val->ferId . '</td>';
				echo '<td><a href="';
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'administrator',
					"action" => 'modifyOzsn'
				));
				echo '?id=' . $val->idOsobe . '">Uredi</a></td></tr>';
			}
			
			echo '</tbody></table></div>';
		}
    }
    
    public function setOsobe($osobe) {
        $this->osobe = $osobe;
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