<?php

namespace view\reports;
use app\view\AbstractView;

class DisciplineCompetitorList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $podrucja;
    private $elektrijade;

    protected function outputHTML() {
        echo new \view\components\ErrorMessage(array(
	    "errorMessage" => $this->errorMessage
	));
	
	echo new \view\components\ResultMessage(array(
	    "resultMessage" => $this->resultMessage
	));
	
	echo new forms\DisciplineCompetitorForm(array(
	    "route" => \route\Route::get('d3')->generate(array(
		"controller" => "reportGenerator",
                "action" => "generateDisciplineList"
	    )),
	    "submitButtonText" => "Generiraj",
	    "podrucja" => $this->podrucja,
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
    
    public function setPodrucja($podrucja) {
	$this->podrucja = $podrucja;
	return $this;
    }

    public function setElektrijade($elektrijade) {
	$this->elektrijade = $elektrijade;
	return $this;
    }
}