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
        
        /**
         * ISPISI obrazac koji ces popuniti s podacima o adminu (ne treba obrazac sadrzavati zivotopis)
         * koristi onaj PersonFormModel
         */
        
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
