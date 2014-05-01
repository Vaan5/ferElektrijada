<?php

namespace view\ozsn;
use app\view\AbstractView;

class AddTeamLeader extends AbstractView {
    private $errorMessage;
    private $resultMessage;
	private $radnaMjesta;
	private $smjerovi;
	private $zavodi;
	private $velicine;
	private $godine;
	private $idPodrucja;
    
    protected function outputHTML() {
		echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
		
		echo new \view\components\PersonForm(array(
            "postAction" => \route\Route::get('d3')->generate(array(
                "controller" => 'ozsn',
                "action" => 'addTeamLeader'
            )),
            "submitButtonText" => "Dodaj",
			"radnaMjesta" => $this->radnaMjesta,
			"velicine" => $this->velicine,
			"godine" => $this->godine,
			"smjerovi" => $this->smjerovi,
			"zavodi" => $this->zavodi,
			"showDelete" => false,
			"showCV" => false,
			"showDropDown" => true,
			"idPodrucja" => $this->idPodrucja,
			"showTip" => true
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
	
	public function setRadnaMjesta($radnaMjesta) {
		$this->radnaMjesta = $radnaMjesta;
		return $this;
	}

	public function setSmjerovi($smjerovi) {
		$this->smjerovi = $smjerovi;
		return $this;
	}

	public function setZavodi($zavodi) {
		$this->zavodi = $zavodi;
		return $this;
	}

	public function setVelicine($velicine) {
		$this->velicine = $velicine;
		return $this;
	}

	public function setGodine($godine) {
		$this->godine = $godine;
		return $this;
	}

	public function setIdPodrucja($idPodrucja) {
		$this->idPodrucja = $idPodrucja;
		return $this;
	}

}