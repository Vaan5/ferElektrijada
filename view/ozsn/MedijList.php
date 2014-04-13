<?php

namespace view\ozsn;
use app\view\AbstractView;

class MedijList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $mediji;
    
    protected function outputHTML() {

        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
	
	// KLASIČNI DBM
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

}
