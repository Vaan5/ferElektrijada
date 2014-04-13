<?php

namespace view\ozsn;
use app\view\AbstractView;

class AreaSponzorModification extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $podrucja;
    private $sponelekpod;
    
    protected function outputHTML() {
	// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
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
    
    public function setPodrucja($podrucja) {
	$this->podrucja = $podrucja;
	return $this;
    }
    
    public function setSponelekpod($sponelekpod) {
	$this->sponelekpod = $sponelekpod;
	return $this;
    }

}