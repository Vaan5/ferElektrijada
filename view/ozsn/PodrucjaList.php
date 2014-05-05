<?php

namespace view\ozsn;
use app\view\AbstractView;

class PodrucjaList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $podrucja;
	private $korijenski;
    
    protected function outputHTML() {
		// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
		
		// klasican DBM, samo kad nudis idNadredjenog ponudi one korijenske i josh jedan prazan value=""
    }
    
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    public function setResultMessage($resultMessage) {
        $this->resultMessage = $resultMessage;
        return $this;
    }
	
	public function setPodrucja($podrucja) {
		$this->podrucja = $podrucja;
		return $this;
	}

	public function setKorijenski($korijenski) {
		$this->korijenski = $korijenski;
		return $this;
	}

}
