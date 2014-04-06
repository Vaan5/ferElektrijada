<?php

namespace view\ozsn;
use app\view\AbstractView;

class AddContact extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $sponzori;
    private $tvrtke;
    
    protected function outputHTML() {
        echo new \view\components\KontaktOsobeForm();
    }
    
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    public function setResultMessage($resultMessage) {
        $this->resultMessage = $resultMessage;
        return $this;
    }
    
    public function setSponzori($sponzori) {
        $this->sponzori = $sponzori;
        return $this;
    }

    public function setTvrtke($tvrtke) {
        $this->tvrtke = $tvrtke;
        return $this;
    }




}