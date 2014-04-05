<?php

namespace view\components;
use app\view\AbstractView;

class MediumPersonSearchForm extends AbstractView {
    /**
     *
     * @var string url of the script to handle this form data
     */
    private $postAction;
    
    /**
     *
     * @var string submit button text
     */
    private $submitButtonText;
	
	/**
     *
     * @var string show all button text
     */
    private $showAllButtonText;
    
    protected function outputHTML() {
        /*
         * dodaj kucice za pretragu po imenu prezimenu korisnickom imenu (nazovi name atribute kao sto su u bazi)
         * poziva se akcija displayOzsn (predajes kao postAction)
         * + dodaj jedno dugme izvan forme u kojem ce biti link na displayOzsn (kao get parametar a=1)
         * u tom slucaju ce se ispisati svi clanovi odbora
         * To dugme parametriziraj kao privatnu varijablu (da li ce se ispisati ili ne i koji ce tekst biti na njemu)
         */
?>
    <form action="<?php echo $this->postAction;?>" method="POST">
		<p>Ime: &nbsp;
			<input type="text" name="ime" placeholder="Upišite ime" />
        </p>
		<p>Prezime: &nbsp;
			<input type="text" name="prezime" placeholder="Upišite prezime" />
        </p>
		<p>Korisničko ime: &nbsp;
			<input type="text" name="ferId" placeholder="Upišite korisničko ime" />
        </p>
            <p>JMBAG: &nbsp;
			<input type="text" name="JMBAG" placeholder="Upišite JMBAG" />
        </p>
            <p>OIB: &nbsp;
			<input type="text" name="OIB" placeholder="Upišite OIB" />
        </p>
        <input type="submit" class="btn btn-primary" value="<?php echo $this->submitButtonText;?>" />
    </form>

<?php
		if($this->showAllButtonText)
		{
			echo '<a href="';
			echo \route\Route::get('d3')->generate(array(
				"controller" => 'administrator',
				"action" => 'displayPersons'
			));
			echo '?a=1">' . $this->showAllButtonText . '</a>';
		}

    }
    
    public function setPostAction($postAction) {
        $this->postAction = $postAction;
        return $this;
    }

    public function setSubmitButtonText($submitButtonText) {
        $this->submitButtonText = $submitButtonText;
        return $this;
    }
	
	public function setShowAllButtonText($showAllButtonText) {
        $this->showAllButtonText = $showAllButtonText;
        return $this;
    }
    
}