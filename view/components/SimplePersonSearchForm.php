<?php

namespace view\components;
use app\view\AbstractView;

class SimplePersonSearchForm extends AbstractView {
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
        
        <input type="submit" class="btn btn-primary" value="<?php echo $this->submitButtonText;?>" />
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
    
}