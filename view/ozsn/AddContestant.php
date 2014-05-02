<?php

namespace view\ozsn;
use app\view\AbstractView;

class AddContestant extends AbstractView {
    private $errorMessage;
    private $resultMessage;
	private $radnaMjesta;
	private $smjerovi;
	private $zavodi;
	private $velicine;
	private $godine;
	private $idPodrucja;
	private $podrucja;
	private $atributi;
    
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
                "action" => 'addContestant'
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
			"showVrstaPodrucja" => true,
			"showOption" => true,
			"showTip" => true,
			"atributi" => $this->atributi,
			"podrucja" => $this->podrucja
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

	public function setPodrucja($podrucja) {
		$this->podrucja = $podrucja;
		return $this;
	}

	public function setAtributi($atributi) {
		$this->atributi = $atributi;
		return $this;
	}

}