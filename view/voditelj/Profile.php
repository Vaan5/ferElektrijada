<?php

namespace view\voditelj;
use app\view\AbstractView;

class Profile extends AbstractView {
    private $errorMessage;
    private $resultMessage;
    private $osoba;
    private $radnaMjesta;
    private $velicine;
    private $godine;
    private $smjerovi;
    private $zavodi;
    private $velicina;
    private $godina;
    private $smjer;
    private $radnoMjesto;
    private $zavod;
    private $sudjelovanje;
    private $disabled;

    protected function outputHTML() {
	
        echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
		if ($this->disabled)
			echo new \view\components\ResultMessage(array(
			"resultMessage" => "Protekao je rok za unos promjena!"
			));
	
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));
        
        echo new \view\components\PersonForm(array(
            "postAction" => \route\Route::get('d3')->generate(array(
                "controller" => 'voditelj',
                "action" => 'modifyProfile'
            )),
            "submitButtonText" => "Spremi promjene",
            "osoba" => $this->osoba,
			"radnaMjesta" => $this->radnaMjesta,
			"velicine" => $this->velicine,
			"godine" => $this->godine,
			"smjerovi" => $this->smjerovi,
			"zavodi" => $this->zavodi,
			"velicina" => $this->velicina,
			"godina" => $this->godina,
			"smjer" => $this->smjer,
			"radnoMjesto" => $this->radnoMjesto,
			"zavod" => $this->zavod,
			"sudjelovanje" => $this->sudjelovanje,
			"showDelete" => false,
			"showCV" => true,
			"showSubmit" => !$this->disabled,
			"showDropDown" => true,
			"controllerCV" => "sudionik"
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

    public function setOsoba($osoba) {
		$this->osoba = $osoba;
		return $this;
    }

    public function setRadnaMjesta($radnaMjesta) {
		$this->radnaMjesta = $radnaMjesta;
		return $this;
    }

    public function setVelicine($velicine) {
		$this->velicine = $velicine;
		return $this;
    }

    public function setGodine($godine) {
		$this->godine = $godine;
		return $this;
    }

    public function setSmjerovi($smjerovi) {
		$this->smjerovi = $smjerovi;
		return $this;
    }

    public function setZavodi($zavodi) {
		$this->zavodi = $zavodi;
		return $this;
    }

    public function setVelicina($velicina) {
		$this->velicina = $velicina;
		return $this;
    }

    public function setGodina($godina) {
		$this->godina = $godina;
		return $this;
    }

    public function setSmjer($smjer) {
		$this->smjer = $smjer;
		return $this;
    }

    public function setRadnoMjesto($radnoMjesto) {
		$this->radnoMjesto = $radnoMjesto;
		return $this;
    }

    public function setZavod($zavod) {
		$this->zavod = $zavod;
		return $this;
    }
    
    public function setSudjelovanje($sudjelovanje) {
		$this->sudjelovanje = $sudjelovanje;
		return $this;
    }
    
    public function setDisabled($disabled) {
		$this->disabled = $disabled;
		return $this;
    }

}