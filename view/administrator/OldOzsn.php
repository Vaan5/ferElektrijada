<?php

namespace view\administrator;
use app\view\AbstractView;

class OldOzsn extends AbstractView {
    /**
     *
     * @var string 
     */
    private $errorMessage;
    
    /**
     *
     * @var array 
     */
    private $clanovi;
    
    protected function outputHTML() {
        /*
         * prvo provjeri je li errorMessage postavljen ako je ispisi ga i ne ispisuj nista vise
         * ako nije proiteriraj kroz polje i generiraj listu sa imenom, prezimenom, korisnickim imenom clana odbora
         * plus pored svakog stavi link koji ce ga dodati kao aktivnog clana za ovu godinu (parametar get zahtjeva neka se zove id
         *  a akcija controllera listOldOzsn)
         * 
         * Na kraju stavi (dugme / link) koje ce preusmjeriti isto na listOldOzsn a sa get parametrom a=1
         * tu cu dodati sve clanove od prosle godine 
         * 
         */
		
		// Print errorMessage if is set
		if($this->errorMessage)
		{
			echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
			));
		}
		
		// Else showOldOzsn in table
		else
		{
			
?>
			<div class="panel panel-default">
				<div class="panel-heading">Prošlogodišnji članovi odbora</div>
				
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
			// Foreach oldOzsn member, generate row in table
			foreach($this->clanovi as $val)
			{
				echo '<tr><td>' . $val->ime . '</td><td>' . $val->prezime . '</td><td>' . $val->ferId . '</td>';
				echo '<td><a href="';
				echo \route\Route::get('d3')->generate(array(
					"controller" => 'administrator',
					"action" => 'listOldOzsn'
				));
				echo '?id=' . $val->idOsobe . '">Dodaj</a></td></tr>';
			}
			
			echo '</tbody></table></div>';
		}
    }
    
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }
    
    public function setClanovi($clanovi) {
        $this->clanovi = $clanovi;
        return $this;
    }

}