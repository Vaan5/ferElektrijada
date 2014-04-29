<?php

namespace ctl;
use app\controller\Controller;
use \PDOException;

class Sudionik implements Controller {
    
    private $errorMessage;
    private $resultMessage;
    private $changesDisabled;
    
    private function checkRole() {
	// you must be an active contestant
	$sudjelovanje = new \model\DBSudjelovanje();
	if (\model\DBOsoba::isLoggedIn() && session("vrsta") === "S" && $sudjelovanje->isActiveContestant(session("auth")))
	    return;
	
	preusmjeri(\route\Route::get('d1')->generate() . "?msg=accessDenied");
    }
    
    private function createMessage($message, $type = 'd1', $controller = null, $action = null) {
	$handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
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
	    
	    $sudjelovanje = new \model\DBSudjelovanje();
	    $podrucja = $sudjelovanje->getContestantAreas(session("auth"), $idElektrijade);
	    
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
            $this->createMessage("Problem prilikom provjere dozvoljenosti provjera!");
	} catch (\PDOException $e) {
	    $handler = new \model\ExceptionHandlerModel($e);
	    $this->createMessage($handler);
	}
    }
    
    private function checkMessages() {
        switch(get("msg")) {
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
    
    /**
     * Displays your profile
     */
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
	    "body" => new \view\sudionik\Profile(array(
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
	    ))
	));
    }
    
    public function modifyProfile() {
	$this->checkRole();
	$this->checkMessages();
	$this->changesAllowed();
	
	if ($this->changesDisabled)
	   $this->createMessage("Prošao je rok za promjenu podataka!", "d3", "sudionik", "displayProfile");
	
	$osoba = new \model\DBOsoba();
	
	if (!postEmpty()) {
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
		   $this->createMessage($validacija->decypherErrors($pov), "d3", "sudionik", "displayProfile");
		} else {
		    // everything's ok ; insert new row
		    // first check for passwords
		    if (post("password") !== false) {
			if (post("password_new") !== false && post("password_new2") !== false) {
			    $pov = $osoba->checkPassword(post("idOsobe"), post("password"));
			    if ($pov === false || $pov->getPrimaryKey() != session('auth')) {
				$this->createMessage("Pogrešna stara lozinka!", "d3", "sudionik", "displayProfile");
			    }
			    if (post("password_new") !== post("password_new2")) {
				$this->createMessage("Nove lozinke se ne podudaraju!", "d3", "sudionik", "displayProfile");
			    }
			} else {
			    $this->createMessage("Ukoliko mijenjate lozinku, morate unijeti staru, kao i novu lozinku!", "d3", "sudionik", "displayProfile");
			}
		    } else {
			if(post("password_new") !== false || post("password_new2") !== false) {
			    $this->createMessage("Morate unijeti staru lozinku!", "d3", "sudionik", "displayProfile");
			}
		    }
		    
		    // process query
		    
		    
		    // success -> redirect
		    
		    
		}
		
		
	    } catch (\app\model\NotFoundException $e) {
		$this->createMessage("Nepostojeći identifikator!", "d3", "sudionik", "displayProfile");
	    } catch (\PDOException $e) {
		$handler = new \model\ExceptionHandlerModel($e);
		$this->createMessage($handler, "d3", "sudionik", "displayProfile");
	    } 
	}
	
	$this->createMessage("Morate unijeti podatke!", "d3", "sudionik", "displayProfile");
	
    }
}
