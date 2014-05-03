<?php

namespace view\ozsn;
use app\view\AbstractView;

class ObjavaAdding extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $mediji;
    private $elektrijade;
    
    protected function outputHTML() {
	// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
	
	// ispisi obrazac za dodavanje objave
	// analogno kao kod sponzora (datoteka nek se zove polje "datoteka")
	// pazi na enctype
	// elektrijade ispises kao dropdown list sa visestrukim izborom
	// mediji dropdown list (SAMO JEDAN SE MOZE IZABRATI)
	// ostali podaci su iz tablice objava
	// 
	// http://stackoverflow.com/questions/944158/php-multiple-dropdown-box-form-submit-to-mysql
	// Ovo ispod je samo nesh sto sam testirao izbrisi to
		
        echo new \view\components\ObjavaForm(array(
			"postAction" => \route\Route::get('d3')->generate(array(
				"controller" => 'ozsn',
				"action" => 'addObjava'
			)),
			"submitButtonText" => "Dodaj objavu",
			"elektrijade" => $this->elektrijade,
			"mediji" => $this->mediji
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
    
    public function setMediji($mediji) {
	$this->mediji = $mediji;
	return $this;
    }

    public function setElektrijade($elektrijade) {
	$this->elektrijade = $elektrijade;
	return $this;
    }
}