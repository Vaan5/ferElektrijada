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

		preusmjeri(\route\Route::get('d1')->generate() . "?msg=accessDenied");
    }
    
	private function idCheck($akcija) {
		$validator = new \model\formModel\IdValidationModel(array("id" => get("id")));
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
		} catch (app\model\NotFoundException $e) {
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
			"title" => "Upravljanje Disciplinama",
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
			))
		));
	}
	
	public function assignExistingPerson() {
		$this->checkRole();
		$this->checkMessages();
		$this->changesAllowed();
		
		$osobe = array();
		if (postEmpty()) {
			$this->idCheck("displayPodrucja");
			$osoba = new \model\DBOsoba();
			$idPodrucja = get("id");
			
		} else {
			// proccess query
			$idPodrucja;
			
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
			$pomPolje = array("Ime", "Prezime", "Email", "Mobitel", "JMBAG", "OIB", "Korisničko ime",
				"Osobna iskaznica", "vrijedi do", "Putovnica", "vrijedi do", "Rezultat", "Ukupno Sudionika");
			$array = array();
			$array[] = $pomPolje;
			
			if ($takmicari !== null && count($takmicari)) {
				foreach ($takmicari as $v) {
					$array[] = array($v->ime, $v->prezime, $v->mail, $v->brojMob, $v->JMBAG, $v->OIB, $v->ferId,
						$v->brOsobne, $v->osobnaVrijediDo, $v->brPutovnice, $v->putovnicaVrijediDo, $v->rezultatPojedinacni, $v->ukupanBrojSudionika);
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
		
	}
	
//    public function displayProfile() {
//		$this->checkRole();
//		$this->checkMessages();
//		$this->changesAllowed();
//
//		$osoba = new \model\DBOsoba();
//		$sudjelovanje = new \model\DBSudjelovanje();
//		$elektrijada = new \model\DBElektrijada();
//		$godina = new \model\DBGodStud();
//		$velMajice = new \model\DBVelMajice();
//		$radnoMjesto = new \model\DBRadnoMjesto();
//		$zavod = new \model\DBZavod();
//		$smjer = new \model\DBSmjer();
//		$godine = $godina->getAllGodStud();
//		$velicine = $velMajice->getAllVelicina();
//		$radnaMjesta = $radnoMjesto->getAllRadnoMjesto();
//		$zavodi = $zavod->getAllZavod();
//		$smjerovi = $smjer->getAllSmjer();
//
//		// the display part
//		try {
//			$osoba->load(session("auth"));
//			$idElektrijade = $elektrijada->getCurrentElektrijadaId();
//			$sudjelovanje->loadByContestant(session("auth"), $idElektrijade);
//
//			if ($sudjelovanje->isStaff()) {
//				$radnoMjesto->loadIfExists($sudjelovanje->idRadnogMjesta);
//				$zavod->loadIfExists($sudjelovanje->idZavoda);
//				$godina->loadIfExists($sudjelovanje->idGodStud);
//				$smjerovi = null;
//			} else if ($sudjelovanje->isStudent()) {
//				$godina->loadIfExists($sudjelovanje->idGodStud);
//				$smjer->loadIfExists($sudjelovanje->idSmjera);
//				$radnaMjesta = null;
//				$zavodi = null;
//			}
//			$velMajice->loadIfExists($sudjelovanje->idVelicine);
//		} catch (\app\model\NotFoundException $e) {
//			$this->createMessage("Greška prilikom dohvata Vaših podataka!");
//		} catch (\PDOException $e) {
//			$handler = new \model\ExceptionHandlerModel($e);
//			$this->createMessage($handler);
//		}
//
//		echo new \view\Main(array(
//			"title" => "Vaši Podaci",
//			"body" => new \view\sudionik\Profile(array(
//				"errorMessage" => $this->errorMessage,
//				"resultMessage" => $this->resultMessage,
//				"osoba" => $osoba,
//				"radnaMjesta" => $radnaMjesta,
//				"velicine" => $velicine,
//				"godine" => $godine,
//				"smjerovi" => $smjerovi,
//				"zavodi" => $zavodi,
//				"velicina" => $velMajice,
//				"godina" => $godina,
//				"smjer" => $smjer,
//				"radnoMjesto" => $radnoMjesto,
//				"zavod" => $zavod,
//				"sudjelovanje" => $sudjelovanje,
//				"disabled" => $this->changesDisabled
//				)),
//			"script" => new \view\scripts\PersonFormJs()
//		));
//    }
//	
//    public function modifyProfile() {
//		$this->checkRole();
//		$this->checkMessages();
//		$this->changesAllowed();
//
//		if ($this->changesDisabled)
//		   $this->createMessage("Prošao je rok za promjenu podataka!", "d3", "sudionik", "displayProfile");
//
//		$osoba = new \model\DBOsoba();
//
//		if (!postEmpty() || files("tmp_name", "datoteka") !== false) {
//			try {
//				$validacija = new \model\PersonFormModel(array('password' => post('password'),
//								'ferId' => post('ferId'),
//								'ime' => post('ime'), 
//								'prezime' => post('prezime'), 
//								'mail' => post('mail'), 
//								'brojMob' => post('brojMob'), 
//								'JMBAG' => post('JMBAG'),
//								'spol' => post('spol'), 
//								'datRod' => post('datRod'), 
//								'brOsobne' => post('brOsobne'), 
//								'brPutovnice' => post('brPutovnice'), 
//								'osobnaVrijediDo' => post('osobnaVrijediDo'), 
//								'putovnicaVrijediDo' => post('putovnicaVrijediDo'),
//								'MBG' => post('MBG'),
//								'OIB' => post('OIB'),
//								'password_new' => post('password_new'),
//								'password_new2' => post('password_new2')));
//				$pravila = $validacija->getRules();
//				$pravila['password'] = array('password');
//				$validacija->setRules($pravila);
//				$pov = $validacija->validate();
//				if($pov !== true) {
//				   $this->createMessage($validacija->decypherErrors($pov), "d3", "sudionik", "displayProfile");
//				} else {
//					// everything's ok ; insert new row
//					// first check for passwords
//					if (post("password") !== false) {
//						if (post("password_new") !== false && post("password_new2") !== false) {
//							$pov = $osoba->checkPassword(post("idOsobe"), post("password"));
//							if ($pov === false || $pov->getPrimaryKey() != session('auth')) {
//								$this->createMessage("Pogrešna stara lozinka!", "d3", "sudionik", "displayProfile");
//							}
//							if (post("password_new") !== post("password_new2")) {
//								$this->createMessage("Nove lozinke se ne podudaraju!", "d3", "sudionik", "displayProfile");
//							}
//						} else {
//							$this->createMessage("Ukoliko mijenjate lozinku, morate unijeti staru, kao i novu lozinku!", "d3", "sudionik", "displayProfile");
//						}
//					} else {
//						if(post("password_new") !== false || post("password_new2") !== false) {
//							$this->createMessage("Morate unijeti staru lozinku!", "d3", "sudionik", "displayProfile");
//						}
//					}
//
//					// process query
//					$osoba->modifyPerson(post("idOsobe"), post("ime", NULL), post("prezime", NULL), post("mail", NULL), 
//							post("brojMob", NULL), post("ferId", NULL), post("password_new", NULL), post("JMBAG", NULL),
//							post("spol", NULL), post("datRod", NULL), post("brOsobne", NULL), post("brPutovnice", NULL),
//							post("osobnaVrijediDo", NULL), post("putovnicaVrijediDo", NULL), NULL, post("MBG", NULL), 
//							post("OIB", NULL), post("aktivanDokument", NULL));
//					
//					$osoba->load(post("idOsobe"));
//					// now add the competition data
//					$sudjelovanje = new \model\DBSudjelovanje();
//					$sudjelovanje->load(post("idSudjelovanja"));
//					
//					if ($sudjelovanje->isStaff()) {
//						$sudjelovanje->modifyRow(post("idSudjelovanja"), FALSE, FALSE, FALSE, post("idVelicine", NULL),
//								post("idGodStud", NULL), NULL, post("idRadnogMjesta", NULL), post("idZavoda", NULL), FALSE);
//					} else {
//						$sudjelovanje->modifyRow(post("idSudjelovanja"), FALSE, FALSE, FALSE, post("idVelicine", NULL),
//								post("idGodStud", NULL), post("idSmjera", NULL), NULL, NULL, FALSE);
//					}
//					
//					// check CV
//					if (files("tmp_name", "datoteka") !== false) {
//						// security check
//						if(files("size", "datoteka") > 1024 * 1024) {
//							$this->createMessage("Datoteka je prevelika! Maksimalna dozvoljena veličina je 1 MB!", "d3", "sudionik", "displayProfile");
//						}
//						if(!is_uploaded_file(files("tmp_name", "datoteka"))) {
//							$this->createMessage("Morate poslati datoteku!", "d3", "sudionik", "displayProfile");
//						}
//						// check if it is a pdf
//						if(function_exists('finfo_file')) {
//							$finfo = \finfo_open(FILEINFO_MIME_TYPE);
//							$mime = finfo_file($finfo, files("tmp_name", "datoteka"));
//						} else {
//							$mime = \mime_content_type(files("tmp_name", "datoteka"));
//						}
//						if($mime != 'application/pdf') {
//							$this->createMessage("Životopis možete poslati samo u pdf formatu!", "d3", "sudionik", "displayProfile");
//						}
//
//						// adding the path and the file
//						$putanja = "./zivotopisi/" . date("Y_m_d_H_i_s") . "_" . post("idOsobe") . ".pdf";
//						if (move_uploaded_file(files("tmp_name", "datoteka"), $putanja)) {
//							// if there was already a CV on the server
//							// remove it
//							if ($osoba->zivotopis !== NULL) {
//								$p = unlink($osoba->zivotopis);
//								if ($p === false) {
//									$e = new \PDOException();
//									$e->errorInfo[0] = '02000';
//									$e->errorInfo[1] = 1604;
//									$e->errorInfo[2] = "Greška prilikom brisanja životopisa!";
//									throw $e;
//								}
//							}
//							
//							// add path to db
//							$osoba->addCV(post("idOsobe"), $putanja);		
//						} else {
//							$this->createMessage("Dogodio se problem sa spremanjem životopisa! Ostali podaci su ažurirani!", "d3", "sudionik", "displayProfile");
//						}
//					} else {
//						// check if he wants to delete the old CV
//						if (post("delete") !== false && $osoba->zivotopis != NULL) {
//							$p = unlink($osoba->zivotopis);
//							if ($p === false) {
//								$e = new \PDOException();
//								$e->errorInfo[0] = '02000';
//								$e->errorInfo[1] = 1604;
//								$e->errorInfo[2] = "Greška prilikom brisanja životopisa!";
//								$osoba->addCV(post("idOsobe"), NULL);
//								throw $e;
//							}
//							$osoba->addCV(post("idOsobe"), NULL);	// delete path from db
//						}
//					}
//
//					// success -> redirect
//					preusmjeri(\route\Route::get("d1")->generate() . "?msg=profSucc");
//					
//				}
//			} catch (\app\model\NotFoundException $e) {
//				$this->createMessage("Nepostojeći identifikator!", "d3", "sudionik", "displayProfile");
//			} catch (\PDOException $e) {
//				$handler = new \model\ExceptionHandlerModel($e);
//				$this->createMessage($handler, "d3", "sudionik", "displayProfile");
//			} 
//		}
//		$this->createMessage("Morate unijeti podatke!", "d3", "sudionik", "displayProfile");
//    }
//	
//	public function downloadCV() {
//		$this->checkRole();
//		$this->checkMessages();
//
//		if (count($_GET) === 0 || get("id") === false)
//			$this->createMessage("Nepoznata osoba!");
//
//		$osoba = new \model\DBOsoba();
//		try {
//			$osoba->load(get("id"));
//		} catch (\app\model\NotFoundException $e) {
//			$this->createMessage("Nepoznata osoba!");
//		} catch (\PDOException $e) {
//			$handler = new \model\ExceptionHandlerModel($e);
//			$this->createMessage($handler);
//		}
//
//		echo new \view\Download(array(
//			"path" => $osoba->zivotopis
//		));
//	}
}
