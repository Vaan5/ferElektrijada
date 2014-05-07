<?php

namespace view\voditelj;
use app\view\AbstractView;

class MemberModification extends AbstractView {
    private $errorMessage;
    private $resultMessage;
	private $disabled;
	private $podrucjeSudjelovanja;
	private $sudjelovanje;
	private $smjerovi;
	private $zavodi;
	private $velicine;
	private $mjesta;
	private $godine;
	private $putovanje;
	private $mjesto;
	private $godina;
	private $zavod;
	private $velicina;
	private $smjer;
	private $osoba;

	protected function outputHTML() {
		// print messages if any
		echo new \view\components\ErrorMessage(array(
            "errorMessage" => $this->errorMessage
        ));
	
        echo new \view\components\ResultMessage(array(
            "resultMessage" => $this->resultMessage
        ));

		if (!$this->disabled) {
			echo new \view\components\PersonForm(array(
				"postAction" => \route\Route::get('d3')->generate(array(
					"controller" => "voditelj",
					"action" => "modifyContestant"
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
				"controllerCV" => "voditelj",
				"idPodrucja" => $this->podrucjeSudjelovanja->idPodrucja,
				"showTip" => true,
				"showVrstaPodrucja" => true,
				"podrucjeSudjelovanja" => $this->podrucjeSudjelovanja,
				"showPassword" => false
			));
		} else {
			echo new \view\components\ErrorMessage(array(
				"errorMessage" => "Istekao je rok za unos promjena!"
			));
		}
		
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

	public function setPodrucjeSudjelovanja($podrucjeSudjelovanja) {
		$this->podrucjeSudjelovanja = $podrucjeSudjelovanja;
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

	public function setPutovanje($putovanje) {
		$this->putovanje = $putovanje;
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
	
}