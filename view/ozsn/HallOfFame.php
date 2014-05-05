<?php

namespace view\ozsn;
use app\view\AbstractView;

class HallOfFame extends AbstractView {
    private $errorMessage;
    private $resultMessage;
	private $rezultati;
    
    protected function outputHTML() {
		// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
		
		// izgeneriraj prikaz rezultata: dakle ukupan za cijelu elektrijadu, ukupan za znanje i ostale korijenske discipline (idNadredjene  = NULL)
		// + za svako podrucje rezultat
		// ISPITAJ KRAJNJE SLUCAJEVE
	
		var_dump($this->rezultati);
    }
    
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    public function setResultMessage($resultMessage) {
        $this->resultMessage = $resultMessage;
        return $this;
    }
	
	public function setRezultati($rezultati) {
		$this->rezultati = $rezultati;
		return $this;
	}

}