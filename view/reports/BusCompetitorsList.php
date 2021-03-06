<?php

namespace view\reports;
use app\view\AbstractView;

class BusCompetitorsList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $elektrijade;

    protected function outputHTML() {
		// print messages if any
        echo new \view\components\ErrorMessage(array(
			"errorMessage" => $this->errorMessage
		));
	
		echo new \view\components\ResultMessage(array(
			"resultMessage" => $this->resultMessage
		));

		echo new forms\BusCompetitorsForm(array(
			"route" => \route\Route::get('d3')->generate(array(
			"controller" => "reportGenerator",
					"action" => "generateBusCompetitorsList"
			)),
			"submitButtonText" => "Generiraj",
			"elektrijade" => $this->elektrijade
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

    public function setElektrijade($elektrijade) {
	$this->elektrijade = $elektrijade;
	return $this;
    }
}