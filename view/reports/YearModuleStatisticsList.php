<?php

namespace view\reports;
use app\view\AbstractView;

class YearModuleStatisticsList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $elektrijade;
	

    protected function outputHTML() {
        echo new \view\components\ErrorMessage(array(
	    "errorMessage" => $this->errorMessage
	));
	
	echo new \view\components\ResultMessage(array(
	    "resultMessage" => $this->resultMessage
	));
	
	echo new forms\YearModuleStatisticsForm(array(
	    "route" => \route\Route::get('d3')->generate(array(
		"controller" => "reportGenerator",
                "action" => "generateYearModuleStatisticsList"
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