<?php

namespace view\administrator;
use app\view\AbstractView;
use view\components\LoginForm;

class AdminDoubleCheck extends AbstractView {
    
    /**
     *
     * @var string 
     */
    private $errorMessage;
    
    private $id;
    
    protected function outputHTML() {
		// Show error message if exists
		echo new \view\components\ErrorMessage(array(
			"errorMessage" => $this->errorMessage
		));
		
		echo new LoginForm(array(
			"showUserName" => false,
			"actionRoute" => \route\Route::get('d3')->generate(array(
				"controller" => "administrator",
				"action" => "doubleCheckAdmin"
			)),
			"id" => $this->id,
			"submitButtonText" => "Potvrdi"
		)); 
    }
    
    /**
     * 
     * @param string $errorMessage
     * @return \view\Login
     */
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }
    
    public function setId($id) {
        $this->id = $id;
        return $this;
    }


}