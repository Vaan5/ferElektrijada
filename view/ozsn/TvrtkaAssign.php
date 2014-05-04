<?php

namespace view\ozsn;
use app\view\AbstractView;

class TvrtkaAssign extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $tvrtka;
    private $usluge;
    
    protected function outputHTML() {
		// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
		
		echo new \view\components\TvrtkaAssignForm(array(
			"route" => \route\Route::get('d3')->generate(array(
				"controller" => 'ozsn',
				"action" => 'assignTvrtka'
			)),
			"submitButtonText" => "Spremi promjene",
			"tvrtka" => $this->tvrtka,
			"usluge" => $this->usluge
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
	
	public function setTvrtka($tvrtka) {
        $this->tvrtka = $tvrtka;
        return $this;
    }
	
	public function setUsluge($usluge) {
        $this->usluge = $usluge;
        return $this;
    }
	
}
