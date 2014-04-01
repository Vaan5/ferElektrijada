<?php

namespace view\administrator;
use app\view\AbstractView;

class AdminProfile extends AbstractView {
    /**
     *
     * @var string 
     */
    private $errorMessage;
    
    /**
     *
     * @var string 
     */
    private $resultMessage;
    
    /**
     *
     * @var object 
     */
    private $admin;
    
    protected function outputHTML() {
        
		// print out the form
        echo new \view\components\PersonForm(array(
            "postAction" => \route\Route::get('d3')->generate(array(
                "controller" => 'administrator',
                "action" => 'changeProfile'
            )),
            "submitButtonText" => "Spremi promjene"
        ));
        
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
    
    public function setAdmin($admin) {
        $this->admin = $admin;
        return $this;
    }

}
