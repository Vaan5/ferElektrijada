<?php

namespace view\ozsn;
use app\view\AbstractView;

class ActiveSponzorModification extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $kategorije;
    private $promocije;
    private $sponzor;
    private $imasponzora;
    private $kategorija;
    private $promocija;
    
    protected function outputHTML() {
	// print messages if any
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
	
		// print out the form
		echo new \view\components\ActiveSponzorForm(array(
			"route" => \route\Route::get('d3')->generate(array(
				"controller" => 'ozsn',
				"action" => 'modifyActiveSponzor'
			)) . "?id=" . $this->sponzor->getPrimaryKey(),
			"submitButtonText" => "Spremi promjene",
			"kategorije" => $this->kategorije,
			"promocije" => $this->promocije,
			"sponzor" => $this->sponzor,
			"imasponzora" => $this->imasponzora,
			"kategorija" => $this->kategorija,
			"promocija" => $this->promocija
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
    
    public function setKategorije($kategorije) {
	$this->kategorije = $kategorije;
	return $this;
    }

    public function setPromocije($promocije) {
	$this->promocije = $promocije;
	return $this;
    }
    
    public function setSponzor($sponzor) {
	$this->sponzor = $sponzor;
	return $this;
    }

    public function setImasponzora($imasponzora) {
	$this->imasponzora = $imasponzora;
	return $this;
    }

    public function setKategorija($kategorija) {
	$this->kategorija = $kategorija;
	return $this;
    }

    public function setPromocija($promocija) {
	$this->promocija = $promocija;
	return $this;
    }

}