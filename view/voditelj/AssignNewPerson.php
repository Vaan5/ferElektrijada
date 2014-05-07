<?php

namespace view\voditelj;
use app\view\AbstractView;

class AssignNewPerson extends AbstractView {
    private $errorMessage;
    private $resultMessage;
	private $idPodrucja;
	
	protected function outputHTML() {
		// print messages if any
		echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
	
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));

		echo new \view\components\PersonForm(array(
			"postAction" => \route\Route::get('d3')->generate(array(
				"controller" => "voditelj",
				"action" => "assignNewPerson"
			)),
			"submitButtonText" => "Zabilježi sudjelovanje",
			"showDelete" => false,
			"idPodrucja" => $this->idPodrucja,
			"showTip" => true,
			"showVrstaPodrucja" => true
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
	
	public function setIdPodrucja($idPodrucja) {
		$this->idPodrucja = $idPodrucja;
		return $this;
	}
}