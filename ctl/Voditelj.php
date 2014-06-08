<?php

namespace ctl;
use app\controller\Controller;
use \PDOException;

class Voditelj implements Controller {
    
    private $errorMessage;
    private $resultMessage;
    private $changesDisabled;
    
    private function checkRole() {
		// you must be an active team leader
		if (\model\DBOsoba::isLoggedIn() && (session("vrsta") === "SV" ||  session("vrsta") === "OV"))
			return;
		if (\model\DBOsoba::isLoggedIn() && \model\DBOsoba::getUserRole() === 'A' ) {
			return;
		}

		preusmjeri(\route\Route::get('d1')->generate() . "?msg=accessDenied");
    }
    
	private function idCheck($akcija) {
		$validator = new \model\formModel\IdValidationModel(array("id" => get("id")));
		$pov = $validator->validate();
		if ($pov !== true)
			$this->createMessage($validator->decypherErrors($pov), "d3", "voditelj", $akcija);
    }
	
	private function getParamCheck($id, $akcija) {
		$validator = new \model\formModel\IdValidationModel(array("id" => get($id)));
		$pov = $validator->validate();
		if ($pov !== true)
			$this->createMessage($validator->decypherErrors($pov), "d3", "voditelj", $akcija);
    }
	
    private function createMessage($message, $type = 'd1', $controller = null, $action = null) {
		$handler = new \model\ExceptionHandlerModel(new \PDOException(), (string)$message);
		$_SESSION["exception"] = serialize($handler);
		if ($type === 'd3') {
			preusmjeri(\route\Route::get('d3')->generate(array(
				"controller" => $controller,
				"action" => $action
				)) . "?msg=excep");
		} else {
			 preusmjeri(\route\Route::get('d1')->generate() . "?msg=excep");
		}
    }
	
    private function changesAllowed() {
		if (\model\DBOsoba::getUserRole() === 'A') {
			$this->changesDisabled = false;
			return;
		}
		try {
			$elektrijada = new \model\DBElektrijada();
			$idElektrijade = $elektrijada->getCurrentElektrijadaId();
			$elektrijada->load($idElektrijade);
			$rokZaZnanje = strtotime($elektrijada->rokZaZnanje);
			$rokZaSport = strtotime($elektrijada->rokZaSport);
			$podrucje = new \model\DBPodrucje();
			$idZnanja = $podrucje->getKnowledgeId();
			$idSporta = $podrucje->getSportId();

			$podrucja = $podrucje->loadDisciplines(session("podrucja"));

			$checkZnanje = false;
			$checkSport = false;
			if (count($podrucja)) {
				foreach($podrucja as $p) {
					if ($p->getPrimaryKey() == $idZnanja || $p->idNadredjenog == $idZnanja)
						$checkZnanje = true;
					if ($p->getPrimaryKey() == $idSporta || $p->idNadredjenog == $idSporta)
						$checkSport = true;
					if ($checkSport && $checkZnanje)
						break;
				}
			} else {
				throw new \app\model\NotFoundException();
			}

			// now check if the dates are right
			$currentTime = time();
			if ($checkZnanje) {
				if ($currentTime > $rokZaZnanje) {
					$this->changesDisabled = true;
				}
			}

			if ($checkSport) {
				if ($currentTime > $rokZaSport) {
					$this->changesDisabled = true;
				}
			}
			$this->changesDisabled = false;
		} catch (\app\model\NotFoundException $e) {
			$this->createMessage("Problem prilikom provjere dozvoljenosti promjena!");
		} catch (\PDOException $e) {
			$handler = new \model\ExceptionHandlerModel($e);
			$this->createMessage($handler);
		}
    }
    
    private function checkMessages() {
        switch(get("msg")) {
			case 'succA':
				$this->resultMessage = "Uspješno zabilježeno sudjelovanje u natjecanju!";
				break;
			case 'succD':
				$this->resultMessage = "Uspješno uklonjen natecatelj iz Vašeg tima!";
				break;
			case 'succM':
				$this->resultMessage = "Uspješno ažurirani podaci o natecatelju!";
				break;
			case 'succR':
				$this->resultMessage = "Uspješno ažurirani rezultati natjecatelja!";
				break;
			case 'fail':
				$this->errorMessage = "Dogodila se greška! Pokušajte ponovno!";
				break;
			case 'succC':
				$this->resultMessage = "Uspješno ažurirani podaci o disciplini!";
				break;
            case 'excep':
                if(isset($_SESSION['exception'])) {
                    $e = unserialize($_SESSION['exception']);
                    unset($_SESSION['exception']);
                    $this->errorMessage = $e;
                }
            default:
                break;
        }
    }
	
	private function checkAuthority($idPodrucja) {
		if (session("podrucja") !== false && count(session("podrucja"))) {
			foreach (session("podrucja") as $p) {
				if ($p->idPodrucja == $idPodrucja)
					return;
			}
			$this->createMessage("Nemate odgovarajuće ovlasti za traženu disciplinu!");
		} else {
			$this->createMessage("Nemate odgovarajuće ovlasti za traženu disciplinu!");
		}
	}
    
    private function generateFile($type, $array) {
		$reportGen = new \model\reports\ReportModel();
		$tmp = sys_get_temp_dir();
		$path = $tmp . "/" . date("Y_m_d_H_i_s") . "_" . session("auth");
		switch ($type) {
			case 'pdf':
				$pdf = $reportGen->generatePdf($array);
				$path .= ".pdf";
				$pdf->Output($path);
				break;
			case 'xls':
			case 'xlsx':
				$path = $reportGen->generateExcel($array, $type);
				break;
			default:
				preusmjeri(\route\Route::get('d1')->generate() . "?msg=typeconf");
				break;
		}
		return $path;
    }
    
	public function displayPodrucja() {
		$this->checkRole();
		$this->checkMessages();
		$this->changesAllowed();
		
		$podrucje = new \model\DBPodrucje();
		$podrucja = null;
		
		try {
			$podrucja = $podrucje->loadDisciplines(session("podrucja"));
		} catch (\app\model\NotFoundException $e) {
			$this->createMessage("Problem prilikom dohvata podataka o disciplinama!");
		} catch (\PDOException $e) {
			$handler = new \model\ExceptionHandlerModel($e);
			$this->createMessage($handler);
		}
		
		if (get("type")) {
			$pomPolje = array("Naziv Područja");
			$array = array();
			$array[] = $pomPolje;
			
			if ($podrucja !== null && count($podrucja)) {
				foreach ($podrucja as $v) {
					$array[] = array($v->nazivPodrucja);
				}
			}
			
			$path = $this->generateFile(get("type"), $array);
			
			echo new \view\ShowFile(array(
				"path" => $path,
				"type" => get("type")
			));
		}
		
		echo new \view\Main(array(
			"title" => "Discipline",
			"body" => new \view\voditelj\MyDisciplines(array(
				"errorMessage" => $this->errorMessage,
				"resultMessage" => $this->resultMessage,
				"podrucja" => $podrucja,
				"disabled" => $this->changesDisabled
			))
		));
	}
	
	public function assignNewPerson() {
		$this->checkRole();
		$this->checkMessages();
		$this->changesAllowed();
		
		if ($this->changesDisabled)
			$this->createMessage ("Istekao rok za unos promjena!", 'd3', 'voditelj', 'displayPodrucja');
		
		if (postEmpty()) {
			// check if you got the Discipline id
			$this->idCheck('displayPodrucja');
			$this->checkAuthority(get("id"));
			$idPodrucja = get("id");
		} else {
			// proccess query
			$idPodrucja = post("idPodrucja");
			$this->checkAuthority($idPodrucja);
			
			try {
				$validacija = new \model\PersonFormModel(array('password' => post('password'),
                                        'ferId' => post('ferId'),
                                        'ime' => post('ime'), 
                                        'prezime' => post('prezime'), 
                                        'mail' => post('mail'), 
                                        'brojMob' => post('brojMob'), 
                                        'JMBAG' => post('JMBAG'),
                                        'spol' => post('spol'), 
                                        'datRod' => post('datRod'), 
                                        'brOsobne' => post('brOsobne'), 
                                        'brPutovnice' => post('brPutovnice'), 
                                        'osobnaVrijediDo' => post('osobnaVrijediDo'), 
                                        'putovnicaVrijediDo' => post('putovnicaVrijediDo'),
                                        'MBG' => post('MBG'),
                                        'OIB' => post('OIB')));
				$pov = $validacija->validate();
				if($pov !== true) {
					$this->errorMessage = $validacija->decypherErrors($pov);
				} else {
					// everything is ok i add the person
					$osoba = new \model\DBOsoba();
					$elektrijada = new \model\DBElektrijada();
					$idElektrijade = $elektrijada->getCurrentElektrijadaId();
					
					$osoba->addNewPerson(post('ime', null), post('prezime', null), post('mail', null), post('brojMob', null), post('ferId'), post('password'), 
						post('JMBAG', null), post('spol', null), post('datRod', null), post('brOsobne', null), post('brPutovnice', null), post('osobnaVrijediDo', null),
						post('putovnicaVrijediDo', null), 'S', NULL, post('MBG', null), post('OIB', null), session("auth"), post("aktivanDokument", NULL));
					
					// added successfully
					
					// now assign them to the current Elektrijada
					$sudjelovanje = new \model\DBSudjelovanje();
					$sudjelovanje->loadByContestant($osoba->getPrimaryKey(), $idElektrijade);
					if ($sudjelovanje->getPrimaryKey() === null)
						$sudjelovanje->addRow($osoba->getPrimaryKey(), $idElektrijade, post("tip", "S"), NULL, NULL, NULL, NULL, NULL, NULL);
					
					$podrucjeSudjelovanja = new \model\DBPodrucjeSudjelovanja();
					$podrucjeSudjelovanja->addRow(post("idPodrucja"), $sudjelovanje->getPrimaryKey(), NULL, post("vrstaPodrucja", NULL), NULL, NULL);
					
					// added successfully
					preusmjeri(\route\Route::get("d3")->generate(array(
						"controller" => "voditelj",
						"action" => "displayPodrucja"
					)) . "?msg=succA");
				}
			} catch (\app\model\NotFoundException $e) {
				$this->errorMessage = "Nepoznati identifikator!";
			} catch (\PDOException $e) {
				$handler = new \model\ExceptionHandlerModel($e);
				$this->errorMessage = $handler;
			} 
		}
		
		echo new \view\Main(array(
			"title" => "Novi Natjecatelj",
			"body" => new \view\voditelj\AssignNewPerson(array(
				"errorMessage" => $this->errorMessage,
				"resultMessage" => $this->resultMessage,
				"idPodrucja" => $idPodrucja
			)),
			"script" => new \view\scripts\PersonFormJs()
		));
	}
	
	public function assignExistingPerson() {
		$this->checkRole();
		$this->checkMessages();
		$this->changesAllowed();
		
		$osobe = null;
		if (postEmpty()) {
			$this->idCheck("displayPodrucja");
			$osoba = new \model\DBOsoba();
			$idPodrucja = get("id");
			
			$osobe = $osoba->getAllPersons();
		} else {
			// proccess query
			$idPodrucja = post("idPodrucja");

			try {
				$elektrijada = new \model\DBElektrijada();
				$idElektrijade = $elektrijada->getCurrentElektrijadaId();
				$sudjelovanje = new \model\DBSudjelovanje();

				if (count(post("osobe"))) {
					// add them to the competition
					foreach (post("osobe") as $o) {
						$sudjelovanje->{$sudjelovanje->getPrimaryKeyColumn()} = null;
						$sudjelovanje->loadByContestant($o, $idElektrijade);
						if ($sudjelovanje->getPrimaryKey() === null)
							$sudjelovanje->addRow($o, $idElektrijade, post("tip", "S"), NULL, NULL, NULL, NULL, NULL, NULL);
						
						$podrucjeSudjelovanja = new \model\DBPodrucjeSudjelovanja();
						if (!$podrucjeSudjelovanja->alreadyExists(post("idPodrucja"), $sudjelovanje->getPrimaryKey(), post("vrstaPodrucja", NULL))) {
							$podrucjeSudjelovanja->addRow(post("idPodrucja"), $sudjelovanje->getPrimaryKey(), NULL, post("vrstaPodrucja", NULL), NULL, NULL);
						}
					}
					
					preusmjeri(\route\Route::get("d3")->generate(array(
						"controller" => "voditelj",
						"action" => "displayTeam"
					)) . "?msg=succA&id=" . post("idPodrucja"));
				} else {
					preusmjeri(\route\Route::get("d3")->generate(array(
						"controller" => "voditelj",
						"action" => "assignExistingPerson"
					)) . "?id=" . post("idPodrucja"));
				}	
			} catch (\app\model\NotFoundException $e) {
				preusmjeri(\route\Route::get("d3")->generate(array(
						"controller" => "voditelj",
						"action" => "assignExistingPerson"
					)) . "?id=" . post("idPodrucja") . "&msg=fail");
			} catch (\PDOException $e) {
				preusmjeri(\route\Route::get("d3")->generate(array(
						"controller" => "voditelj",
						"action" => "assignExistingPerson"
					)) . "?id=" . post("idPodrucja") . "&msg=fail");
			}	
		}
		
		echo new \view\Main(array(
			"title" => "Dodavanje Natjecatelja",
			"body" => new \view\voditelj\AssignExistingPerson(array(
				"errorMessage" => $this->errorMessage,
				"resultMessage" => $this->resultMessage,
				"idPodrucja" => $idPodrucja,
				"disabled" => $this->changesDisabled,
				"osobe" => $osobe
			))
		));		
	}
	
	public function displayTeam() {
		$this->checkRole();
		$this->checkMessages();
		$this->changesAllowed();
		
		$this->idCheck("displayPodrucja");
		
		$takmicari = null;
		try {
			$podrucjeSudjelovanja = new \model\DBPodrucjeSudjelovanja();
			$elektrijada = new \model\DBElektrijada();
			$idElektrijade = $elektrijada->getCurrentElektrijadaId();
			
			$takmicari = $podrucjeSudjelovanja->getPaticipants(get("id"), $idElektrijade);
		} catch (\app\model\NotFoundException $e) {
			$this->createMessage("Nepostojeći identifikator!", "d3", "voditelj", "displayPodrucja");
		} catch (\PDOException $e) {
			$handler = new \model\ExceptionHandlerModel($e);
			$this->createMessage($handler, "d3", "voditelj", "displayPodrucja");
		}
		
		if (get("type")) {
			// generate file
			$pomPolje = array("Ime", "Prezime", "Email", "JMBAG", "OIB", "Korisničko ime",
				"Osobna iskaznica", "Putovnica", "Rezultat", "Ukupno Sudionika", "Vrsta Natjecanja");
			$array = array();
			$array[] = $pomPolje;
			
			if ($takmicari !== null && count($takmicari)) {
				foreach ($takmicari as $v) {
					$array[] = array($v->ime, $v->prezime, $v->mail, $v->JMBAG, $v->OIB, $v->ferId,
						$v->brOsobne, $v->brPutovnice, $v->rezultatPojedinacni, $v->ukupanBrojSudionika, $v->vrstaPodrucja == '1' ? 'Timsko' : "Pojedinačno");
				}
			}
			
			$path = $this->generateFile(get("type"), $array);
			
			echo new \view\ShowFile(array(
				"path" => $path,
				"type" => get("type")
			));
		}

		echo new \view\Main(array(
			"title" => "Članovi Tima",
			"body" => new \view\voditelj\TeamMembers(array(
				"errorMessage" => $this->errorMessage,
				"resultMessage" => $this->resultMessage,
				"takmicari" => $takmicari,
				"idPodrucja" => get("id"),
				"disabled" => $this->changesDisabled
			))
		));
	}
	
	public function modifyContestant() {
		$this->checkRole();
		$this->checkMessages();
		$this->changesAllowed();
		
		$osoba = new \model\DBOsoba();
		$podrucjeSudjelovanja = new \model\DBPodrucjeSudjelovanja();
		$sudjelovanje = new \model\DBSudjelovanje();
		$velicina = new \model\DBVelMajice();
		$smjer = new \model\DBSmjer();
		$godina = new \model\DBGodStud();
		$mjesto = new \model\DBRadnoMjesto();
		$zavod = new \model\DBZavod();
		$putovanje = new \model\DBPutovanje();
		
		$smjerovi = null;
		$zavodi = null;
		$velicine = null;
		$godine = null;
		$mjesta = null;
		
		if (postEmpty()) {
			// check if parameters are alright
			$this->getParamCheck("idP", "displayPodrucja");
			$this->getParamCheck("idS", "displayPodrucja");
			$this->getParamCheck("idO", "displayPodrucja");

			// get display data
			try {
				$osoba->load(get("idO"));
				$podrucjeSudjelovanja->load(get("idP"));
				$this->checkAuthority($podrucjeSudjelovanja->idPodrucja);
				$sudjelovanje->load(get("idS"));
				
				// get drop down data
				$godine = $godina->getAllGodStud();
				$zavodi = $zavod->getAllZavod();
				$smjerovi = $smjer->getAllSmjer();
				$velicine = $velicina->getAllVelicina();
				$mjesta = $mjesto->getAllRadnoMjesto();
				$putovanje->loadIfExists($sudjelovanje->idPutovanja);
				if ($sudjelovanje->isStaff()) {
					$mjesto->loadIfExists($sudjelovanje->idRadnogMjesta);
					$zavod->loadIfExists($sudjelovanje->idZavoda);
					$godina->loadIfExists($sudjelovanje->idGodStud);
					$smjerovi = null;
				} else if ($sudjelovanje->isStudent()) {
					$godina->loadIfExists($sudjelovanje->idGodStud);
					$smjer->loadIfExists($sudjelovanje->idSmjera);
					$mjesta = null;
					$zavodi = null;
				}
				$velicina->loadIfExists($sudjelovanje->idVelicine);
			} catch (\app\model\NotFoundException $e) {
				$this->createMessage("Nepostojeći zapis!", "d3", "voditelj", "displayPodrucja");
			} catch (\PDOException $e) {
				$handler = new \model\ExceptionHandlerModel($e);
				$this->createMessage($handler, "d3", "voditelj", "displayPodrucja");
			}
		} else {
			// process query
			try {
				$validacija = new \model\PersonFormModel(array('password' => post('password'),
								'ferId' => post('ferId'),
								'ime' => post('ime'), 
								'prezime' => post('prezime'), 
								'mail' => post('mail'), 
								'brojMob' => post('brojMob'), 
								'JMBAG' => post('JMBAG'),
								'spol' => post('spol'), 
								'datRod' => post('datRod'), 
								'brOsobne' => post('brOsobne'), 
								'brPutovnice' => post('brPutovnice'), 
								'osobnaVrijediDo' => post('osobnaVrijediDo'), 
								'putovnicaVrijediDo' => post('putovnicaVrijediDo'),
								'MBG' => post('MBG'),
								'OIB' => post('OIB')));
				$pravila = $validacija->getRules();
				$pravila['password'] = array('password');
				$validacija->setRules($pravila);
				$pov = $validacija->validate();
				if($pov !== true) {
					$handler = new \model\ExceptionHandlerModel(new \PDOException(), $validacija->decypherErrors($pov));
					$_SESSION["exception"] = serialize($handler);
					preusmjeri(\route\Route::get('d3')->generate(array(
						"controller" => "voditelj",
						"action" => "modifyContestant"
					)) . "?msg=excep&idP=" . post("idPodrucjeSudjelovanja") . "&idO=" . post("idOsobe") . "&idS=" . post("idSudjelovanje"));
				}
				
				// everything's okay lets do the modifying
				// check competition data
				$podrucjeSudjelovanja->load(post("idPodrucjeSudjelovanja"));
				$this->checkAuthority($podrucjeSudjelovanja->idPodrucja);
				$podrucjeSudjelovanja->modifyRow(post("idPodrucjeSudjelovanja"), FALSE, FALSE, FALSE, post("vrstaPodrucja", "0"), FALSE, FALSE);
				
				$osoba->modifyPerson(post("idOsobe"), post("ime", NULL), post("prezime", NULL), post("mail", NULL), 
							post("brojMob", NULL), post("ferId", NULL), NULL, post("JMBAG", NULL),
							post("spol", NULL), post("datRod", NULL), post("brOsobne", NULL), post("brPutovnice", NULL),
							post("osobnaVrijediDo", NULL), post("putovnicaVrijediDo", NULL), NULL, post("MBG", NULL), 
							post("OIB", NULL), post("aktivanDokument", NULL));
				
				$osoba->load(post("idOsobe"));
				// now add the competition data
				$sudjelovanje = new \model\DBSudjelovanje();
				$sudjelovanje->load(post("idSudjelovanja"));
				$sudjelovanje->tip = post("tip", "S");
				$sudjelovanje->save();

				if ($sudjelovanje->isStaff()) {
					$sudjelovanje->modifyRow(post("idSudjelovanja"), FALSE, FALSE, FALSE, post("idVelicine", NULL),
							post("idGodStud", NULL), NULL, post("idRadnogMjesta", NULL), post("idZavoda", NULL), FALSE);
				} else {
					$sudjelovanje->modifyRow(post("idSudjelovanja"), FALSE, FALSE, FALSE, post("idVelicine", NULL),
							post("idGodStud", NULL), post("idSmjera", NULL), NULL, NULL, FALSE);
				}
				
				// check CV
				if (files("tmp_name", "datoteka") !== false) {
					// security check
					if(files("size", "datoteka") > 1024 * 1024) {
						$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Datoteka je prevelika! Maksimalna dozvoljena veličina je 1 MB!");
						$_SESSION["exception"] = serialize($handler);
						preusmjeri(\route\Route::get('d3')->generate(array(
							"controller" => "voditelj",
							"action" => "modifyContestant"
						)) . "?msg=excep&idP=" . post("idPodrucjeSudjelovanja") . "&idO=" . post("idOsobe") . "&idS=" . post("idSudjelovanje"));
					}
					if(!is_uploaded_file(files("tmp_name", "datoteka"))) {
						$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Morate poslati datoteku!");
						$_SESSION["exception"] = serialize($handler);
						preusmjeri(\route\Route::get('d3')->generate(array(
							"controller" => "voditelj",
							"action" => "modifyContestant"
						)) . "?msg=excep&idP=" . post("idPodrucjeSudjelovanja") . "&idO=" . post("idOsobe") . "&idS=" . post("idSudjelovanje"));
					}
					// check if it is a pdf
					if(function_exists('finfo_file')) {
						$finfo = \finfo_open(FILEINFO_MIME_TYPE);
						$mime = finfo_file($finfo, files("tmp_name", "datoteka"));
					} else {
						$mime = \mime_content_type(files("tmp_name", "datoteka"));
					}
					if($mime != 'application/pdf') {
						$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Životopis možete poslati samo u pdf formatu!");
						$_SESSION["exception"] = serialize($handler);
						preusmjeri(\route\Route::get('d3')->generate(array(
							"controller" => "voditelj",
							"action" => "modifyContestant"
						)) . "?msg=excep&idP=" . post("idPodrucjeSudjelovanja") . "&idO=" . post("idOsobe") . "&idS=" . post("idSudjelovanje"));
					}

					// adding the path and the file
					$putanja = "./zivotopisi/" . date("Y_m_d_H_i_s") . "_" . post("idOsobe") . ".pdf";
					if (move_uploaded_file(files("tmp_name", "datoteka"), $putanja)) {
						// if there was already a CV on the server
						// remove it
						if ($osoba->zivotopis !== NULL) {
							$p = unlink($osoba->zivotopis);
							if ($p === false) {
								$e = new \PDOException();
								$e->errorInfo[0] = '02000';
								$e->errorInfo[1] = 1604;
								$e->errorInfo[2] = "Greška prilikom brisanja životopisa!";
								throw $e;
							}
						}

						// add path to db
						$osoba->addCV(post("idOsobe"), $putanja);		
					} else {
						$e = new \PDOException();
						$e->errorInfo[0] = '02000';
						$e->errorInfo[1] = 1604;
						$e->errorInfo[2] = "Dogodio se problem sa spremanjem životopisa! Ostali podaci su ažurirani!";
						throw $e;
					}
				} else {
					// check if he wants to delete the old CV
					if (post("delete") !== false && $osoba->zivotopis != NULL) {
						$p = unlink($osoba->zivotopis);
						if ($p === false) {
							$e = new \PDOException();
							$e->errorInfo[0] = '02000';
							$e->errorInfo[1] = 1604;
							$e->errorInfo[2] = "Greška prilikom brisanja životopisa!";
							$osoba->addCV(post("idOsobe"), NULL);
							throw $e;
						}
						$osoba->addCV(post("idOsobe"), NULL);	// delete path from db
					}
				}
				
				// success -> redirect
				preusmjeri(\route\Route::get("d3")->generate(array(
						"controller" => "voditelj",
						"action" => "displayTeam"
					)) . "?msg=succM&id=" . post("idPodrucja"));
			} catch (\app\model\NotFoundException $e) {
				$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Nepoznati identifikator!");
				$_SESSION["exception"] = serialize($handler);
				preusmjeri(\route\Route::get('d3')->generate(array(
					"controller" => "voditelj",
					"action" => "modifyContestant"
				)) . "?msg=excep&idP=" . post("idPodrucjeSudjelovanja") . "&idO=" . post("idOsobe") . "&idS=" . post("idSudjelovanje"));
			} catch (\PDOException $e) {
				$handler = new \model\ExceptionHandlerModel($e);
				$_SESSION["exception"] = serialize($handler);
				preusmjeri(\route\Route::get('d3')->generate(array(
					"controller" => "voditelj",
					"action" => "modifyContestant"
				)) . "?msg=excep&idP=" . post("idPodrucjeSudjelovanja") . "&idO=" . post("idOsobe") . "&idS=" . post("idSudjelovanje"));
			}
		}
		
		echo new \view\Main(array(
			"title" => "Ažuriranje",
			"body" => new \view\voditelj\MemberModification(array(
				"errorMessage" => $this->errorMessage,
				"resultMessage" => $this->resultMessage,
				"osoba" => $osoba,
				"disabled" => $this->changesDisabled,
				"podrucjeSudjelovanja" => $podrucjeSudjelovanja,
				"sudjelovanje" => $sudjelovanje,
				"smjerovi" => $smjerovi,
				"zavodi" => $zavodi,
				"velicine" => $velicine,
				"mjesta" => $mjesta,
				"godine" => $godine,
				"putovanje" => $putovanje,
				"velicina" => $velicina,
				"godina" => $godina,
				"mjesto" => $mjesto,
				"zavod" => $zavod,
				"smjer" => $smjer
			))
		));
	}
	
	public function deleteContestant() {
		$this->checkRole();
		$this->checkMessages();
		$this->changesAllowed();
		
		if ($this->changesDisabled)
			$this->createMessage ("Istekao rok za unos promjena!", 'd3', 'voditelj', 'displayPodrucja');
		
		if (get("idP") !== false && get("idS") !== false) {
			$this->getParamCheck("idP", "displayPodrucja");
			$this->getParamCheck("idS", "displayPodrucja");
			
			// lets remove him
			try {
				$podrucjeSudjelovanja = new \model\DBPodrucjeSudjelovanja();
				$sudjelovanje = new \model\DBSudjelovanje();
				
				$podrucjeSudjelovanja->load(get("idP"));
				$this->checkAuthority($podrucjeSudjelovanja->idPodrucja);
				
				$podrucje = $podrucjeSudjelovanja->idPodrucja;
				
				// everything's okay let's continue
				$podrucjeSudjelovanja->delete();
				
				if (!$podrucjeSudjelovanja->isParticipating(get("idS"))) {
					$sudjelovanje->load(get("idS"));
					$sudjelovanje->delete();
				}
				
				// done
				preusmjeri(\route\Route::get("d3")->generate(array(
					"controller" => "voditelj",
					"action" => "displayTeam"
				)) . "?msg=succD&id=" . $podrucje);
				
			} catch (\app\model\NotFoundException $e) {
				$this->createMessage("Dogodila se greška prilikom brisanja!", "d3", "voditelj", "displayPodrucja");
			} catch (\PDOException $e) {
				$handler = new \model\ExceptionHandlerModel($e);
				$this->createMessage($handler);
			}			
		} else {
			$this->createMessage("Nepoznati parametri brisanja!", "d3", "voditelj", "displayPodrucja");
		}
	}
	
	public function modifyCompetitionData() {
		$this->checkRole();
		$this->checkMessages();
		
		$elekPod = new \model\DBElekPodrucje();
		$podrucje = new \model\DBPodrucje();
		$podSud = new \model\DBPodrucjeSudjelovanja();
		$natjecatelja = null;
		$timova = null;

		$naziv = "";
		if (postEmpty()) {
			try {
				$this->idCheck("displayPodrucja");
				$this->checkAuthority(get("id"));

				$elektrijada = new \model\DBElektrijada();
				$idElektrijade = $elektrijada->getCurrentElektrijadaId();
				$elekPod->loadByDiscipline(get("id"), $idElektrijade);
				
				$podrucje->load(get("id"));
				$idPodrucja = get("id");
				$naziv = $podrucje->nazivPodrucja;
				$natjecatelja = $podSud->getNumberOfContestants($idPodrucja, $idElektrijade);
				$timova = $podSud->getNumberOfTeams($idPodrucja, $idElektrijade);
			} catch (\app\model\NotFoundException $e) {
				$this->createMessage("Nepoznati identifikator!", "d3", "voditelj", "displayPodrucja");
			} catch (\PDOException $e) {
				$handler = new \model\ExceptionHandlerModel($e);
				$this->createMessage($handler, "d3", "voditelj", "displayPodrucja");
			}
		} else {
			try {
				$podrucje->load(post("idPodrucja"));
				$idPodrucja = post("idPodrucja");
				$naziv = $podrucje->nazivPodrucja;
				$validacija = new \model\formModel\ElekPodFormModel(array('rezultatGrupni' => post('rezultatGrupni'),
											'ukupanBrojEkipa' => post('ukupanBrojEkipa'),
											'ukupanBrojTakmicara' => post('ukupanBrojTakmicara'),
											'ukupanBrojTimova' => post('ukupanBrojTimova')
											));
				$pov = $validacija->validate();
				if($pov !== true) {
					$this->errorMessage = $validacija->decypherErrors($pov);
				} else if (post("rezultatGrupni", "0") > post("ukupanBrojEkipa", "0")) {
					$this->errorMessage = "Rezultat ne može biti veći od broja fakulteta!";
				} else {	
					// now add data
					if (post("idElekPodrucje") === false) {
						$elektrijada = new \model\DBElektrijada();
						$idElektrijade = $elektrijada->getCurrentElektrijadaId();
						$elekPod->addRow(post("idPodrucja"),  post("rezultatGrupni", NULL), NULL, $idElektrijade, post("ukupanBrojEkipa", NULL));
					} else {
						$elekPod->modifyRow(post("idElekPodrucje"), FALSE, post("rezultatGrupni"), FALSE, FALSE, post("ukupanBrojEkipa", NULL));
					}
					
					// azuriraj broj timova i broj natjecatelja
					$elektrijada = new \model\DBElektrijada();
					$idElektrijade = $elektrijada->getCurrentElektrijadaId();
					if ($podrucje && $podrucje->idNadredjenog !== null && !$podSud->updateNumberOfContestants($elekPod->idPodrucja, $idElektrijade, 0, post("ukupanBrojTakmicara", 0))) {
						$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Da biste unijeli broj natjecatelja, morate najprije registrirati barem jednog natjecatelja!");
						$_SESSION["exception"] = serialize($handler);
						preusmjeri(\route\Route::get('d3')->generate(array(
							"controller" => "voditelj",
							"action" => "modifyCompetitionData"
						)) . "?msg=excep&id=" . post("idPodrucja"));
					}
					if ($podrucje && $podrucje->idNadredjenog !== null && !$podSud->updateNumberOfContestants($elekPod->idPodrucja, $idElektrijade, 1, post("ukupanBrojTimova", 0))) {
						$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Da biste unijeli broj timova, morate najprije registrirati barem jednog člana tima!");
						$_SESSION["exception"] = serialize($handler);
						preusmjeri(\route\Route::get('d3')->generate(array(
							"controller" => "voditelj",
							"action" => "modifyCompetitionData"
						)) . "?msg=excep&id=" . post("idPodrucja"));
					}
					
					// process image
					if (files("tmp_name", "datoteka") !== false) {
						// security check
						if(files("size", "datoteka") > 10 * 1024 * 1024) {
							$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Datoteka je prevelika! Maksimalna dozvoljena veličina je 10 MB!");
							$_SESSION["exception"] = serialize($handler);
							preusmjeri(\route\Route::get('d3')->generate(array(
								"controller" => "voditelj",
								"action" => "modifyCompetitionData"
							)) . "?msg=excep&id=" . post("idPodrucja"));
						}
						if(!is_uploaded_file(files("tmp_name", "datoteka"))) {
							$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Morate poslati datoteku!");
							$_SESSION["exception"] = serialize($handler);
							preusmjeri(\route\Route::get('d3')->generate(array(
								"controller" => "voditelj",
								"action" => "modifyCompetitionData"
							)) . "?msg=excep&id=" . post("idPodrucja"));
						}
						
						// uncomment this if you want to accept only jpg format
//						// check if it is a jpg
//						if(function_exists('finfo_file')) {
//							$finfo = \finfo_open(FILEINFO_MIME_TYPE);
//							$mime = finfo_file($finfo, files("tmp_name", "datoteka"));
//						} else {
//							$mime = \mime_content_type(files("tmp_name", "datoteka"));
//						}
//						if($mime != 'image/jpeg' && $mime != 'image/jpg') {
//							$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Sliku možete poslati samo u jpeg formatu!");
//							$_SESSION["exception"] = serialize($handler);
//							preusmjeri(\route\Route::get('d3')->generate(array(
//								"controller" => "voditelj",
//								"action" => "modifyCompetitionData"
//							)) . "?msg=excep&id=" . post("idPodrucja"));
//						}
//
//						// adding the path and the file
//						$putanja = "./elektrijada_slike/" . date("Y_m_d_H_i_s") . ".jpg";
						// adding the path and the file
						$putanja = "./elektrijada_slike/" . date("Y_m_d_H_i_s") . "_" . files("name", "datoteka");
						if (move_uploaded_file(files("tmp_name", "datoteka"), $putanja)) {
							// if there was already a CV on the server
							// remove it
							if ($elekPod->slikaLink !== NULL) {
								$p = unlink($elekPod->slikaLink);
								if ($p === false) {
									$e = new \PDOException();
									$e->errorInfo[0] = '02000';
									$e->errorInfo[1] = 1604;
									$e->errorInfo[2] = "Greška prilikom brisanja datoteke!";
									throw $e;
								}
							}
							
							// add path to db
							$elekPod->addImage($elekPod->getPrimaryKey(), $putanja);		
						} else {
							$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Dogodio se problem sa spremanjem datoteke! Ostali podaci su ažurirani!");
							$_SESSION["exception"] = serialize($handler);
							preusmjeri(\route\Route::get('d3')->generate(array(
								"controller" => "voditelj",
								"action" => "modifyCompetitionData"
							)) . "?msg=excep&id=" . post("idPodrucja"));
						}
					} else {
						// check if he wants to delete the old CV
						if (post("delete") !== false && $elekPod->slikaLink != NULL) {
							$p = unlink($elekPod->slikaLink);
							if ($p === false) {
								$e = new \PDOException();
								$e->errorInfo[0] = '02000';
								$e->errorInfo[1] = 1604;
								$e->errorInfo[2] = "Greška prilikom brisanja datoteke!";
								$elekPod->addImage($elekPod->getPrimaryKey(), NULL);
								throw $e;
							}
							$elekPod->addImage($elekPod->getPrimaryKey(), NULL);	// delete path from db
						}
					}
					
					// success -> redirect
					preusmjeri(\route\Route::get('d3')->generate(array(
								"controller" => "voditelj",
								"action" => "modifyCompetitionData"
							)) . "?msg=succC&id=" . post("idPodrucja"));
				}
			} catch (\app\model\NotFoundException $e) {
				$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Nepoznati identifikator!");
				$_SESSION["exception"] = serialize($handler);
				preusmjeri(\route\Route::get('d3')->generate(array(
					"controller" => "voditelj",
					"action" => "modifyCompetitionData"
				)) . "?msg=excep&id=" . post("idPodrucja"));
			} catch (\PDOException $e) {
				$handler = new \model\ExceptionHandlerModel($e);
				$_SESSION["exception"] = serialize($handler);
				preusmjeri(\route\Route::get('d3')->generate(array(
					"controller" => "voditelj",
					"action" => "modifyCompetitionData"
				)) . "?msg=excep&id=" . post("idPodrucja"));
			}
		}
		
		echo new \view\Main(array(
			"title" => $naziv,
			"body" => new \view\voditelj\ModifyCompetitionData(array(
				"errorMessage" => $this->errorMessage,
				"resultMessage" => $this->resultMessage,
				"elekPod" => $elekPod,
				"idPodrucja" => $idPodrucja,
				"natjecatelja" => $natjecatelja,
				"timova" => $timova
			))
		));
	}
	
	public function modifyResults() {
		$this->checkRole();
		$this->checkMessages();
		
		$elektrijada = new \model\DBElektrijada();
		$podrucjeSudjelovanja = new \model\DBPodrucjeSudjelovanja();
		$takmicari = null;
		
		if (postEmpty()) {
			// display form
			$this->idCheck("displayPodrucja");
			$this->checkAuthority(get("id"));
			$idPodrucje = get("id");
			
			try {
				$idElektrijade = $elektrijada->getCurrentElektrijadaId();
				$takmicari = $podrucjeSudjelovanja->getPaticipants(get("id"), $idElektrijade);
			} catch (\app\model\NotFoundException $e) {
				$this->createMessage("Nepoznati identifikator!", "d3", "voditelj", "displayPodrucja");
			} catch (\PDOException $e) {
				$handler = new \model\ExceptionHandlerModel($e);
				$this->createMessage($handler, "d3", "voditelj", "displayPodrucja");
			}			
		} else {
			// proccess query
			try {
				$idPodrucje = post("idPodrucja");
				$this->checkAuthority($idPodrucje);
				foreach($_POST as $k => $r) {
					if ($k !== "idPodrucja" && $k[0] !== 'b') {
						$validacija = new \model\formModel\NumberValidationModel(array("number" => $r,
																						"num" => post('b' . $k)));
						$validacija->setRules(array("number" => array("numbers"),
													"num" => array("numbers")));
						$pov = $validacija->validate();
						if ($pov !== true) {
							$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Rezultat i broj sudionika mogu biti samo brojevi!");
							$_SESSION["exception"] = serialize($handler);
							preusmjeri(\route\Route::get('d3')->generate(array(
								"controller" => "voditelj",
								"action" => "modifyResults"
							)) . "?msg=excep&id=" . post("idPodrucja"));
						}
						
						if ($r > post('b' . $k, "0")) {
							$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Rezultat mora biti manji ili jednak broju sudionika!");
							$_SESSION["exception"] = serialize($handler);
							preusmjeri(\route\Route::get('d3')->generate(array(
								"controller" => "voditelj",
								"action" => "modifyResults"
							)) . "?msg=excep&id=" . post("idPodrucja"));
						}
					}
				}
				
				// everything's okay lets add
				foreach($_POST as $k => $r) {
					if ($k !== "idPodrucja" && $k[0] !== 'b') {
						$podrucjeSudjelovanja->modifyRow($k, FALSE, FALSE, ($r === '' ? NULL : $r), FALSE, post("b" . $k, NULL), FALSE);
					}
				}
				
				// success -> redirect
				preusmjeri(\route\Route::get('d3')->generate(array(
								"controller" => "voditelj",
								"action" => "modifyResults"
							)) . "?msg=succR&id=" . post("idPodrucja"));
			} catch (\app\model\NotFoundException $e) {
				$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Nepoznati identifikator!");
				$_SESSION["exception"] = serialize($handler);
				preusmjeri(\route\Route::get('d3')->generate(array(
					"controller" => "voditelj",
					"action" => "modifyResults"
				)) . "?msg=excep&id=" . post("idPodrucja"));
			} catch (\PDOException $e) {
				$handler = new \model\ExceptionHandlerModel($e);
				$_SESSION["exception"] = serialize($handler);
				preusmjeri(\route\Route::get('d3')->generate(array(
					"controller" => "voditelj",
					"action" => "modifyResults"
				)) . "?msg=excep&id=" . post("idPodrucja"));
			}		
		}
		
		if (get("type") !== false && get("id") !== false) {
			$pomPolje = array("Ime", "Prezime", "JMBAG", "Vrsta natjecanja", "Rezultat", "Broj sudionika");
			$array = array();
			$array[] = $pomPolje;

			if ($takmicari !== null && count($takmicari)) {
				foreach ($takmicari as $v) {
					$array[] = array($v->ime, $v->prezime, $v->JMBAG,
						($v->vrstaPodrucja == 1 ? "Timsko" : "Pojedinačno"), $v->rezultatPojedinacni, $v->ukupanBrojSudionika);
				}
			}

			$path = $this->generateFile(get("type"), $array);

			echo new \view\ShowFile(array(
				"path" => $path,
				"type" => get("type")
			));
		}
		
		echo new \view\Main(array(
			"title" => "Rezultati",
			"body" => new \view\voditelj\ModifyResults(array(
				"errorMessage" => $this->errorMessage,
				"resultMessage" => $this->resultMessage,
				"idPodrucja" => $idPodrucje,
				"takmicari" => $takmicari
			))
		));
	}

    public function displayProfile() {
		$this->checkRole();
		$this->checkMessages();
		$this->changesAllowed();

		$osoba = new \model\DBOsoba();
		$sudjelovanje = new \model\DBSudjelovanje();
		$elektrijada = new \model\DBElektrijada();
		$godina = new \model\DBGodStud();
		$velMajice = new \model\DBVelMajice();
		$radnoMjesto = new \model\DBRadnoMjesto();
		$zavod = new \model\DBZavod();
		$smjer = new \model\DBSmjer();
		$godine = $godina->getAllGodStud();
		$velicine = $velMajice->getAllVelicina();
		$radnaMjesta = $radnoMjesto->getAllRadnoMjesto();
		$zavodi = $zavod->getAllZavod();
		$smjerovi = $smjer->getAllSmjer();

		// the display part
		try {
			$osoba->load(session("auth"));
			$idElektrijade = $elektrijada->getCurrentElektrijadaId();
			$sudjelovanje->loadByContestant(session("auth"), $idElektrijade);

			if ($sudjelovanje->isStaff()) {
				$radnoMjesto->loadIfExists($sudjelovanje->idRadnogMjesta);
				$zavod->loadIfExists($sudjelovanje->idZavoda);
				$godina->loadIfExists($sudjelovanje->idGodStud);
				$smjerovi = null;
			} else if ($sudjelovanje->isStudent()) {
				$godina->loadIfExists($sudjelovanje->idGodStud);
				$smjer->loadIfExists($sudjelovanje->idSmjera);
				$radnaMjesta = null;
				$zavodi = null;
			}
			$velMajice->loadIfExists($sudjelovanje->idVelicine);
		} catch (\app\model\NotFoundException $e) {
			$this->createMessage("Greška prilikom dohvata Vaših podataka!");
		} catch (\PDOException $e) {
			$handler = new \model\ExceptionHandlerModel($e);
			$this->createMessage($handler);
		}

		echo new \view\Main(array(
			"title" => "Vaši Podaci",
			"body" => new \view\voditelj\Profile(array(
				"errorMessage" => $this->errorMessage,
				"resultMessage" => $this->resultMessage,
				"osoba" => $osoba,
				"radnaMjesta" => $radnaMjesta,
				"velicine" => $velicine,
				"godine" => $godine,
				"smjerovi" => $smjerovi,
				"zavodi" => $zavodi,
				"velicina" => $velMajice,
				"godina" => $godina,
				"smjer" => $smjer,
				"radnoMjesto" => $radnoMjesto,
				"zavod" => $zavod,
				"sudjelovanje" => $sudjelovanje,
				"disabled" => $this->changesDisabled
				)),
			"script" => new \view\scripts\PersonFormJs(array(
				"modification" => true
			))
		));
    }
	
    public function modifyProfile() {
		$this->checkRole();
		$this->checkMessages();
		$this->changesAllowed();

		if ($this->changesDisabled)
		   $this->createMessage("Prošao je rok za promjenu podataka!", "d3", "voditelj", "displayProfile");

		$osoba = new \model\DBOsoba();

		if (!postEmpty() || files("tmp_name", "datoteka") !== false) {
			try {
				$validacija = new \model\PersonFormModel(array('password' => post('password'),
								'ferId' => post('ferId'),
								'ime' => post('ime'), 
								'prezime' => post('prezime'), 
								'mail' => post('mail'), 
								'brojMob' => post('brojMob'), 
								'JMBAG' => post('JMBAG'),
								'spol' => post('spol'), 
								'datRod' => post('datRod'), 
								'brOsobne' => post('brOsobne'), 
								'brPutovnice' => post('brPutovnice'), 
								'osobnaVrijediDo' => post('osobnaVrijediDo'), 
								'putovnicaVrijediDo' => post('putovnicaVrijediDo'),
								'MBG' => post('MBG'),
								'OIB' => post('OIB'),
								'password_new' => post('password_new'),
								'password_new2' => post('password_new2')));
				$pravila = $validacija->getRules();
				$pravila['password'] = array('password');
				$validacija->setRules($pravila);
				$pov = $validacija->validate();
				if($pov !== true) {
				   $this->createMessage($validacija->decypherErrors($pov), "d3", "voditelj", "displayProfile");
				} else {
					// everything's ok ; insert new row
					// first check for passwords
					if (post("password") !== false) {
						if (post("password_new") !== false && post("password_new2") !== false) {
							$pov = $osoba->checkPassword(post("idOsobe"), post("password"));
							if ($pov === false || $pov->getPrimaryKey() != session('auth')) {
								$this->createMessage("Pogrešna stara lozinka!", "d3", "voditelj", "displayProfile");
							}
							if (post("password_new") !== post("password_new2")) {
								$this->createMessage("Nove lozinke se ne podudaraju!", "d3", "voditelj", "displayProfile");
							}
						} else {
							$this->createMessage("Ukoliko mijenjate lozinku, morate unijeti staru, kao i novu lozinku!", "d3", "voditelj", "displayProfile");
						}
					} else {
						if(post("password_new") !== false || post("password_new2") !== false) {
							$this->createMessage("Morate unijeti staru lozinku!", "d3", "voditelj", "displayProfile");
						}
					}

					// process query
					$osoba->modifyPerson(post("idOsobe"), post("ime", NULL), post("prezime", NULL), post("mail", NULL), 
							post("brojMob", NULL), post("ferId", NULL), post("password_new", NULL), post("JMBAG", NULL),
							post("spol", NULL), post("datRod", NULL), post("brOsobne", NULL), post("brPutovnice", NULL),
							post("osobnaVrijediDo", NULL), post("putovnicaVrijediDo", NULL), NULL, post("MBG", NULL), 
							post("OIB", NULL), post("aktivanDokument", NULL));
					
					$osoba->load(post("idOsobe"));
					// now add the competition data
					$sudjelovanje = new \model\DBSudjelovanje();
					$sudjelovanje->load(post("idSudjelovanja"));
					
					if ($sudjelovanje->isStudent()) {
						$sudjelovanje->modifyRow(post("idSudjelovanja"), FALSE, FALSE, FALSE, post("idVelicine", NULL),
								post("idGodStud", NULL), post("idSmjera", NULL), NULL, NULL, FALSE);
					} else {
						$sudjelovanje->modifyRow(post("idSudjelovanja"), FALSE, FALSE, FALSE, post("idVelicine", NULL),
								post("idGodStud", NULL), NULL, post("idRadnogMjesta", NULL), post("idZavoda", NULL), FALSE);
					}
					
					// check CV
					if (files("tmp_name", "datoteka") !== false) {
						// security check
						if(files("size", "datoteka") > 1024 * 1024) {
							$this->createMessage("Datoteka je prevelika! Maksimalna dozvoljena veličina je 1 MB!", "d3", "voditelj", "displayProfile");
						}
						if(!is_uploaded_file(files("tmp_name", "datoteka"))) {
							$this->createMessage("Morate poslati datoteku!", "d3", "voditelj", "displayProfile");
						}
						// check if it is a pdf
						if(function_exists('finfo_file')) {
							$finfo = \finfo_open(FILEINFO_MIME_TYPE);
							$mime = finfo_file($finfo, files("tmp_name", "datoteka"));
						} else {
							$mime = \mime_content_type(files("tmp_name", "datoteka"));
						}
						if($mime != 'application/pdf') {
							$this->createMessage("Životopis možete poslati samo u pdf formatu!", "d3", "voditelj", "displayProfile");
						}

						// adding the path and the file
						$putanja = "./zivotopisi/" . date("Y_m_d_H_i_s") . "_" . post("idOsobe") . ".pdf";
						if (move_uploaded_file(files("tmp_name", "datoteka"), $putanja)) {
							// if there was already a CV on the server
							// remove it
							if ($osoba->zivotopis !== NULL) {
								$p = unlink($osoba->zivotopis);
								if ($p === false) {
									$e = new \PDOException();
									$e->errorInfo[0] = '02000';
									$e->errorInfo[1] = 1604;
									$e->errorInfo[2] = "Greška prilikom brisanja životopisa!";
									throw $e;
								}
							}
							
							// add path to db
							$osoba->addCV(post("idOsobe"), $putanja);		
						} else {
							$this->createMessage("Dogodio se problem sa spremanjem životopisa! Ostali podaci su ažurirani!", "d3", "voditelj", "displayProfile");
						}
					} else {
						// check if he wants to delete the old CV
						if (post("delete") !== false && $osoba->zivotopis != NULL) {
							$p = unlink($osoba->zivotopis);
							if ($p === false) {
								$e = new \PDOException();
								$e->errorInfo[0] = '02000';
								$e->errorInfo[1] = 1604;
								$e->errorInfo[2] = "Greška prilikom brisanja životopisa!";
								$osoba->addCV(post("idOsobe"), NULL);
								throw $e;
							}
							$osoba->addCV(post("idOsobe"), NULL);	// delete path from db
						}
					}

					// success -> redirect
					preusmjeri(\route\Route::get("d1")->generate() . "?msg=profSucc");
					
				}
			} catch (\app\model\NotFoundException $e) {
				$this->createMessage("Nepostojeći identifikator!", "d3", "voditelj", "displayProfile");
			} catch (\PDOException $e) {
				$handler = new \model\ExceptionHandlerModel($e);
				$this->createMessage($handler, "d3", "voditelj", "displayProfile");
			} 
		}
		$this->createMessage("Morate unijeti podatke!", "d3", "voditelj", "displayProfile");
    }
	
	public function downloadCV() {
		$this->checkRole();
		$this->checkMessages();

		if (count($_GET) === 0 || get("id") === false)
			$this->createMessage("Nepoznata osoba!");

		$osoba = new \model\DBOsoba();
		try {
			$osoba->load(get("id"));
		} catch (\app\model\NotFoundException $e) {
			$this->createMessage("Nepoznata osoba!");
		} catch (\PDOException $e) {
			$handler = new \model\ExceptionHandlerModel($e);
			$this->createMessage($handler);
		}

		echo new \view\Download(array(
			"path" => $osoba->zivotopis
		));
	}
	
	public function downloadImage() {
		$this->checkRole();
		$this->checkMessages();

		if (count($_GET) === 0 || get("id") === false)
			$this->createMessage("Nepoznata datoteka!");
		
		$elekPod = new \model\DBElekPodrucje();
		try {
			$elekPod->load(get("id"));
		} catch (\app\model\NotFoundException $e) {
			$this->createMessage("Nepoznati zapis!");
		} catch (\PDOException $e) {
			$handler = new \model\ExceptionHandlerModel($e);
			$this->createMessage($handler);
		}

		echo new \view\Download(array(
			"path" => $elekPod->slikaLink
		));
	}
	
	public function changeContestantAttributes() {
		$this->checkRole();
		$this->checkMessages();
		
		$sudjelovanje = new \model\DBSudjelovanje();
		$podrucjeSudjelovanja = new \model\DBPodrucjeSudjelovanja();
		$podrucje = new \model\DBPodrucje();
		$imaatribut = new \model\DBImaatribut();
		$atribut = new \model\DBAtribut();
		
		$atributi = null;
		$podrucja = null;
		$korisnikoviAtributi = null;
		
		
		if (postEmpty()) {
			// get data to show
			$this->getParamCheck("idS", "displayPodrucja");
			$this->getParamCheck("idP", "displayPodrucja");
			$idSudjelovanja = get("idS");
			$idPodrucja = get("idP");
			
			try {
				$sudjelovanje->load(get("idS"));
				$elektrijada = new \model\DBElektrijada();
				$idElektrijade = $elektrijada->getCurrentElektrijadaId();
				
				if ($sudjelovanje->idElektrijade != $idElektrijade)
					$this->createMessage("Ne možete mijenjati prošlogodišnje zapise!", "d3", "voditelj", "searchContestants");
				
				$podrucja = $podrucje->getAll();
				$atributi = $atribut->getAllExceptTeamLeader();
				
				$podrucjeSudjelovanja = $podrucjeSudjelovanja->loadIfExists(get("idP"), get("idS"), get("vrsta"));
				
				$at = $imaatribut->getAllContestantAttributes(get("idS"));
				
				$korisnikoviAtributi = array();
				if (count($at)) {
					foreach ($at as $a) {
						if ($a->idPodrucja == get("idP"))
							$korisnikoviAtributi[] = $a->idAtributa;
					}
				}
				
			} catch (\app\model\NotFoundException $e) {
				$this->createMessage("Nepoznati identifikator", "d3", "voditelj", "displayPodrucja");
			} catch (\PDOException $e) {
				$handler = new \model\ExceptionHandlerModel($e);
				$this->createMessage($handler, "d3", "voditelj", "displayPodrucja");
			}
		} else {
			$idSudjelovanja = post("idS");
			$idPodrucja = post("idP");
			
			try {
				// now lets modify the attributes
				// first delete the old ones, and after that add new
				$imaatribut->deleteContestantsAttributesExceptLeader($idSudjelovanja, $idPodrucja);
				
				$idVoditelja = $atribut->getTeamLeaderId();
				// now add the new ones if any
				foreach (post("idAtributa") as $k => $v) {
					if ($v !== '' && $v !== $idVoditelja) {
						$imaatribut = new \model\DBImaatribut();
						$imaatribut->addRow($idPodrucja, $v, $idSudjelovanja);
					}
				}
				
				// success redirect
				preusmjeri(\route\Route::get("d3")->generate(array(
					"controller" => "voditelj",
					"action" => "displayTeam"
				)) . "?id=" . $idPodrucja . "&msg=succM");
			} catch (\app\model\NotFoundException $e) {
				$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Nepoznati identifikator");
				$_SESSION["exception"] = serialize($handler);
				preusmjeri(\route\Route::get('d3')->generate(array(
					"controller" => "voditelj",
					"action" => "changeContestantAttributes"
				)) . "?msg=excep&idP=" . $idPodrucja . "&idS=" . $idSudjelovanja);
			} catch (\PDOException $e) {
				$handler = new \model\ExceptionHandlerModel($e);
				$_SESSION["exception"] = serialize($handler);
				preusmjeri(\route\Route::get('d3')->generate(array(
					"controller" => "voditelj",
					"action" => "changeContestantAttributes"
				)) . "?msg=excep&idP=" . $idPodrucja . "&idS=" . $idSudjelovanja);
			}
		}
		
		echo new \view\Main(array(
			"title" => "Ažuriranje Atributa",
			"body" => new \view\voditelj\ContestantAttributes(array(
				"errorMessage" => $this->errorMessage,
				"resultMessage" => $this->resultMessage,
				"podrucjeSudjelovanja" => $podrucjeSudjelovanja,
				"podrucja" => $podrucja,
				"atributi" => $atributi,
				"korisnikoviAtributi" => $korisnikoviAtributi,
				"idSudjelovanja" => $idSudjelovanja,
				"idPodrucja" => $idPodrucja
			))
		));
	}
}
