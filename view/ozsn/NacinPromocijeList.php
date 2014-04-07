<?php

namespace view\ozsn;
use app\view\AbstractView;

class NacinPromocijeList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $nacini;
    
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
    
    public function setNacini($nacini) {
	$this->nacini = $nacini;
	return $this;
    }


}
