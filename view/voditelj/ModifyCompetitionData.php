<?php

namespace view\voditelj;
use app\view\AbstractView;

class ModifyCompetitionData extends AbstractView {
    private $errorMessage;
    private $resultMessage;
	private $elekPod;
	private $idPodrucja;
	private $timova;
	private $natjecatelja;
	
	protected function outputHTML() {
		// print messages if any
		echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
	
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
		
		echo new \view\components\ElekPodForm(array(
			"postAction" => \route\Route::get('d3')->generate(array(
				"controller" => "voditelj",
				"action" => "modifyCompetitionData"
			)),
			"submitButtonText" => "Promijeni",
			"elekPod" => $this->elekPod,
			"controller" => "voditelj",
			"action" => "downloadImage",
			"idPodrucja" => $this->idPodrucja,
			"timova" => $this->timova,
			"natjecatelja" => $this->natjecatelja
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
	
	public function setElekPod($elekPod) {
		$this->elekPod = $elekPod;
		return $this;
	}
	
	public function setIdPodrucja($idPodrucja) {
		$this->idPodrucja = $idPodrucja;
		return $this;
	}

	public function setTimova($timova) {
		$this->timova = $timova;
		return $this;
	}

	public function setNatjecatelja($natjecatelja) {
		$this->natjecatelja = $natjecatelja;
		return $this;
	}

}