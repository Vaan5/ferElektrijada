<?php

namespace view\ozsn;
use app\view\AbstractView;

class AddContact extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $sponzori;
    private $tvrtke;
    private $mediji;
    
    protected function outputHTML() {
	
	// mediji na isti nacin kao i sponzori i tvrtke (drop down list)
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
    
    public function setMediji($mediji) {
	$this->mediji = $mediji;
	return $this;
    }

}