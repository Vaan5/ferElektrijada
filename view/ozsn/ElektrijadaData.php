<?php

namespace view\ozsn;
use app\view\AbstractView;

class ElektrijadaData extends AbstractView {
	private $errorMessage;
    private $resultMessage;
	private $elektrijada;
	
	protected function outputHTML() {
		// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
		
		echo new \view\components\ElektrijadaForm(array(
			"postAction" => \route\Route::get('d3')->generate(array(
				"controller" => "ozsn",
				"action" => "modifyElektrijada"
			)),
			"submitButtonText" =>  "Ažuriraj",
			"elektrijada" => $this->elektrijada,
			"modifyDates" => false
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
	
	public function setElektrijada($elektrijada) {
		$this->elektrijada = $elektrijada;
		return $this;
	}

}
