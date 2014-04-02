<?php

namespace view\ozsn;
use app\view\AbstractView;

class AtributList extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    
    protected function outputHTML() {
        /**
         * ovdje ce randy ispisati ;
         */
    }
    
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    public function setResultMessage($resultMessage) {
        $this->resultMessage = $resultMessage;
        return $this;
    }

}
