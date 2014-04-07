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
    <form class="form-horizontal" role="form" action="<?php echo $this->postAction;?>" method="POST">
        <div class="form-group">
            <label for="ime" class="col-sm-3 control-label">Ime</label>
                        <div class="col-sm-8">
			<input type="text" name="ime" class="form-control" placeholder="Upišite ime" />
                        </div>
        </div>
        <div class="form-group">
            <label for="prezime" class="col-sm-3 control-label">Prezime</label>
                        <div class="col-sm-8">
			<input type="text" name="prezime" class="form-control" placeholder="Upišite prezime" />
                        </div>
        </div>
        <div class="form-group">
            <label for="korime" class="col-sm-3 control-label">Korisničko ime</label>
                        <div class="col-sm-8">
			<input type="text" name="ferId" class="form-control" placeholder="Upišite korisničko ime" />
                        </div>
        </div>
        <div class="form-group">
            <label for="jmbag" class="col-sm-3 control-label">JMBAG</label>
                        <div class="col-sm-8">
			<input type="text" name="JMBAG" class="form-control" placeholder="Upišite JMBAG" />
                        </div>
        </div>
        
        <div class="form-group">
            <label for="oib" class="col-sm-3 control-label">OIB</label>
                        <div class="col-sm-8">
			<input type="text" name="OIB" class="form-control" placeholder="Upišite OIB" />
                        </div>
        </div>
            <center><input type="submit" class="btn btn-primary" value="<?php echo $this->submitButtonText;?>" /></center>
    </form>

<?php

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