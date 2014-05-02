<?php

namespace view\ozsn;
use app\view\AbstractView;

class ModifyTeamLeader extends AbstractView {
    private $errorMessage;
    private $resultMessage;
	private $sudjelovanje;
	private $smjerovi;
	private $zavodi;
	private $velicine;
	private $mjesta;
	private $godine;
	private $mjesto;
	private $godina;
	private $zavod;
	private $velicina;
	private $smjer;
	private $osoba;
	private $idimaatribut;

	protected function outputHTML() {
		echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
	
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));

		echo new \view\components\PersonForm(array(
			"postAction" => \route\Route::get('d3')->generate(array(
				"controller" => "ozsn",
				"action" => "modifyTeamLeader"
			)),
			"submitButtonText" => "Ažuriraj",
			"osoba" => $this->osoba,
			"prikazSpola" => true,
			"showDelete" => false,
			"sudjelovanje" => $this->sudjelovanje,
			"showCV" => true,
			"radnaMjesta" => $this->mjesta,
			"velicine" => $this->velicine,
			"godine" => $this->godine,
			"smjerovi" => $this->smjerovi,
			"zavodi" => $this->zavodi,
			"velicina" => $this->velicina,
			"godina" => $this->godina,
			"smjer" => $this->smjer,
			"radnoMjesto" => $this->mjesto,
			"zavod" => $this->zavod,
			"showSubmit" => true,
			"showDropDown" => true,
			"controllerCV" => "ozsn",
			"idPodrucja" => $this->idimaatribut,
			"showTip" => true,
			"showVrstaPodrucja" => false,
			"showPassword" => false
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
	
	public function setDisabled($disabled) {
		$this->disabled = $disabled;
		return $this;
	}

	public function setSudjelovanje($sudjelovanje) {
		$this->sudjelovanje = $sudjelovanje;
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

	public function setVelicine($velicine) {
		$this->velicine = $velicine;
		return $this;
	}

	public function setMjesta($mjesta) {
		$this->mjesta = $mjesta;
		return $this;
	}

	public function setGodine($godine) {
		$this->godine = $godine;
		return $this;
	}

	public function setMjesto($mjesto) {
		$this->mjesto = $mjesto;
		return $this;
	}

	public function setGodina($godina) {
		$this->godina = $godina;
		return $this;
	}

	public function setZavod($zavod) {
		$this->zavod = $zavod;
		return $this;
	}

	public function setVelicina($velicina) {
		$this->velicina = $velicina;
		return $this;
	}

	public function setSmjer($smjer) {
		$this->smjer = $smjer;
		return $this;
	}
	
	public function setOsoba($osoba) {
		$this->osoba = $osoba;
		return $this;
	}
	
	public function setIdimaatribut($idimaatribut) {
		$this->idimaatribut = $idimaatribut;
		return $this;
	}

}