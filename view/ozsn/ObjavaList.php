<?php

namespace view\ozsn;
use app\view\AbstractView;

class ObjavaList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $objave;
    
    protected function outputHTML() {
	// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
	
	// Objave su klasicni objetkni modeli
    }
    
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    public function setResultMessage($resultMessage) {
        $this->resultMessage = $resultMessage;
        return $this;
    }
    
    public function setObjave($objave) {
	$this->objave = $objave;
	return $this;
    }
}