<?php

namespace ctl;
use app\controller\Controller;
use \PDOException;

class Ozsn implements Controller {
    
    private $errorMessage;
    private $resultMessage;
    
    private function checkRole() {
        // you must be logged in, and an Ozsn member with or without leadership
		$o = new \model\DBOsoba();
		if (!(\model\DBOsoba::isLoggedIn() && (\model\DBOsoba::getUserRole() === 'O' ||
			\model\DBOsoba::getUserRole() === 'OV') && $o->isActiveOzsn(session("auth")))) {
				preusmjeri(\route\Route::get('d1')->generate() . "?msg=accessDenied");
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
	 * Checks if get("id") is set and a number
	 * @param string $akcija
	 */
    private function idCheck($akcija) {
		$validator = new \model\formModel\IdValidationModel(array("id" => get("id")));
		$pov = $validator->validate();
		if ($pov !== true) {
			$message = $validator->decypherErrors($pov);
			$handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
			$_SESSION["exception"] = serialize($handler);
			preusmjeri(\route\Route::get('d3')->generate(array(
				"controller" => "ozsn",
				"action" => $akcija
			)) . "?msg=excep");
		}
    }
    
    private function postGetCheck($akcija) {
		if (false !== post("id")) {
			$validator = new \model\formModel\IdValidationModel(array("id" => post("id")));
			$pov = $validator->validate();
			if ($pov !== true) {
				$message = $validator->decypherErrors($pov);
				$handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
				$_SESSION["exception"] = serialize($handler);
				preusmjeri(\route\Route::get('d3')->generate(array(
					"controller" => "ozsn",
					"action" => $akcija
				)) . "?msg=excep");
			}
		} else if (false !== get("id")) {
			$validator = new \model\formModel\IdValidationModel(array("id" => get("id")));
			$pov = $validator->validate();
			if ($pov !== true) {
				$message = $validator->decypherErrors($pov);
				$handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
				$_SESSION["exception"] = serialize($handler);
				preusmjeri(\route\Route::get('d3')->generate(array(
					"controller" => "ozsn",
					"action" => $akcija
				)) . "?msg=excep");
			}
		} else {
			$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Nepoznati identifikator!");
			$_SESSION["exception"] = serialize($handler);
			preusmjeri(\route\Route::get('d3')->generate(array(
				"controller" => "ozsn",
				"action" => $akcija
			)) . "?msg=excep");
		}
    }
    
    private function checkMessages() {
        switch(get("msg")) {
            case 'succm':
                $this->resultMessage = "Uspješno ažuriran zapis!";
                break;
            case 'succd':
                $this->resultMessage = "Uspješno obrisan zapis!";
                break;
            case 'succa':
                $this->resultMessage = "Uspješno dodan zapis!";
                break;
			case 'succMC':
				$this->resultMessage = "Uspješno izmijenjeni podaci o kontakt osobi!";
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
	
	private function getParamCheck($id, $akcija) {
		$validator = new \model\formModel\IdValidationModel(array("id" => get($id)));
		$pov = $validator->validate();
		if ($pov !== true)
			$this->createMessage($validator->decypherErrors($pov), "d3", "ozsn", $akcija);
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
    
    /**
     * Displays all companies
     */
    public function displayTvrtke() {
	$this->checkRole();
	$this->checkMessages();
	
	$tvrtka = new \model\DBTvrtka();
	$tvrtke = null;
	
	try {
	    $tvrtke = $tvrtka->getAll();
	} catch (\PDOException $e) {
	    $handler = new \model\ExceptionHandlerModel($e);
            $this->errorMessage = $handler;
	}
	
	echo new \view\Main(array(
	    "body" => new \view\ozsn\TvrtkaList(array(
		"errorMessage" => $this->errorMessage,
		"resultMessage" => $this->resultMessage,
		"tvrtke" => $tvrtke
	    )),
	    "title" => "Tvrtke",
		"script" => new \view\scripts\ozsn\TvrtkaListJs()
	));
    }
    
    public function displayUserFunctions() {
	$this->checkRole();
	$this->checkMessages();
	
	$funkcija = new \model\DBFunkcija();
	$obavljaFunkciju = new \model\DBObavljaFunkciju();
	$sveFunkcije = array();
	$funkcijeKorisnika = array();
	try {
	    $elektrijada = new \model\DBElektrijada();
	    $idElektrijade = $elektrijada->getCurrentElektrijadaId();
	    $sveFunkcije = $funkcija->getAllFunkcija();
	    $funkcijeKorisnika = $obavljaFunkciju->loadOzsnFunctions(session("auth"), $idElektrijade);
	} catch (\PDOException $e) {
	    $handler = new \model\ExceptionHandlerModel($e);
            $this->errorMessage = $handler;
	}
	
	echo new \view\Main(array(
	    "body" => new \view\ozsn\OzsnUdrugeList(array(
		"errorMessage" => $this->errorMessage,
		"resultMessage" => $this->resultMessage,
		"sveFunkcije" => $sveFunkcije,
		"funkcijeKorisnika" => $funkcijeKorisnika
	    )),
	    "title" => "Vaše Funkcije"
	));
    }
    
    public function deleteUserFunction() {
	$this->checkRole();
        
        $this->idCheck("displayUserFunctions");
	
        $obavljaFunkciju = new \model\DBObavljaFunkciju();
        try {
	    if ($obavljaFunkciju->checkOzsnFunction(get("id"), session("auth"))) {
		$obavljaFunkciju->deleteRow(get("id"));
	    } else {
		$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Ne možete brisati tuđe funkcije!");
		$_SESSION["exception"] = serialize($handler);
		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "ozsn",
		    "action" => "displayUserFunctions"
		)) . "?msg=excep");
	    }
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayUserFunctions"
            )) . '?msg=succd');
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayUserFunctions"
            )) . "?msg=excep");
        }
    }
    
    public function addUserFunction() {
	$this->checkRole();

        $obavljaFunkciju = new \model\DBObavljaFunkciju();
        
	if (!postEmpty()) {
	    try {
		$elektrijada = new \model\DBElektrijada();
		$idElektrijade = $elektrijada->getCurrentElektrijadaId();
		$obavljaFunkciju->addNewRow(post("idFunkcije", null), session("auth"), $idElektrijade);

		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "ozsn",
		    "action" => "displayUserFunctions"
		)) . '?msg=succa');
	    } catch (\PDOException $e){
		$handler = new \model\ExceptionHandlerModel($e);
		$_SESSION["exception"] = serialize($handler);
		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "ozsn",
		    "action" => "displayUserFunctions"
		)) . "?msg=excep");
	    }
	} else {
	    $handler = new \model\ExceptionHandlerModel(new \PDOException(), "Nepoznata funkcija!");
	    $_SESSION["exception"] = serialize($handler);
	    preusmjeri(\route\Route::get('d3')->generate(array(
		"controller" => "ozsn",
		"action" => "displayUserFunctions"
	    )) . "?msg=excep");
	}
    }
    
    /**
     * Displays associations in which the logged in user is registered
     */
    public function displayUserUdruge() {
	$this->checkRole();
	$this->checkMessages();
	
	$udruga = new \model\DBUdruga();
	$jeUUdruzi = new \model\DBJeUUdruzi();
	$sveUdruge = array();
	$udrugeKorisnika = array();
	try {
	    $sveUdruge = $udruga->getAllUdruga();
	    $udrugeKorisnika = $jeUUdruzi->loadUserUdruge(session("auth"));
	} catch (\PDOException $e) {
	    $handler = new \model\ExceptionHandlerModel($e);
            $this->errorMessage = $handler;
	}
	
	echo new \view\Main(array(
	    "body" => new \view\ozsn\OzsnUdrugeList(array(
		"errorMessage" => $this->errorMessage,
		"resultMessage" => $this->resultMessage,
		"sveUdruge" => $sveUdruge,
		"udrugeKorisnika" => $udrugeKorisnika
	    )),
	    "title" => "Vaše Udruge"
	));
    }
    
    /**
     * Registers a user to an association via post parameter idUdruge
     */
    public function addUserUdruga() {
	$this->checkRole();

        $jeuudruzi = new \model\DBJeUUdruzi();
        
	if (!postEmpty()) {
	    try {
		$jeuudruzi->addRow(post("idUdruge", null), session("auth"));

		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "ozsn",
		    "action" => "displayUserUdruge"
		)) . '?msg=succa');
	    } catch (\PDOException $e){
		$handler = new \model\ExceptionHandlerModel($e);
		$_SESSION["exception"] = serialize($handler);
		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "ozsn",
		    "action" => "displayUserUdruge"
		)) . "?msg=excep");
	    }
	} else {
	    $handler = new \model\ExceptionHandlerModel(new \PDOException(), "Nepoznata udruga!");
	    $_SESSION["exception"] = serialize($handler);
	    preusmjeri(\route\Route::get('d3')->generate(array(
		"controller" => "ozsn",
		"action" => "displayUserUdruge"
	    )) . "?msg=excep");
	}
    }
    
    /**
     * Deletes user registration from an association via get parameter
     */
    public function deleteUserUdruga() {
	$this->checkRole();
        
        $this->idCheck("displayUserUdruge");
	
        $jeuudruzi = new \model\DBJeUUdruzi();
        try {
            $jeuudruzi->deleteRow(get("id"), session("auth"));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayUserUdruge"
            )) . '?msg=succd');
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayUserUdruge"
            )) . "?msg=excep");
        }
    }
    
    /**
     * Adds a new company which stil isn't related to any Elektrijada competition
     */
    public function addTvrtka() {
	$this->checkRole();
	
	$tvrtka = new \model\DBTvrtka();
        $validacija = new \model\formModel\TvrtkaFormModel(array('imeTvrtke' => post("imeTvrtke"),
								    'adresaTvrtke' => post("adresaTvrtke")));
        $pov = $validacija->validate();
        if($pov !== true) {
            $message = $validacija->decypherErrors($pov);
            $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayTvrtke"
            )) . "?msg=excep");
        }
        
        try {
            $tvrtka->addRow(post("imeTvrtke", null), post("adresaTvrtke", null));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayTvrtke"
            )) . '?msg=succa');
        } catch (\PDOException $e){
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayTvrtke"
            )) . "?msg=excep");
        }
    }
    
    public function modifyTvrtka() {
	$this->checkRole();
        
        $tvrtka = new \model\DBTvrtka();
	$validacija = new \model\formModel\TvrtkaFormModel(array('imeTvrtke' => post("imeTvrtke"),
								    'adresaTvrtke' => post("adresaTvrtke")));
        $pov = $validacija->validate();
        if($pov !== true) {
            $message = $validacija->decypherErrors($pov);
            $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayTvrtke"
            )) . "?msg=excep");
        }
        try {
            $tvrtka->modifyRow(post($tvrtka->getPrimaryKeyColumn(), null), post('imeTvrtke', null), post('adresaTvrtke', null));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayTvrtke"
            )) . '?msg=succm');
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayTvrtke"
            )) . "?msg=excep");
        }
    }
    
    public function deleteTvrtka() {
	$this->checkRole();
        
        $this->idCheck("displayTvrtke");
	
        $tvrtka = new \model\DBTvrtka();
        try {
            $tvrtka->deleteRow(get("id"));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayTvrtke"
            )) . '?msg=succd');
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayTvrtke"
            )) . "?msg=excep");
        }
    }
    
    public function assignTvrtka() {
	$this->checkRole();
	$this->checkMessages();
	
	$this->postGetCheck("displayTvrtke");
	$tvrtka = new \model\DBTvrtka();
	$usluga = new \model\DBUsluga();
	$usluge = $usluga->getAll();
	
	if (get("id") !== false) {
	    try {
		$tvrtka->load(get("id"));
	    } catch (\app\model\NotFoundException $e) {
		$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Nepoznata tvrtka!");
		$_SESSION["exception"] = serialize($handler);
		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "ozsn",
		    "action" => "displayTvrtke"
		)) . "?msg=excep");
	    } catch (\PDOException $e) {
		$handler = new \model\ExceptionHandlerModel($e);
		$_SESSION["exception"] = serialize($handler);
		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "ozsn",
		    "action" => "displayTvrtke"
		)) . "?msg=excep");
	    }
	}
	
	if (!postEmpty()) {
	    $validacija = new \model\formModel\TvrtkaAssignFormModel(array('iznosRacuna' => post('iznosRacuna'),
					'nacinPlacanja' => post('nacinPlacanja'),
					'napomena' => post('napomena'),
					'idUsluge' => post('idUsluge')));
	    $pov = $validacija->validate();
	    if($pov !== true) {
		$message = $validacija->decypherErrors($pov);
		$handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
		$_SESSION["exception"] = serialize($handler);
		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "ozsn",
		    "action" => "assignTvrtka"
		)) . "?msg=excep&id=" . post("id"));
	    }
	    
	    try {
		$elektrijada = new \model\DBElektrijada();
		$idElektrijade = $elektrijada->getCurrentElektrijadaId();
		
		$koristiPruza = new \model\DBKoristiPruza();
		$koristiPruza->addRow(post("idUsluge"), post("id"), $idElektrijade, post("iznosRacuna", null),
			post("valutaRacuna", null), post("nacinPlacanja", null), post("napomena", null));
		
		preusmjeri(\route\Route::get('d1')->generate() . "?msg=assignS");
		
	    } catch (\PDOException $e) {
		$handler = new \model\ExceptionHandlerModel($e);
		$_SESSION["exception"] = serialize($handler);
		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "ozsn",
		    "action" => "assignTvrtka"
		)) . "?msg=excep&id=" . post("id"));
	    }
	}
	
	echo new \view\Main(array(
	    "body" => new \view\ozsn\TvrtkaAssign(array(
		"errorMessage" => $this->errorMessage,
		"resultMessage" => $this->resultMessage,
		"tvrtka" => $tvrtka,
		"usluge" => $usluge
	    )),
	    "title" => "Usluge Tvrtke"
	));
    }
    
    public function displayActiveTvrtke() {
	$this->checkRole();
	$this->checkMessages();
	
	$tvrtka = new \model\DBTvrtka();
	$tvrtke = array();
	try {
	    $elektrijada = new \model\DBElektrijada();
	    $idElektrijade = $elektrijada->getCurrentElektrijadaId();
	    $tvrtke = $tvrtka->getAllActive($idElektrijade);
	} catch (\PDOException $e) {
	    $handler = new \model\ExceptionHandlerModel($e);
	    $_SESSION["exception"] = serialize($handler);
	    preusmjeri(\route\Route::get('d1')->generate() . "?msg=excep");
	}
	
	echo new \view\Main(array(
	    "title" => "Tvrtke",
	    "body" => new \view\ozsn\ActiveTvrtkeList(array(
		"errorMessage" => $this->errorMessage,
		"resultMessage" => $this->resultMessage,
		"tvrtke" => $tvrtke
	    )),
		"script" => new \view\scripts\ozsn\ActiveTvrtkeListJs()
	));
    }
    
    /**
     * modifies row in koristipruza via get request (parameter is id)
     */
    public function modifyActiveTvrtka() {
	$this->checkRole();
	$this->checkMessages();
	
	$this->postGetCheck("displayActiveTvrtke");
	
	$koristiPruza = new \model\DBKoristiPruza();
	$usluga = new \model\DBUsluga();
	$usluge = $usluga->getAll();
	$tvrtka = new \model\DBTvrtka();
	
	if(false !== get("id")) {
	    try {
		$koristiPruza->load(get("id"));
		$usluga->load($koristiPruza->idUsluge);
		$tvrtka->load($koristiPruza->idTvrtke);		
	    } catch (\app\model\NotFoundException $e) {
		$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Pogreška prilikom dohvata podataka!");
		$_SESSION["exception"] = serialize($handler);
		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "ozsn",
		    "action" => "displayActiveTvrtke"
		)) . "?msg=excep");
	    } catch (\PDOException $e) {
		$handler = new \model\ExceptionHandlerModel($e);
		$_SESSION["exception"] = serialize($handler);
		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "ozsn",
		    "action" => "displayActiveTvrtke"
		)) . "?msg=excep");
	    }
	}
	
	if (!postEmpty()) {
	    $validacija = new \model\formModel\TvrtkaAssignFormModel(array('iznosRacuna' => post('iznosRacuna'),
					'nacinPlacanja' => post('nacinPlacanja'),
					'napomena' => post('napomena'),
					'idUsluge' => post('idUsluge')));
	    $pov = $validacija->validate();
	    if($pov !== true) {
		$message = $validacija->decypherErrors($pov);
		$handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
		$_SESSION["exception"] = serialize($handler);
		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "ozsn",
		    "action" => "modifyActiveTvrtka"
		)) . "?msg=excep&id=" . post("id"));
	    }
	    
	    try {
		$koristiPruza->modifyRow(post("id"), post("idUsluge", null), $koristiPruza->idTvrtke, $koristiPruza->idElektrijade,
			post("iznosRacuna", null), post("valutaRacuna", null), post("nacinPlacanja", null), post("napomena", null));
		
		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "ozsn",
		    "action" => "displayActiveTvrtke"
		)) . "?msg=succm");
		
	    } catch (\PDOException $e) {
		$handler = new \model\ExceptionHandlerModel($e);
		$_SESSION["exception"] = serialize($handler);
		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "ozsn",
		    "action" => "modifyActiveTvrtka"
		)) . "?msg=excep&id=" . post("id"));
	    }
	    
	}
	
	echo new \view\Main(array(
	    "title" => "Ažuriranje Korištene Usluge",
	    "body" => new \view\ozsn\ActiveTvrtkaModification(array(
		"errorMessage" => $this->errorMessage,
		"resultMessage" => $this->resultMessage,
		"koristiPruza" => $koristiPruza,
		"tvrtka" => $tvrtka,
		"usluga" => $usluga,
		"usluge" => $usluge
	    ))
	));
    }
    
    public function deleteActiveTvrtka() {
	$this->checkRole();
        
        $this->idCheck("displayActiveTvrtke");
	
        $koristiPruza = new \model\DBKoristiPruza();
        try {
            $koristiPruza->deleteRow(get("id"));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayActiveTvrtke"
            )) . '?msg=succd');
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayActiveTvrtke"
            )) . "?msg=excep");
        }
    }

    public function displayMediji() {
	$this->checkRole();
        $this->checkMessages();
        
        $medij = new \model\DBMedij();
	$mediji = array();
        try {
            $mediji = $medij->getAll();
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $this->errorMessage = $handler;
        }

        echo new \view\Main(array(
            "body" => new \view\ozsn\MedijList(array(
                "errorMessage" => $this->errorMessage,
                "resultMessage" => $this->resultMessage,
                "mediji" => $mediji
            )),
            "title" => "Načini Promocije"
        ));
    }
    
    public function addMedij() {
	$this->checkRole();

        $medij = new \model\DBMedij();
        $validacija = new \model\formModel\MedijFormModel(array('nazivMedija' => post("nazivMedija")));
        $pov = $validacija->validate();
        if($pov !== true) {
            $message = $validacija->decypherErrors($pov);
            $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayMediji"
            )) . "?msg=excep");
        }
        
        try {
            $medij->addRow(post("nazivMedija", null));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayMediji"
            )) . '?msg=succa');
        } catch (\PDOException $e){
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayMediji"
            )) . "?msg=excep");
        }
    }
    
    public function modifyMedij() {
	$this->checkRole();
        
        $medij = new \model\DBMedij();
        $validacija = new \model\formModel\MedijFormModel(array('nazivMedija' => post("nazivMedija")));
        $pov = $validacija->validate();
        if($pov !== true) {
            $message = $validacija->decypherErrors($pov);
            $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayMediji"
            )) . "?msg=excep");
        }
        try {
            $medij->modifyRow(post($medij->getPrimaryKeyColumn(), null), post('nazivMedija', null));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayMediji"
            )) . '?msg=succm');
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayMediji"
            )) . "?msg=excep");
        }
    }
    
    public function deleteMedij() {
	$this->checkRole();
        
        $this->idCheck("displayMediji");
	
        $medij = new \model\DBMedij();
        try {
            $medij->deleteRow(get("id"));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayMediji"
            )) . '?msg=succd');
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayMediji"
            )) . "?msg=excep");
        }
    }
    
    /**
     * Shows posts about faculty competitors success on current Elektrijada
     */
    public function displayActiveObjava() {
	$this->checkRole();
	$this->checkMessages();
	
	$objavaOElektrijadi = new \model\DBObjavaOElektrijadi();
	$objave = array();
        try {
	    $elektrijada = new \model\DBElektrijada();
	    $idElektrijade = $elektrijada->getCurrentElektrijadaId();
            $objave = $objavaOElektrijadi->getAllActive($idElektrijade);
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $this->errorMessage = $handler;
        }

        echo new \view\Main(array(
            "body" => new \view\ozsn\ActiveObjavaList(array(
                "errorMessage" => $this->errorMessage,
                "resultMessage" => $this->resultMessage,
		"objave" => $objave
            )),
            "title" => "Aktualne objave"
        ));
    }
    
    /**
     * Displays all newspaper reports
     */
    public function displayObjava() {
	$this->checkRole();
	$this->checkMessages();
	
	$objava = new \model\DBObjava();
	$objave = array();
        try {
	    $objave = $objava->getAll();
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $this->errorMessage = $handler;
        }

        echo new \view\Main(array(
            "body" => new \view\ozsn\ActiveObjavaList(array(
                "errorMessage" => $this->errorMessage,
                "resultMessage" => $this->resultMessage,
		"objave" => $objave
            )),
            "title" => "Objave"
        ));
    }
    
    public function deleteActiveObjava() {
	$this->checkRole();
        
        $this->idCheck("displayActiveObjava");
	
        $objavaOElektrijadi = new \model\DBObjavaOElektrijadi();
	$objava = new \model\DBObjava();
        try {
	    $pov = $objavaOElektrijadi->deleteRow(get("id"));
	    
	    if ($pov !== false) {
		// delete objava also
		$objava->deleteRow($pov);
	    }
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayActiveObjava"
            )) . '?msg=succd');
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayActiveObjava"
            )) . "?msg=excep");
        }
    }
    
    /**
     * Adds new row to table objava and if necessary rows in the table objavaoelektrijadi
     */
    public function addObjava() {
	$this->checkRole();
	$this->checkMessages();
	
	$objava = new \model\DBObjava();
	$objavaOElektrijadi = new \model\DBObjavaOElektrijadi();
	$medij = new \model\DBMedij();
	$mediji = $medij->getAll();
	$elektrijada = new \model\DBElektrijada();
	$elektrijade = $elektrijada->getAll();
	
	if (postEmpty() && files("tmp_name", "datoteka") !== false) {
	    $handler = new \model\ExceptionHandlerModel(new \PDOException(), "Da biste dodali datoteku, morate unijeti podatke o objavi!");
	    $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "addObjava"
            )) . "?msg=excep");
	}
	
	if (!postEmpty()) {
	    try {
		$validacija = new \model\formModel\ObjavaFormModel(array('datumObjave' => post("datumObjave"),
									'autorIme' => post("autorIme"),
									'autorPrezime' => post("autorPrezime"),
									'link' => post("link"),
									'idMedija' => post("idMedija")));
		$pov = $validacija->validate();
		if ($pov !== true) {
		    $message = $validacija->decypherErrors($pov);
		    $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
		    $_SESSION["exception"] = serialize($handler);
		    preusmjeri(\route\Route::get('d3')->generate(array(
			"controller" => "ozsn",
			"action" => "addObjava"
		    )) . "?msg=excep");
		}
		
		// check if atleast one elektrijada is chosen
		if (false === post("idElektrijade") || count(post("idElektrijade")) === 0) {
		    $handler = new \model\ExceptionHandlerModel(new \PDOException(), "Morate odabrati barem jednu elektrijadu!");
		    $_SESSION["exception"] = serialize($handler);
		    preusmjeri(\route\Route::get('d3')->generate(array(
			"controller" => "ozsn",
			"action" => "addObjava"
		    )) . "?msg=excep");
		}
		
		// data checked and ok
		$objava->addRow(post("datumObjave", null), post("link", null), post("autorIme", null),
			post("autorPrezime", null), post("idMedija", null), NULL);
		$idObjave = $objava->getPrimaryKey();
		
		// now lets save the connections to the elektrijada competitions
		foreach (post("idElektrijade") as $k => $v) {
		    $objavaOElektrijadi->addRow($idObjave, $v);
		}
		
		// now i check the uploaded file
		if (files("tmp_name", "datoteka") !== false) {
		    if(!is_uploaded_file(files("tmp_name", "datoteka"))) {
			$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Morate poslati datoteku!");
			$_SESSION["exception"] = serialize($handler);
			preusmjeri(\route\Route::get('d3')->generate(array(
			    "controller" => "ozsn",
			    "action" => "addObjava"
			)) . "?msg=excep");
		    }
		    
		    $putanja = "./medij_dokumenti/" . date("Y_m_d_H_i_s") . "_" . $idObjave . "_" . basename(files("name", "datoteka"));
		    if (move_uploaded_file(files("tmp_name", "datoteka"), $putanja)) {
			// add path to db
			$objava->addFile($idObjave, $putanja);			
		    } else {
			$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Dogodio se problem s spremanjem datoteke! Podaci o objavi su uneseni!");
			$_SESSION["exception"] = serialize($handler);
			preusmjeri(\route\Route::get('d3')->generate(array(
			    "controller" => "ozsn",
			    "action" => "addObjava"
			)) . "?msg=excep");
		    }
		}
		
		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "ozsn",
		    "action" => "displayObjava"
		)) . "?msg=succa");
	    } catch (\PDOException $e) {
		$handler = new \model\ExceptionHandlerModel($e);
		$_SESSION["exception"] = serialize($handler);
		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "ozsn",
		    "action" => "addObjava"
		)) . "?msg=excep");
	    } 
	}
	
	echo new \view\Main(array(
	    "body" => new \view\ozsn\ObjavaAdding(array(
		"errorMessage" => $this->errorMessage,
		"resultMessage" => $this->resultMessage,
		"mediji" => $mediji,
		"elektrijade" => $elektrijade
	    )),
	    "title" => "Dodavanje Objave"
	));
    }
    
    public function modifyObjava() {
	$this->checkRole();
	$this->checkMessages();
	
	$medij = new \model\DBMedij();
	$mediji = $medij->getAll();
	$elektrijada = new \model\DBElektrijada();
	$elektrijade = $elektrijada->getAll();
	$objavaOElektrijadi = new \model\DBObjavaOElektrijadi();
	$objava = new \model\DBObjava();
	$objaveOElektrijadi = array();
	
	$this->idCheck("displayObjava");
	
	// get needed display data
	try {
	    $objava->load(get("id"));
	    $objaveOElektrijadi = $objavaOElektrijadi->getAllByObjava($objava->getPrimaryKey());
	    
	} catch (\app\model\NotFoundException $e) {
	    $handler = new \model\ExceptionHandlerModel(new \PDOException(), "Nepoznati identifikator!");
	    $_SESSION["exception"] = serialize($handler);
	    preusmjeri(\route\Route::get('d3')->generate(array(
		"controller" => "ozsn",
		"action" => "displayObjava"
	    )) . "?msg=excep");
	} catch (\PDOException $e) {
	    $handler = new \model\ExceptionHandlerModel($e);
	    $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayObjava"
            )) . "?msg=excep");
	}
	
	if (!postEmpty()) {
	    try {
		$validacija = new \model\formModel\ObjavaFormModel(array('datumObjave' => post("datumObjave"),
									'autorIme' => post("autorIme"),
									'autorPrezime' => post("autorPrezime"),
									'link' => post("link"),
									'idMedija' => post("idMedija")));
		$pov = $validacija->validate();
		if ($pov !== true) {
		    $message = $validacija->decypherErrors($pov);
		    $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
		    $_SESSION["exception"] = serialize($handler);
		    preusmjeri(\route\Route::get('d3')->generate(array(
			"controller" => "ozsn",
			"action" => "modifyObjava"
		    )) . "?msg=excep");
		}
		
		// check if atleast one elektrijada is chosen
		if (false === post("idElektrijade") || count(post("idElektrijade")) === 0) {
		    $handler = new \model\ExceptionHandlerModel(new \PDOException(), "Morate odabrati barem jednu elektrijadu!");
		    $_SESSION["exception"] = serialize($handler);
		    preusmjeri(\route\Route::get('d3')->generate(array(
			"controller" => "ozsn",
			"action" => "modifyObjava"
		    )) . "?msg=excep");
		}
		
		// data checked and ok
		$idObjave = $objava->getPrimaryKey();
		$objava->modifyRow($idObjave, post("datumObjave", null), post("link", null), post("autorIme", null),
			post("autorPrezime", null), post("idMedija", null), NULL);
		
		// first delete all old rows from objavaoelektrijadi
		$objavaOElektrijadi->deleteRowsByObjava($idObjave);
		
		// add new rows
		foreach (post("idElektrijade") as $k => $v) {
		    $objavaOElektrijadi->addRow($idObjave, $v);
		}
		
		// now i check the file
		if (files("tmp_name", "datoteka") !== false) {
		    if(!is_uploaded_file(files("tmp_name", "datoteka"))) {
			$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Morate poslati datoteku!");
			$_SESSION["exception"] = serialize($handler);
			preusmjeri(\route\Route::get('d3')->generate(array(
			    "controller" => "ozsn",
			    "action" => "modifyObjava"
			)) . "?msg=excep&id=" . get("id"));
		    }
		    
		    // save file over the old one if there was any
		    $putanja = "./medij_dokumenti/" . date("Y_m_d_H_i_s") . "_" . $idObjave . "_" . basename(files("name", "datoteka"));
		    if (move_uploaded_file(files("tmp_name", "datoteka"), $putanja)) {
			// add path to db
			if ($objava->datoteka != NULL) {
			    $p = unlink($objava->datoteka);
			    if ($p === false) {
				$e = new \PDOException();
				$e->errorInfo[0] = '02000';
				$e->errorInfo[1] = 1604;
				$e->errorInfo[2] = "Greška prilikom brisanja datoteke!";
				$objava->addFile($idObjave, NULL);
				throw $e;
			    }
			}
			    
			$objava->addFile($idObjave, $putanja);			
		    } else {
			$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Dogodio se problem s spremanjem datoteke! Podaci o objavi su uneseni!");
			$_SESSION["exception"] = serialize($handler);
			preusmjeri(\route\Route::get('d3')->generate(array(
			    "controller" => "ozsn",
			    "action" => "modifyObjava"
			)) . "?msg=excep&id=" . get("id"));
		    }
		} else {
		    // check if he wants to delete the old one
		    if (post("delete") !== false) {
			$p = unlink($objava->datoteka);
			if ($p === false) {
			    $e = new \PDOException();
			    $e->errorInfo[0] = '02000';
			    $e->errorInfo[1] = 1604;
			    $e->errorInfo[2] = "Greška prilikom brisanja datoteke!";
			    $objava->addFile($idObjave, NULL);
			    throw $e;
			}
			$objava->addFile($idObjave, NULL);	// delete path from db
		    }
		}
		
		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "ozsn",
		    "action" => "displayObjava"
		)) . "?msg=succm");
	    } catch (\PDOException $e) {
		$handler = new \model\ExceptionHandlerModel($e);
		$_SESSION["exception"] = serialize($handler);
		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "ozsn",
		    "action" => "modifyObjava"
		)) . "?msg=excep&id=" . get("id"));
	    } 
	}
	
	echo new \view\Main(array(
	    "body" => new \view\ozsn\ObjavaModification(array(
		"errorMessage" => $this->errorMessage,
		"resultMessage" => $this->resultMessage,
		"mediji" => $mediji,
		"elektrijade" => $elektrijade,
		"objaveOElektrijadi" => $objaveOElektrijadi,
		"objava" => $objava
	    )),
	    "title" => "Mijenjanje Objave"
	));
    }
    
    /**
     * Deletes report and all data from table objavaoelektrijadi (By FOREIGN KEY CONSTRAINT)
     */
    public function deleteObjava() {
	$this->checkRole();
        
        $this->idCheck("displayObjava");
	
	$objava = new \model\DBObjava();
        try {
	    $objava->deleteRow(get("id"));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayObjava"
            )) . '?msg=succd');
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayObjava"
            )) . "?msg=excep");
        }
    }
	    
    /**
     * Displays all promotion types in database
     */
    public function displayNacinPromocije() {
        $this->checkRole();
        $this->checkMessages();
        
        $nacin = new \model\DBNacinPromocije();
	$nacini = null;
        try {
            $nacini = $nacin->getAll();
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $this->errorMessage = $handler;
        }

        echo new \view\Main(array(
            "body" => new \view\ozsn\NacinPromocijeList(array(
                "errorMessage" => $this->errorMessage,
                "resultMessage" => $this->resultMessage,
                "nacini" => $nacini
            )),
            "title" => "Načini Promocije",
			"script" => new \view\scripts\ozsn\NacinPromocijeListJs()
        ));
    }
    
    /**
     * Inserts new data into database via post request
     */
    public function addNacinPromocije() {
        $this->checkRole();

        $nacin = new \model\DBNacinPromocije();
        $validacija = new \model\formModel\NacinPromocijeFormModel(array('tipPromocije' => post("tipPromocije")));
        $pov = $validacija->validate();
        if($pov !== true) {
            $message = $validacija->decypherErrors($pov);
            $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayNacinPromocije"
            )) . "?msg=excep");
        }
        
        try {
            $nacin->addRow(post("tipPromocije", null));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayNacinPromocije"
            )) . '?msg=succa');
        } catch (\PDOException $e){
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayNacinPromocije"
            )) . "?msg=excep");
        }
        
    }
    
    /**
     * Modifies promotion type data via post request
     */
    public function modifyNacinPromocije() {
        $this->checkRole();
        
        $nacin = new \model\DBNacinPromocije();
        $validacija = new \model\formModel\NacinPromocijeFormModel(array('tipPromocije' => post("tipPromocije")));
        $pov = $validacija->validate();
        if($pov !== true) {
            $message = $validacija->decypherErrors($pov);
            $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayNacinPromocije"
            )) . "?msg=excep");
        }
        try {
            $nacin->modifyRow(post($nacin->getPrimaryKeyColumn(), null), post('tipPromocije', null));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayNacinPromocije"
            )) . '?msg=succm');
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayNacinPromocije"
            )) . "?msg=excep");
        }
    }
    
    /**
     * Deletes promotion type via get request
     */
    public function deleteNacinPromocije() {
        $this->checkRole();
        
        $this->idCheck("displayNacinPromocije");
	
        $nacin = new \model\DBNacinPromocije();
        try {
            $nacin->deleteRow(get("id"));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayNacinPromocije"
            )) . '?msg=succd');
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayNacinPromocije"
            )) . "?msg=excep");
        }
    }
    
    /**
     * Displays all sponsor categories in database
     */
    public function displayKategorija() {
        $this->checkRole();
        $this->checkMessages();
        
        $kategorija = new \model\DBKategorija();
	$kategorije = null;
        try {
            $kategorije = $kategorija->getAll();
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $this->errorMessage = $handler;
        }

        echo new \view\Main(array(
            "body" => new \view\ozsn\KategorijaList(array(
                "errorMessage" => $this->errorMessage,
                "resultMessage" => $this->resultMessage,
                "kategorije" => $kategorije
            )),
            "title" => "Kategorije Sponzora",
			"script" => new \view\scripts\ozsn\KategorijaListJs()
        ));
    }
    
    /**
     * Inserts new data into database via post request
     */
    public function addKategorija() {
        $this->checkRole();

        $kategorija = new \model\DBKategorija();
        $validacija = new \model\formModel\KategorijaFormModel(array('tipKategorijeSponzora' => post("tipKategorijeSponzora")));
        $pov = $validacija->validate();
        if($pov !== true) {
            $message = $validacija->decypherErrors($pov);
            $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayKategorija"
            )) . "?msg=excep");
        }
        
        try {
            $kategorija->addRow(post("tipKategorijeSponzora", null));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayKategorija"
            )) . '?msg=succa');
        } catch (\PDOException $e){
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayKategorija"
            )) . "?msg=excep");
        }
        
    }
    
    /**
     * Modifies sponsor category data via post request
     */
    public function modifyKategorija() {
        $this->checkRole();
        
        $kategorija = new \model\DBKategorija();
        $validacija = new \model\formModel\NacinPromocijeFormModel(array('tipKategorijeSponzora' => post("tipKategorijeSponzora")));
        $pov = $validacija->validate();
        if($pov !== true) {
            $message = $validacija->decypherErrors($pov);
            $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayKategorija"
            )) . "?msg=excep");
        }
        try {
            $kategorija->modifyRow(post($kategorija->getPrimaryKeyColumn(), null), post('tipKategorijeSponzora', null));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayKategorija"
            )) . '?msg=succm');
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayKategorija"
            )) . "?msg=excep");
        }
    }
    
    /**
     * Deletes sponsor category via get request
     */
    public function deleteKategorija() {
        $this->checkRole();
        
        $this->idCheck("displayKategorija");
	
        $kategorija = new \model\DBKategorija();
        try {
            $kategorija->deleteRow(get("id"));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayKategorija"
            )) . '?msg=succd');
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayKategorija"
            )) . "?msg=excep");
        }
    }
    
    /**
     * Displays all sponsors
     */
    public function displaySponzor() {
	$this->checkRole();
        $this->checkMessages();
        
        $sponzor = new \model\DBSponzor();
	$sponzori = null;
        try {
            $sponzori = $sponzor->getAll();
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $this->errorMessage = $handler;
        }

        echo new \view\Main(array(
            "body" => new \view\ozsn\SponzorList(array(
                "errorMessage" => $this->errorMessage,
                "resultMessage" => $this->resultMessage,
                "sponzori" => $sponzori
            )),
            "title" => "Sponzori",
			"script" => new \view\scripts\ozsn\SponzorListJs()
        ));
    }
    
    /**
     * Display sponzors for current Elektrijada (who have sponsored the whole competition, not only some areas of it)
     */
    public function displayActiveSponzor() {
	$this->checkRole();
        $this->checkMessages();
        
        $sponzor = new \model\DBSponzor();
	$sponzori = null;
        try {
	    $elektrijada = new \model\DBElektrijada();
	    $id = $elektrijada->getCurrentElektrijadaId();
            $sponzori = $sponzor->getAllActive($id);
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $this->errorMessage = $handler;
        }

        echo new \view\Main(array(
            "body" => new \view\ozsn\ActiveSponzorList(array(
                "errorMessage" => $this->errorMessage,
                "resultMessage" => $this->resultMessage,
                "sponzori" => $sponzori
            )),
            "title" => "Ovogodišnji Sponzori",
			"script" => new \view\scripts\ozsn\ActiveSponzorListJs()
        ));
    }
    
    /**
     * Display sponzors for current Elektrijada (who have sponsored some competition areas)
     */
    public function displayAreaSponzor() {
	$this->checkRole();
        $this->checkMessages();
        
        $sponzor = new \model\DBSponzor();
	$sponzori = null;
        try {
	    $elektrijada = new \model\DBElektrijada();
	    $id = $elektrijada->getCurrentElektrijadaId();
            $sponzori = $sponzor->getAllArea($id);
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $this->errorMessage = $handler;
        }

        echo new \view\Main(array(
            "body" => new \view\ozsn\AreaSponzorList(array(
                "errorMessage" => $this->errorMessage,
                "resultMessage" => $this->resultMessage,
                "sponzori" => $sponzori
            )),
            "title" => "Sponzori Područja",
			"script" => new \view\scripts\ozsn\AreaSponzorListJs()
        ));
    }
    
    /**
     * Adds a new sponsor and sponsorship data for current Elektrijada (if any)
     */
    public function addSponzor() {
	$this->checkRole();
	$this->checkMessages();
	
	$kategorija = new \model\DBKategorija();
	$promocija = new \model\DBNacinPromocije();
	$kategorije = null;
	$promocije = null;
	
	try {
	    $kategorije = $kategorija->getAll();
	    $promocije = $promocija->getAll();
	} catch (\PDOException $e) {
	    $handler = new \model\ExceptionHandlerModel($e);
	    $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displaySponzor"
            )) . "?msg=excep");
	}
	
	if (postEmpty() && files("tmp_name", "datoteka") !== false) {
	    $handler = new \model\ExceptionHandlerModel(new \PDOException(), "Da biste dodali logotip, morate unijeti i podatke o sponzoru!");
	    $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "addSponzor"
            )) . "?msg=excep");
	}
	
	if (!postEmpty()) {
	    try {
		// first do the db work and after that the image work
		$sponzor = new \model\DBSponzor();
		$imaSponzora = new \model\DBImaSponzora();
		
		$validacija = new \model\formModel\SponzorFormModel(array("imeTvrtke" => post("imeTvrtke"),
									"adresaTvrtke" => post("adresaTvrtke"),
									"iznosDonacije" => post("iznosDonacije"),
									"napomena" => post("napomena")));
		$pov = $validacija->validate();
		if ($pov !== true) {
		    $message = $validacija->decypherErrors($pov);
		    $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
		    $_SESSION["exception"] = serialize($handler);
		    preusmjeri(\route\Route::get('d3')->generate(array(
			"controller" => "ozsn",
			"action" => "addSponzor"
		    )) . "?msg=excep");
		}
		
		// data checked and ok
		$sponzor->addRow(post("imeTvrtke"), post("adresaTvrtke"), NULL);
		$idSponzora = $sponzor->getPrimaryKey();
		
		if (post("iznosDonacije") !== false && post("valutaDonacije") !== false) {
		    $elektrijada = new \model\DBElektrijada();
		    $i = $elektrijada->getCurrentElektrijadaId();
		    $imaSponzora->addRow($idSponzora, post("idKategorijeSponzora", null), post("idPromocije", null), $i, 
			    post("iznosDonacije", null), post("valutaDonacije", null), post("napomena", null));
		}
		
		// now i check the image
		if (files("tmp_name", "datoteka") !== false) {
		    if(!is_uploaded_file(files("tmp_name", "datoteka"))) {
			$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Morate poslati datoteku!");
			$_SESSION["exception"] = serialize($handler);
			preusmjeri(\route\Route::get('d3')->generate(array(
			    "controller" => "ozsn",
			    "action" => "addSponzor"
			)) . "?msg=excep");
		    }
		    // save resized image
		    $putanja = "./logotip/" . date("Y_m_d_H_i_s") . "_" . basename(files("name", "datoteka"));
		    if (move_uploaded_file(files("tmp_name", "datoteka"), $putanja)) {
			// add path to db
			$sponzor->addLogo($idSponzora, $putanja);			
		    } else {
			$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Dogodio se problem s spremanjem datoteke! Podaci o sponzoru su uneseni!");
			$_SESSION["exception"] = serialize($handler);
			preusmjeri(\route\Route::get('d3')->generate(array(
			    "controller" => "ozsn",
			    "action" => "addSponzor"
			)) . "?msg=excep");
		    }
		}
		
		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "ozsn",
		    "action" => "displaySponzor"
		)) . "?msg=succa");
	    } catch (\PDOException $e) {
		$handler = new \model\ExceptionHandlerModel($e);
		$_SESSION["exception"] = serialize($handler);
		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "ozsn",
		    "action" => "addSponzor"
		)) . "?msg=excep");
	    } 
	}
	
	echo new \view\Main(array(
	    "body" => new \view\ozsn\SponzorAdding(array(
		"errorMessage" => $this->errorMessage,
		"resultMessage" => $this->resultMessage,
		"kategorije" => $kategorije,
		"promocije" => $promocije
	    )),
	    "title" => "Dodavanje Sponzora"
	));
    }
    
    public function addAreaSponzor() {
	$this->checkRole();
	$this->checkMessages();
	
	$podrucje = new \model\DBPodrucje();
	$sponzor = new \model\DBSponzor();
	
	$podrucja = null;
	$sponzori = null;
	
	try {
	    $podrucja = $podrucje->getAll();
	    $sponzori = $sponzor->getAll();
	} catch (\PDOException $e) {
	    $handler = new \model\ExceptionHandlerModel($e);
	    $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayAreaSponzor"
            )) . "?msg=excep");
	}
	
	if (!postEmpty()) {
	    try {
		// first do the db work
		$validacija = new \model\formModel\AreaSponzorFormModel(array("iznosDonacije" => post("iznosDonacije"),
									"napomena" => post("napomena"),
									"idSponzora" => post("idSponzora"),
									"idPodrucja" => post("idPodrucja")));
		$pov = $validacija->validate();
		if ($pov !== true) {
		    $message = $validacija->decypherErrors($pov);
		    $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
		    $_SESSION["exception"] = serialize($handler);
		    preusmjeri(\route\Route::get('d3')->generate(array(
			"controller" => "ozsn",
			"action" => "addAreaSponzor"
		    )) . "?msg=excep");
		}
		
		// data checked and ok
		$sponElek = new \model\DBSponElekPod();
		
		if (post("iznosDonacije") !== false && post("valutaDonacije") !== false) {
		    $elektrijada = new \model\DBElektrijada();
		    $i = $elektrijada->getCurrentElektrijadaId();
		    $sponElek->addRow(post("idSponzora", null), post("idPodrucja", null), $i, 
			    post("iznosDonacije", null), post("valutaDonacije", null), post("napomena", null));
		}

		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "ozsn",
		    "action" => "displayAreaSponzor"
		)) . "?msg=succa");
	    } catch (\PDOException $e) {
		$handler = new \model\ExceptionHandlerModel($e);
		$_SESSION["exception"] = serialize($handler);
		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "ozsn",
		    "action" => "addAreaSponzor"
		)) . "?msg=excep");
	    } 
	}

	echo new \view\Main(array(
	    "body" => new \view\ozsn\AreaSponzorAdding(array(
		"errorMessage" => $this->errorMessage,
		"resultMessage" => $this->resultMessage,
		"sponzori" => $sponzori,
		"podrucja" => $podrucja
	    )),
	    "title" => "Pojedinačne Donacije"
	));
    }
    
    public function displaySponzorsByElektrijada() {
	$this->checkRole();
	$this->checkMessages();
	
	$e = new \model\DBElektrijada();
	$elektrijade = array();
	$sponzori = null;
	
	try {
	    if (postEmpty()) {
		$elektrijade = $e->getAll();
	    } else {
		$sponzor = new \model\DBSponzor();
		$sponzori = $sponzor->getAllByElektrijada(post("idElektrijade"));
	    }
	} catch (\PDOException $e) {
	    $sponzori = null;
	    $handler = new \model\ExceptionHandlerModel($e);
            $this->errorMessage = $handler;
	}
	
	echo new \view\Main(array(
	    "title" => "Pregled Sponzora",
	    "body" => new \view\ozsn\SponsorsByElektrijadaList(array(
		"resultMessage" => $this->resultMessage,
		"errorMessage" => $this->errorMessage,
		"sponzori" => $sponzori,
		"elektrijade" => $elektrijade
	    ))
	));
	
    }
    
    /**
     * Modifies sponsor data and sponsorship data if given. The logo is overwritten if given
     */
    public function modifySponzor() {
	$this->checkRole();
	$this->checkMessages();
	
	$kategorija = new \model\DBKategorija();
	$promocija = new \model\DBNacinPromocije();
	$sponzor = new \model\DBSponzor();
	$imaSponzora = new \model\DBImaSponzora();
	$kategorije = null;
	$promocije = null;
	
	$this->idCheck("displaySponzor");
	
	// get needed display data
	try {
	    $kategorije = $kategorija->getAll();
	    $promocije = $promocija->getAll();
	    $sponzor->load(get("id"));
	    $elektrijada = new \model\DBElektrijada();
	    $i = $elektrijada->getCurrentElektrijadaId();
	    $imaSponzora->loadRow($sponzor->getPrimaryKey(), $i);
	    if ($imaSponzora->getPrimaryKey() !== null) {
		$kategorija = $kategorija->loadIfExists($imaSponzora->idKategorijeSponzora);
		$promocija = $promocija->loadIfExists($imaSponzora->idPromocije);
	    } else {
		$kategorija = null;
		$promocija = null;
	    }
	} catch (\app\model\NotFoundException $e) {
	    $handler = new \model\ExceptionHandlerModel(new \PDOException(), "Nepoznati identifikator!");
	    $_SESSION["exception"] = serialize($handler);
	    preusmjeri(\route\Route::get('d3')->generate(array(
		"controller" => "ozsn",
		"action" => "displaySponzor"
	    )) . "?msg=excep");
	} catch (\PDOException $e) {
	    $handler = new \model\ExceptionHandlerModel($e);
	    $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displaySponzor"
            )) . "?msg=excep");
	}
	
	if (!postEmpty()) {
	    try {
		// first do the db work and after that the image work
		
		$validacija = new \model\formModel\SponzorFormModel(array("imeTvrtke" => post("imeTvrtke"),
									"adresaTvrtke" => post("adresaTvrtke"),
									"iznosDonacije" => post("iznosDonacije"),
									"napomena" => post("napomena")));
		$pov = $validacija->validate();
		if ($pov !== true) {
		    $message = $validacija->decypherErrors($pov);
		    $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
		    $_SESSION["exception"] = serialize($handler);
		    preusmjeri(\route\Route::get('d3')->generate(array(
			"controller" => "ozsn",
			"action" => "modifySponzor"
		    )) . "?msg=excep&id=" . get("id"));
		}
		
		// data checked and ok
		$idSponzora = $sponzor->getPrimaryKey();
		$sponzor->modifyRow($idSponzora, post("imeTvrtke"), post("adresaTvrtke"), NULL);
		
		if (post("iznosDonacije") !== false && post("valutaDonacije") !== false) {
		    $elektrijada = new \model\DBElektrijada();
		    $i = $elektrijada->getCurrentElektrijadaId();
		    if ($imaSponzora->getPrimaryKey() !== null) {
			$imaSponzora->modifyRow($imaSponzora->getPrimaryKey(), $idSponzora, post("idKategorijeSponzora", null), post("idPromocije", null), $i, 
				post("iznosDonacije", null), post("valutaDonacije", null), post("napomena", null));
		    } else {
			// completely new row
			$imaSponzora->addRow($idSponzora, post("idKategorijeSponzora", null), post("idPromocije", null), $i, 
			    post("iznosDonacije", null), post("valutaDonacije", null), post("napomena", null));
		    }
		}
		
		// now i check the image
		if (files("tmp_name", "datoteka") !== false) {
		    if(!is_uploaded_file(files("tmp_name", "datoteka"))) {
			$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Morate poslati datoteku!");
			$_SESSION["exception"] = serialize($handler);
			preusmjeri(\route\Route::get('d3')->generate(array(
			    "controller" => "ozsn",
			    "action" => "modifySponzor"
			)) . "?msg=excep&id=" . get("id"));
		    }
		    // save image over the old one if there was any
		    $putanja = "./logotip/" . date("Y_m_d_H_i_s") . "_" . basename(files("name", "datoteka"));
		    if (move_uploaded_file(files("tmp_name", "datoteka"), $putanja)) {
			// add path to db
			if ($sponzor->logotip != NULL) {
			    $p = unlink($sponzor->logotip);
			    if ($p === false) {
				$e = new \PDOException();
				$e->errorInfo[0] = '02000';
				$e->errorInfo[1] = 1604;
				$e->errorInfo[2] = "Greška prilikom brisanja logotipa!";
				$sponzor->addLogo($idSponzora, NULL);
				throw $e;
			    }
			}
			    
			$sponzor->addLogo($idSponzora, $putanja);			
		    } else {
			$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Dogodio se problem s spremanjem datoteke! Podaci o sponzoru su uneseni!");
			$_SESSION["exception"] = serialize($handler);
			preusmjeri(\route\Route::get('d3')->generate(array(
			    "controller" => "ozsn",
			    "action" => "modifySponzor"
			)) . "?msg=excep&id=" . get("id"));
		    }
		} else {
		    // check if he wants to delete the old one
		    if (post("delete") !== false) {
			$p = unlink($sponzor->logotip);
			if ($p === false) {
			    $e = new \PDOException();
			    $e->errorInfo[0] = '02000';
			    $e->errorInfo[1] = 1604;
			    $e->errorInfo[2] = "Greška prilikom brisanja logotipa!";
			    $sponzor->addLogo($sponzor->getPrimaryKey(), NULL);
			    throw $e;
			}
			$sponzor->addLogo($sponzor->getPrimaryKey(), NULL);	// delete path from db
		    }
		}
		
		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "ozsn",
		    "action" => "displaySponzor"
		)) . "?msg=succm");
	    } catch (\PDOException $e) {
		$handler = new \model\ExceptionHandlerModel($e);
		$_SESSION["exception"] = serialize($handler);
		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "ozsn",
		    "action" => "modifySponzor"
		)) . "?msg=excep&id=" . get("id"));
	    } 
	}
	
	echo new \view\Main(array(
	    "body" => new \view\ozsn\SponzorModification(array(
		"errorMessage" => $this->errorMessage,
		"resultMessage" => $this->resultMessage,
		"kategorije" => $kategorije,
		"promocije" => $promocije,
		"sponzor" => $sponzor,
		"imasponzora" => $imaSponzora,
		"kategorija" => $kategorija,
		"promocija" => $promocija
	    )),
	    "title" => "Mijenjanje Sponzora"
	));
    }
    
    public function downloadLogo() {
		$this->checkRole();
		$this->checkMessages();

		if (count($_GET) === 0 || get("id") === false) {
			$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Nepoznati sponzor!");
			$_SESSION["exception"] = serialize($handler);
			preusmjeri(\route\Route::get('d1')->generate() . "?msg=excep");
		}

		$sponzor = new \model\DBSponzor();
		try {
			$sponzor->load(get("id"));
		} catch (\app\model\NotFoundException $e) {
			$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Nepoznati sponzor!");
			$_SESSION["exception"] = serialize($handler);
			preusmjeri(\route\Route::get('d1')->generate() . "?msg=excep");
		} catch (\PDOException $e) {
			$handler = new \model\ExceptionHandlerModel($e);
			$_SESSION["exception"] = serialize($handler);
			preusmjeri(\route\Route::get('d1')->generate() . "?msg=excep");
		}

		echo new \view\Download(array(
			"path" => $sponzor->logotip
		));
    }
    
    /**
     * Modifies data from SponElekPod table
     */
    public function modifyActiveSponzor() {
	$this->checkRole();
	$this->checkMessages();
	
	$kategorija = new \model\DBKategorija();
	$promocija = new \model\DBNacinPromocije();
	$sponzor = new \model\DBSponzor();
	$imaSponzora = new \model\DBImaSponzora();
	$kategorije = null;
	$promocije = null;
	
	$this->idCheck("displayActiveSponzor");
	
	// get needed display data
	try {
	    $kategorije = $kategorija->getAll();
	    $promocije = $promocija->getAll();
	    $sponzor->load(get("id"));
	    $elektrijada = new \model\DBElektrijada();
	    $i = $elektrijada->getCurrentElektrijadaId();
	    $imaSponzora->loadRow($sponzor->getPrimaryKey(), $i);
	    if ($imaSponzora->getPrimaryKey() !== null) {
		$kategorija = $kategorija->loadIfExists($imaSponzora->idKategorijeSponzora);
		$promocija = $promocija->loadIfExists($imaSponzora->idPromocije);
	    } else {
		$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Nepoznati sponzor!");
		$_SESSION["exception"] = serialize($handler);
		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "ozsn",
		    "action" => "displayActiveSponzor"
		)) . "?msg=excep");
	    }
	} catch (\app\model\NotFoundException $e) {
	    $handler = new \model\ExceptionHandlerModel(new \PDOException(), "Nepoznati identifikator!");
	    $_SESSION["exception"] = serialize($handler);
	    preusmjeri(\route\Route::get('d3')->generate(array(
		"controller" => "ozsn",
		"action" => "displayActiveSponzor"
	    )) . "?msg=excep");
	} catch (\PDOException $e) {
	    $handler = new \model\ExceptionHandlerModel($e);
	    $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayActiveSponzor"
            )) . "?msg=excep");
	}
	
	
	if (!postEmpty()) {
	    try {
		// first do the db work and after that the image work
		
		$validacija = new \model\formModel\ActiveSponzorFormModel(array("iznosDonacije" => post("iznosDonacije"),
									"napomena" => post("napomena")));
		$pov = $validacija->validate();
		if ($pov !== true) {
		    $message = $validacija->decypherErrors($pov);
		    $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
		    $_SESSION["exception"] = serialize($handler);
		    preusmjeri(\route\Route::get('d3')->generate(array(
			"controller" => "ozsn",
			"action" => "modifyActiveSponzor"
		    )) . "?msg=excep&id=" . get("id"));
		}
		
		// data checked and ok
		if (post("iznosDonacije") !== false && post("valutaDonacije") !== false) {
		    $elektrijada = new \model\DBElektrijada();
		    $i = $elektrijada->getCurrentElektrijadaId();
		    $imaSponzora->modifyRow($imaSponzora->getPrimaryKey(), $sponzor->getPrimaryKey(), post("idKategorijeSponzora" , null), post("idPromocije", null),
			    $i, post("iznosDonacije", null), post("valutaDonacije", null), post("napomena", null));
		}
		
		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "ozsn",
		    "action" => "displayActiveSponzor"
		)) . "?msg=succm");
	    } catch (\PDOException $e) {
		$handler = new \model\ExceptionHandlerModel($e);
		$_SESSION["exception"] = serialize($handler);
		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "ozsn",
		    "action" => "modifyActiveSponzor"
		)) . "?msg=excep&id=" . get("id"));
	    }
	}
	
	echo new \view\Main(array(
	    "body" => new \view\ozsn\ActiveSponzorModification(array(
		"errorMessage" => $this->errorMessage,
		"resultMessage" => $this->resultMessage,
		"kategorije" => $kategorije,
		"promocije" => $promocije,
		"sponzor" => $sponzor,
		"imasponzora" => $imaSponzora,
		"kategorija" => $kategorija,
		"promocija" => $promocija
	    )),
	    "title" => "Mijenjanje Ovogodišnjeg Sponzora"
	));
    }
    
    public function modifyAreaSponzor() {
	$this->checkRole();
	$this->checkMessages();
	
	$podrucje = new \model\DBPodrucje();
	$podrucja = null;
	$sponElekPod = new \model\DBSponElekPod();
	
	$this->idCheck("displayAreaSponzor");
	// get needed display data
	try {
	    $podrucja = $podrucje->getAll();
	    $sponElekPod->load(get("id"));
	} catch (\app\model\NotFoundException $e) {
	    $handler = new \model\ExceptionHandlerModel(new \PDOException(), "Nepoznati identifikator!");
	    $_SESSION["exception"] = serialize($handler);
	    preusmjeri(\route\Route::get('d3')->generate(array(
		"controller" => "ozsn",
		"action" => "displayAreaSponzor"
	    )) . "?msg=excep");
	} catch (\PDOException $e) {
	    $handler = new \model\ExceptionHandlerModel($e);
	    $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayAreaSponzor"
            )) . "?msg=excep");
	}
	
	if (!postEmpty()) {
	    try {
		// first do the db work
		$validacija = new \model\formModel\AreaSponzorFormModel(array("iznosDonacije" => post("iznosDonacije"),
										"napomena" => post("napomena"),
										"idPodrucja" => post("idPodrucja")));
		$pov = $validacija->validate();
		if ($pov !== true) {
		    $message = $validacija->decypherErrors($pov);
		    $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
		    $_SESSION["exception"] = serialize($handler);
		    preusmjeri(\route\Route::get('d3')->generate(array(
			"controller" => "ozsn",
			"action" => "modifyAreaSponzor"
		    )) . "?msg=excep&id=" . get("id"));
		}
		
		// data checked and ok
		if (post("iznosDonacije") !== false && post("valutaDonacije") !== false) {
		    $sponElekPod->modifyRow($sponElekPod->getPrimaryKey(), $sponElekPod->idSponzora, post("idPodrucja", null),
			    $sponElekPod->idElektrijade, post("iznosDonacije", null), post("valutaDonacije", null), post("napomena", null));
		}
		
		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "ozsn",
		    "action" => "displayAreaSponzor"
		)) . "?msg=succm");
	    } catch (\PDOException $e) {
		$handler = new \model\ExceptionHandlerModel($e);
		$_SESSION["exception"] = serialize($handler);
		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "ozsn",
		    "action" => "modifyAreaSponzor"
		)) . "?msg=excep&id=" . get("id"));
	    }
	}
	
	echo new \view\Main(array(
	    "body" => new \view\ozsn\AreaSponzorModification(array(
		"errorMessage" => $this->errorMessage,
		"resultMessage" => $this->resultMessage,
		"sponelekpod" => $sponElekPod,
		"podrucja" => $podrucja
	    )),
	    "title" => "Mijenjanje Djelomičnog Sponzora"
	));
    }
    
    /**
     * Deletes a sponsor via get request
     */
    public function deleteSponzor() {
	$this->checkRole();
        
        $this->idCheck("displaySponzor");
	
        $sponzor = new \model\DBSponzor();
        try {
            $sponzor->deleteRow(get("id"));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displaySponzor"
            )) . '?msg=succd');
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displaySponzor"
            )) . "?msg=excep");
        }
    }
    
    /**
     * Removes sponsor from current elektrijada
     */
    public function deleteActiveSponzor() {
	$this->checkRole();
        
        $this->idCheck("displayActiveSponzor");
	
        $imaSponzora = new \model\DBImaSponzora();
        try {
	    $elektrijada = new \model\DBElektrijada();
	    $id = $elektrijada->getCurrentElektrijadaId();
            $imaSponzora->deleteActiveRow(get("id"), $id);
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayActiveSponzor"
            )) . '?msg=succd');
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayActiveSponzor"
            )) . "?msg=excep");
        }
    }
    
    /**
     * Removes sponsor from current elektrijada
     */
    public function deleteAreaSponzor() {
	$this->checkRole();
        
        $this->idCheck("displayAreaSponzor");
	
        $spon = new \model\DBSponElekPod();
        try {
            $spon->deleteRow(get("id"));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayAreaSponzor"
            )) . '?msg=succd');
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayAreaSponzor"
            )) . "?msg=excep");
        }
    }
    
    public function addContact() {
        $this->checkRole();
        $this->checkMessages();
        $sponzor = new \model\DBSponzor();
        $tvrtka = new \model\DBTvrtka();
	$medij = new \model\DBMedij();
        $mail = new \model\DBEmailAdrese();
        $mob = new \model\DBBrojeviMobitela();
        $kontak = new \model\DBKontaktOsobe();
        
        // get company data and sponsor data
        $tvrtke = $tvrtka->getAll();
        $sponzori = $sponzor->getAll();
	$mediji = $medij->getAll();
        
        if (!postEmpty()) {
            $validacija = new \model\formModel\KontaktOsobeFormModel(array(
                "imeKontakt" => post("imeKontakt"),
                "prezimeKontakt" => post("prezimeKontakt"),
                "telefon" => post("telefon"),
                "radnoMjesto" => post("radnoMjesto"),
                "idTvrtke" => post("idTvrtke"),
                "idSponzora" => post("idSponzora"),
		"idMedija" => post("idMedija")
            ));
            
            $pov = $validacija->validate();
            if ($pov !== true) {
                $message = $validacija->decypherErrors($pov);
                $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
                $_SESSION["exception"] = serialize($handler);
                preusmjeri(\route\Route::get('d3')->generate(array(
                    "controller" => "ozsn",
                    "action" => "addContact"
                 )) . "?msg=excep");
            }
            
            // check if atleast one idTvrtke or idSponzora or idMedija is given
            if (post('idTvrtke') === false && false === post('idSponzora') && false === post('idMedija')) {
                $handler = new \model\ExceptionHandlerModel(new \PDOException(), "Morate odabrati barem jednog sponzora, medij ili tvrtku!");
                $_SESSION["exception"] = serialize($handler);
                preusmjeri(\route\Route::get('d3')->generate(array(
                    "controller" => "ozsn",
                    "action" => "addContact"
                 )) . "?msg=excep");
            }
            
            // now we check the mail addresses and phone numbers
            // if you entered a number that already exists we won't add another one, just gonna apply it
            $i = 1;
            while (isset($_POST["mob" . $i])) {
					if ($_POST["mob" . $i] !== "") {
                $validator = new \model\formModel\NumberValidationModel(array("number" => post("mob" . $i)));
                $pov = $validator->validate();
                if ($pov !== true) {
                    $message = $validacija->decypherErrors($pov);
                    $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
                    $_SESSION["exception"] = serialize($handler);
                    preusmjeri(\route\Route::get('d3')->generate(array(
                        "controller" => "ozsn",
                        "action" => "addContact"
                     )) . "?msg=excep");
                }
					}
                $i = $i + 1;
            }
            
            $k = 1;
            while (isset($_POST["mail" . $k])) {
					if ($_POST["mail" . $k] !== "") {
                $validator = new \model\formModel\MailValidationModel(array("mail" => post("mail" . $k)));
                $pov = $validator->validate();
                if ($pov !== true) {
                    $message = $validacija->decypherErrors($pov);
                    $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
                    $_SESSION["exception"] = serialize($handler);
                    preusmjeri(\route\Route::get('d3')->generate(array(
                        "controller" => "ozsn",
                        "action" => "addContact"
                     )) . "?msg=excep");
					}
				}
                $k = $k + 1;
            }
            
            // now i have checked all of the data, next i go add the new contact
            try {
                $kontak->addNewContact(post("imeKontakt"), post("prezimeKontakt"), post("telefon", null), post('radnoMjesto', null),
                        post('idTvrtke', NULL), post('idSponzora', NULL), post('idMedija', NULL));
                // now lets add the phone numbers and e-mails
                for ($j = 1; $j < $i; $j = $j + 1) {
					if(post("mob" . $j))
                    $mob->addNewOrIgnore($kontak->getPrimaryKey(), post("mob" . $j));
                }
                
                for ($j = 1; $j < $k; $j = $j + 1) {
					if(post("mail" . $j))
                    $mail->addNewOrIgnore($kontak->getPrimaryKey(), post("mail" . $j));
                }
                
                preusmjeri(\route\Route::get('d1')->generate() . "?msg=succContact");
                
            } catch (\PDOException $e) {
                $handler = new \model\ExceptionHandlerModel($e);
                $_SESSION["exception"] = serialize($handler);
                preusmjeri(\route\Route::get('d3')->generate(array(
                    "controller" => "ozsn",
                    "action" => "addContact"
                )) . "?msg=excep");
            }
        }
        
        echo new \view\Main(array(
            "body" => new \view\ozsn\AddContact(array(
                        "errorMessage" => $this->errorMessage,
                        "resultMessage" => $this->resultMessage,
                        "tvrtke" => $tvrtke,
                        "sponzori" => $sponzori,
			"mediji" => $mediji
		)),
            "title" => "Dodavanje Kontakta",
			"script" => new \view\scripts\KontaktOsobeFormJs()
            ));
    }
    
    /**
     * Modifies Contact Data
     */
    public function modifyContact() {
		$this->checkRole();
		$this->checkMessages();

		$this->idCheck("displayContacts");

		$sponzor = new \model\DBSponzor();
		$tvrtka = new \model\DBTvrtka();
		$medij = new \model\DBMedij();
		$mail = new \model\DBEmailAdrese();
		$mob = new \model\DBBrojeviMobitela();
		$kontakt = new \model\DBKontaktOsobe();

		// get data so that the view can show em
		$mobiteli = null;
		$mailovi = null;
		$tvrtke = $tvrtka->getAll();
		$sponzori = $sponzor->getAll();
		$mediji = $medij->getAll();

		try {
			$kontakt->load(get("id"));
			// load contact other contact data
			$mailovi = $mail->getContactEmails($kontakt->getPrimaryKey());
			$mobiteli = $mob->getContactNumbers($kontakt->getPrimaryKey());
		} catch (\app\model\NotFoundException $e) {
			$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Nepoznati identifikator!");
			$_SESSION["exception"] = serialize($handler);
			preusmjeri(\route\Route::get('d3')->generate(array(
			"controller" => "ozsn",
			"action" => "displayContacts"
			)) . "?msg=excep");
		} catch (\PDOException $e) {
			$handler = new \model\ExceptionHandlerModel($e);
			$_SESSION["exception"] = serialize($handler);
			preusmjeri(\route\Route::get('d3')->generate(array(
			"controller" => "ozsn",
			"action" => "modifyContact"
			)) . "?msg=excep&id=" . get("id"));
		}

		// if you have sent me data i parse it
		if (!postEmpty()) {
			$validacija = new \model\formModel\KontaktOsobeFormModel(array(
							"imeKontakt" => post("imeKontakt"),
							"prezimeKontakt" => post("prezimeKontakt"),
							"telefon" => post("telefon"),
							"radnoMjesto" => post("radnoMjesto"),
							"idTvrtke" => post("idTvrtke"),
							"idSponzora" => post("idSponzora"),
							"idMedija" => post("idMedija")
							));
			$pov = $validacija->validate();
				if ($pov !== true) {
					$message = $validacija->decypherErrors($pov);
					$handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
					$_SESSION["exception"] = serialize($handler);
					preusmjeri(\route\Route::get('d3')->generate(array(
						"controller" => "ozsn",
						"action" => "modifyContact"
					 )) . "?msg=excep&id=" . post("id"));
				}

			// check if atleast one idTvrtke or idSponzora or idMedija is given
				if (post('idTvrtke') === false && false === post('idSponzora') && false === post('idMedija')) {
					$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Morate odabrati barem jednog sponzora, medij ili tvrtku!");
					$_SESSION["exception"] = serialize($handler);
					preusmjeri(\route\Route::get('d3')->generate(array(
						"controller" => "ozsn",
						"action" => "modifyContact"
					 )) . "?msg=excep&id=" . post("id"));
				}

			// check emails and cell numbers
			$i = 1;
				while (isset($_POST["mob" . $i])) {
					if ($_POST["mob" . $i] !== "") {
					$validator = new \model\formModel\NumberValidationModel(array("number" => post("mob" . $i)));
					$pov = $validator->validate();
					if ($pov !== true) {
						$message = $validacija->decypherErrors($pov);
						$handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
						$_SESSION["exception"] = serialize($handler);
						preusmjeri(\route\Route::get('d3')->generate(array(
							"controller" => "ozsn",
							"action" => "modifyContact"
						 )) . "?msg=excep&id=" . post("id"));
					}
					}
					$i = $i + 1;
					
				}

				$k = 1;
				while (isset($_POST["mail" . $k])) {
					if ($_POST["mail" . $k] !== "") {
					$validator = new \model\formModel\MailValidationModel(array("mail" => post("mail" . $k)));
					$pov = $validator->validate();
					if ($pov !== true) {
						$message = $validacija->decypherErrors($pov);
						$handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
						$_SESSION["exception"] = serialize($handler);
						preusmjeri(\route\Route::get('d3')->generate(array(
							"controller" => "ozsn",
							"action" => "modifyContact"
						 )) . "?msg=excep&id=" . post("id"));
					}
					}
					$k = $k + 1;
				}


			// i checked everything now i add data
			try {
			$kontakt->modifyRow(post("id"), post("imeKontakt"), post("prezimeKontakt"), post("telefon", NULL), post('radnoMjesto', NULL),
							post('idTvrtke', NULL), post('idSponzora', NULL), post('idMedija', NULL));

			// first delete old numbers and mails
			$mob->deleteByContact(post("id"));
			$mail->deleteByContact(post("id"));
			// now change phone numbers and mails
			for ($j = 1; $j < $i; $j = $j + 1) {
					if (post("mob" . $j))
						$mob->addNewOrIgnore(post("id"), post("mob" . $j));
					}

					for ($j = 1; $j < $k; $j = $j + 1) {
						if(post("mail" . $j))
						$mail->addNewOrIgnore(post("id"), post("mail" . $j));
					}

			// everything ok let's redirect
			preusmjeri(\route\Route::get('d3')->generate(array(
				"controller" => "ozsn",
				"action" => "displayContacts"
			)) . "?msg=succMC");
			} catch (\PDOException $e) {
				$handler = new \model\ExceptionHandlerModel($e);
				$_SESSION["exception"] = serialize($handler);
				preusmjeri(\route\Route::get('d3')->generate(array(
					"controller" => "ozsn",
					"action" => "modifyContact"
				)) . "?msg=excep&id=" . post("id"));
			}
		}

		echo new \view\Main(array(
			"body" => new \view\ozsn\ContactModification(array(
			"errorMessage" => $this->errorMessage,
			"resultMessage" => $this->resultMessage,
			"kontakt" => $kontakt,
			"tvrtke" => $tvrtke,
			"sponzori" => $sponzori,
			"mediji" => $mediji,
			"mailovi" => $mailovi,
			"mobiteli" => $mobiteli
			)),
			"title" => "Mijenjanje Kontakta",
			"script" => new \view\scripts\KontaktOsobeFormJs()
		));
    }
    
    /**
     * Simple search for Contacts
     */
    public function searchContacts() {
	$this->checkRole();
	$this->checkMessages();
	$kontakti = null;
	
	$s = new \model\DBSponzor();
	$sponzori = $s->getAll();
	$m = new \model\DBMedij();
	$mediji = $m->getAll();
	$t = new \model\DBTvrtka();
	$tvrtke = $t->getAll();
	
	// parse search query if any
	if (!postEmpty()) {
	    $validacija = new \model\formModel\ContactSearchFormModel(array("search" => post("search"),
									    "idSponzora" => post("idSponzora"),
									    "idTvrtke" => post("idTvrtke"),
									    "idMedija" => post("idMedija")));
	    
	    $pov = $validacija->validate();
            if ($pov !== true) {
                $message = $validacija->decypherErrors($pov);
                $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
                $_SESSION["exception"] = serialize($handler);
                preusmjeri(\route\Route::get('d3')->generate(array(
                    "controller" => "ozsn",
                    "action" => "searchContacts"
                 )) . "?msg=excep");
            }
	    
	    if (false === post("search") && false === post("idSponzora") && false === post("idTvrtke") && false === post("idMedija")) {
		$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Morate unijeti barem jedan parametar pretrage!");
                $_SESSION["exception"] = serialize($handler);
                preusmjeri(\route\Route::get('d3')->generate(array(
                    "controller" => "ozsn",
                    "action" => "searchContacts"
                 )) . "?msg=excep");
	    }
	    
	    // everythings okay now lets search
	    try {
		$k = new \model\DBKontaktOsobe();
		$kontakti = $k->search(post("search", null), post("idTvrtke", null), post("idSponzora", null), post("idMedija", null));
	    } catch (\PDOException $e) {
		$handler = new \model\ExceptionHandlerModel($e);
		$_SESSION["exception"] = serialize($handler);
		preusmjeri(\route\Route::get('d3')->generate(array(
		    "controller" => "ozsn",
		    "action" => "searchContacts"
		)) . "?msg=excep");
	    }
	}	
	
	echo new \view\Main(array(
	    "body" => new \view\ozsn\ContactSearch(array(
		"kontakti" => $kontakti,
		"sponzori" => $sponzori,
		"tvrtke" => $tvrtke,
		"mediji" => $mediji,
		"errorMessage" => $this->errorMessage,
		"resultMessage" => $this->resultMessage
	    )),
	    "title" => "Pretraga Kontakt Osoba"
	));
    }
    
    /**
     * Displays all contacts from all Elektrijada
     */
    public function displayContacts() {
        $this->checkRole();
        $this->checkMessages();
        
        $kontakt = new \model\DBKontaktOsobe();
	$kontakti = null;
        try {
            $kontakti = $kontakt->getAll();
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $this->errorMessage = $handler;
        }
        
        echo new \view\Main(array(
            "body" => new \view\ozsn\ContactList(array(
                "errorMessage" => $this->errorMessage,
                "resultMessage" => $this->resultMessage,
                "kontakti" => $kontakti,
            )),
            "title" => "Kontakt Osobe",
			"script" => new \view\scripts\ozsn\ContactListJs()
        ));
    }
    
    /**
     * Deletes contact via get request
     */
    public function deleteContact() {
        $this->checkRole();
        
        $this->idCheck("displayContacts");
        
        $kontakt = new \model\DBKontaktOsobe();
        try {
            $kontakt->deleteRow(get("id"));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayContacts"
            )) . '?msg=succd');
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayContacts"
            )) . "?msg=excep");
        }
    }
    
    /**
     * Displays all attributes in database
     */
    public function displayAtribut() {
        $this->checkRole();
        $this->checkMessages();
        
        $atribut = new \model\DBAtribut();
	$atributi = null;
        try {
            $atributi = $atribut->getAllAtributes();
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $this->errorMessage = $handler;
        }

        echo new \view\Main(array(
            "body" => new \view\ozsn\AtributList(array(
                "errorMessage" => $this->errorMessage,
                "resultMessage" => $this->resultMessage,
                "atributi" => $atributi
            )),
            "title" => "Lista atributa",
            "script" => new \view\scripts\ozsn\AtributListJs()
        ));
    }
    
    /**
     * Inserts new data into database via post request
     */
    public function addAtribut() {
        $this->checkRole();

        $atribut = new \model\DBAtribut();
        $validacija = new \model\formModel\AtributFormModel(array('nazivAtributa' => post("nazivAtributa")));
        $pov = $validacija->validate();
        if($pov !== true) {
            $message = $validacija->decypherErrors($pov);
            $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayAtribut"
            )) . "?msg=excep");
        }
        
        try {
            $atribut->addRow(post("nazivAtributa", null));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayAtribut"
            )) . '?msg=succa');
        } catch (\PDOException $e){
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayAtribut"
            )) . "?msg=excep");
        }
        
    }
    
    /**
     * Modifies attribute data via post request
     */
    public function modifyAtribut() {
        $this->checkRole();
        
        $atribut = new \model\DBAtribut();
        $validacija = new \model\formModel\AtributFormModel(array('nazivAtributa' => post("nazivAtributa")));
        $pov = $validacija->validate();
        if($pov !== true) {
            $message = $validacija->decypherErrors($pov);
            $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayAtribut"
            )) . "?msg=excep");
        }
        try {
            $atribut->modifyRow(post($atribut->getPrimaryKeyColumn(), null), post('nazivAtributa', null));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayAtribut"
            )) . '?msg=succm');
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayAtribut"
            )) . "?msg=excep");
        }
    }
    
    /**
     * Deletes attribute via get request
     */
    public function deleteAtribut() {
        $this->checkRole();
        
        $this->idCheck("displayAtribut");
	
        $atribut = new \model\DBAtribut();
        try {
            $atribut->deleteRow(get("id"));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayAtribut"
            )) . '?msg=succd');
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayAtribut"
            )) . "?msg=excep");
        }
    }
    
    /**
         *Displays all "velicina" from database
         */
    public function displayVelMajice(){
        $this->checkRole();
        $this->checkMessages();
	
	$velicina = new \model\DBVelMajice();
	$velicine = null;
	try {
            $velicine = $velicina->getAllVelicina();
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $this->errorMessage = $handler;
        }
	
	// put in try catch block
	if (get("type")) {
	    // generate file
	    $pomPolje = array("Veličina majice");
	    $array = array();
	    $array[] = $pomPolje;
	    foreach ($velicine as $v) {
		$pom = array();
		$pom[] = $v->velicina;
		$array[] = $pom;
	    }
	    $path = $this->generateFile(get("type"), $array);
	    
	    echo new \view\ShowFile(array(
		"path" => $path,
		"type" => get("type")
	    ));
	}
	    echo new \view\Main(array(
		"body" => new \view\ozsn\VelMajiceList(array(
		    "errorMessage" => $this->errorMessage,
		    "resultMessage" => $this->resultMessage,
		    "velicine" => $velicine
		)),
		"title" => "Lista velicina",
		"script" => new \view\scripts\ozsn\VelMajiceListJs()
	    ));
	}
	
    /**
     * Inserts new data into database via post request
     */
    public function addVelMajice() {
        $this->checkRole();

        $velicina = new \model\DBVelMajice();
        $validacija = new \model\formModel\VelMajiceFormModel(array('velicina' => post("velicina")));
        $pov = $validacija->validate();
        if($pov !== true) {
            $message = $validacija->decypherErrors($pov);
            $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayVelMajice"
            )) . "?msg=excep");
        }
        
        try {
            $velicina->addRow(post("velicina", null));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayVelMajice"
            )) . '?msg=succa');
        } catch (\PDOException $e){
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayVelMajice"
            )) . "?msg=excep");
        }
        
    }
	
    /**
     * Modifies velicina data via post request
     */
    public function modifyVelMajice() {
        $this->checkRole();
        
        $velicina = new \model\DBVelMajice();
        $validacija = new \model\formModel\VelMajiceFormModel(array('velicina' => post("velicina")));
        $pov = $validacija->validate();
        if($pov !== true) {
            $message = $validacija->decypherErrors($pov);
            $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayVelMajice"
            )) . "?msg=excep");
        }
        try {
            $velicina->modifyRow(post($velicina->getPrimaryKeyColumn(), null), post('velicina', null));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayVelMajice"
            )) . '?msg=succm');
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayVelMajice"
            )) . "?msg=excep");
        }
    }
    
    /**
     * Deletes velicina via get request
     */
    public function deleteVelMajice() {
        $this->checkRole();
        
        $this->idCheck("displayVelMajice");
        
        $velicina = new \model\DBVelMajice();
        try {
            $velicina->deleteRow(get("id"));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayVelMajice"
            )) . '?msg=succd');
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayVelMajice"
            )) . "?msg=excep");
        }
    }
	
    /**
     * Displays all "GodStud" in database
     */
    public function displayGodStud() {
        $this->checkRole();
        $this->checkMessages();
        
        $godina = new \model\DBGodStud();
	$godine = null;
        try {
            $godine = $godina->getAllGodStud();
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $this->errorMessage = $handler;
        }

        echo new \view\Main(array(
            "body" => new \view\ozsn\GodStudList(array(
                "errorMessage" => $this->errorMessage,
                "resultMessage" => $this->resultMessage,
                "godine" => $godine
            )),
            "title" => "Lista godina studija",
            "script" => new \view\scripts\ozsn\GodStudListJs()
        ));
    }
    
    /**
     * Inserts new data into database via post request
     */
    public function addGodStud() {
        $this->checkRole();

        $godStud = new \model\DBGodStud();
        $validacija = new \model\formModel\GodStudFormModel(array('studij' => post("studij"),'godina'=>post("godina")));
        $pov = $validacija->validate();
        if($pov !== true) {
            $message = $validacija->decypherErrors($pov);
            $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayGodStud"
            )) . "?msg=excep");
        }
        
        try {
            $godStud->addRow(post("studij", null),post("godina",null));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayGodStud"
            )) . '?msg=succa');
        } catch (\PDOException $e){
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayGodStud"
            )) . "?msg=excep");
        }
        
    }
	
    /**
     * Modifies godina studiranja data via post request
     */
    public function modifyGodStud() {
        $this->checkRole();
        
        $godStud = new \model\DBGodStud();
        $validacija = new \model\formModel\VelMajiceFormModel(array('studij' => post("studij")),array('godina'=>post("godina")));
        $pov = $validacija->validate();
        if($pov !== true) {
            $message = $validacija->decypherErrors($pov);
            $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayGodStud"
            )) . "?msg=excep");
        }
        try {
            $godStud->modifyRow(post($godStud->getPrimaryKeyColumn(), null), post('studij', null), post('godina', null));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayGodStud"
            )) . '?msg=succm');
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayGodStud"
            )) . "?msg=excep");
        }
    }
    
    /**
     * Deletes Godstud via get request
     */
    public function deleteGodStud() {
        $this->checkRole();
        
        $this->idCheck("displayGodStud");
        
        $godStud = new \model\DBGodStud();
        try {
            $godStud->deleteRow(get("id"));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayGodStud"
            )) . '?msg=succd');
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayGodStud"
            )) . "?msg=excep");
        }
    }
		/**
         *Displays all "radnomjesto" from database
         */
    public function displayRadnoMjesto(){
        $this->checkRole();
        $this->checkMessages();
	
	$naziv = new \model\DBRadnoMjesto();
	$nazivi = null;
	try {
            $nazivi = $naziv->getAllRadnoMjesto();
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $this->errorMessage = $handler;
        }
		
	echo new \view\Main(array(
            "body" => new \view\ozsn\RadnoMjestoList(array(
                "errorMessage" => $this->errorMessage,
                "resultMessage" => $this->resultMessage,
                "nazivi" => $nazivi
            )),
            "title" => "Lista radnih mjesta",
            "script" => new \view\scripts\ozsn\RadnoMjestoListJs()
        ));
	}
/**
* Inserts new data into database via post request
*/
public function addRadnoMjesto() {
        $this->checkRole();

        $naziv = new \model\DBRadnoMjesto();
        $validacija = new \model\formModel\RadnoMjestoFormModel(array('naziv' => post("naziv")));
        $pov = $validacija->validate();
        if($pov !== true) {
            $message = $validacija->decypherErrors($pov);
            $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayRadnoMjesto"
            )) . "?msg=excep");
        }
        
        try {
            $naziv->addRow(post("naziv", null));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayRadnoMjesto"
            )) . '?msg=succa');
        } catch (\PDOException $e){
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayRadnoMjesto"
            )) . "?msg=excep");
        }
        
    }

	/**
     * Modifies radno mjesto data via post request
     */
    public function modifyRadnoMjesto() {
        $this->checkRole();
        
        $naziv = new \model\DBRadnoMjesto();
        $validacija = new \model\formModel\RadnoMjestoFormModel(array('naziv' => post("naziv")));
        $pov = $validacija->validate();
        if($pov !== true) {
            $message = $validacija->decypherErrors($pov);
            $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayRadnoMjesto"
            )) . "?msg=excep");
        }
        try {
            $naziv->modifyRow(post($naziv->getPrimaryKeyColumn(), null), post('naziv', null));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayRadnoMjesto"
            )) . '?msg=succm');
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayRadnoMjesto"
            )) . "?msg=excep");
        }
    }
	
	 /**
     * Deletes radno mjesto via get request
     */
    public function deleteRadnoMjesto() {
        $this->checkRole();
        
		$this->idCheck("displayRadnoMjesto");
        
        $naziv = new \model\DBRadnoMjesto();
        try {
            $naziv->deleteRow(get("id"));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayRadnoMjesto"
            )) . '?msg=succd');
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayRadnoMjesto"
            )) . "?msg=excep");
        }
    }
	
		/**
     * Displays all "Zavodi" in database
     */
    public function displayZavod() {
        $this->checkRole();
        $this->checkMessages();
        
        $zavod = new \model\DBZAvod();
		$zavodi = null;
        try {
            $zavodi = $zavod->getAllZavod();
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $this->errorMessage = $handler;
        }

        echo new \view\Main(array(
            "body" => new \view\ozsn\ZavodList(array(
                "errorMessage" => $this->errorMessage,
                "resultMessage" => $this->resultMessage,
                "zavodi" => $zavodi
            )),
            "title" => "Lista zavoda",
            "script" => new \view\scripts\ozsn\ZavodListJs()
        ));
    }
	
/**
     * Inserts new data into database via post request
     */
    public function addZavod() {
        $this->checkRole();

        $zavod = new \model\DBZavod();
        $validacija = new \model\formModel\ZavodFormModel(array('nazivZavoda' => post("nazivZavoda"),'skraceniNaziv'=>post("skraceniNaziv")));
        $pov = $validacija->validate();
        if($pov !== true) {
            $message = $validacija->decypherErrors($pov);
            $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayZavod"
            )) . "?msg=excep");
        }
        
        try {
            $zavod->addRow(post("nazivZavoda", null),post("skraceniNaziv",null));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayZavod"
            )) . '?msg=succa');
        } catch (\PDOException $e){
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayZavod"
            )) . "?msg=excep");
        }
        
    }
	
	/**
     * Modifies zavod data via post request
     */
    public function modifyZavod() {
        $this->checkRole();
        
        $zavod = new \model\DBZavod();
        $validacija = new \model\formModel\ZavodFormModel(array('nazivZavoda' => post("nazivZavoda"),'skraceniNaziv'=>post("skraceniNaziv")));
        $pov = $validacija->validate();
        if($pov !== true) {
            $message = $validacija->decypherErrors($pov);
            $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayZavod"
            )) . "?msg=excep");
        }
        try {
            $zavod->modifyRow(post($zavod->getPrimaryKeyColumn(), null), post('nazivZavoda', null), post('skraceniNaziv', null));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayZavod"
            )) . '?msg=succm');
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayZavod"
            )) . "?msg=excep");
        }
    }
	
	/**
     * Deletes Zavod via get request
     */
    public function deleteZavod() {
        $this->checkRole();
        
		$this->idCheck("displayZavod");
        
        $zavod = new \model\DBZavod();
        try {
            $zavod->deleteRow(get("id"));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayZavod"
            )) . '?msg=succd');
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayZavod"
            )) . "?msg=excep");
        }
    }
			/**
         *Displays all "smjer" from database
         */
    public function displaySmjer(){
        $this->checkRole();
        $this->checkMessages();
	
	$smjer = new \model\DBSmjer();
	$smjerovi = null;
	try {
            $smjerovi = $smjer->getAllSmjer();
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $this->errorMessage = $handler;
        }
		
	echo new \view\Main(array(
            "body" => new \view\ozsn\SmjerList(array(
                "errorMessage" => $this->errorMessage,
                "resultMessage" => $this->resultMessage,
                "smjerovi" => $smjerovi
            )),
            "title" => "Lista smjerova",
            "script" => new \view\scripts\ozsn\SmjerListJs()
        ));
	}
	
	/**
* Inserts new data into database via post request
*/
public function addSmjer() {
        $this->checkRole();

        $smjer = new \model\DBSmjer();
        $validacija = new \model\formModel\SmjerFormModel(array('nazivSmjera' => post("nazivSmjera")));
        $pov = $validacija->validate();
        if($pov !== true) {
            $message = $validacija->decypherErrors($pov);
            $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displaySmjer"
            )) . "?msg=excep");
        }
        
        try {
            $smjer->addRow(post("nazivSmjera", null));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displaySmjer"
            )) . '?msg=succa');
        } catch (\PDOException $e){
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displaySmjer"
            )) . "?msg=excep");
        }
        
    }
	/**
     * Modifies smjer data via post request
     */
    public function modifySmjer() {
        $this->checkRole();
        
        $smjer = new \model\DBSmjer();
        $validacija = new \model\formModel\SmjerFormModel(array('nazivSmjera' => post("nazivSmjera")));
        $pov = $validacija->validate();
        if($pov !== true) {
            $message = $validacija->decypherErrors($pov);
            $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displaySmjer"
            )) . "?msg=excep");
        }
        try {
            $smjer->modifyRow(post($smjer->getPrimaryKeyColumn(), null), post('nazivSmjera', null));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displaySmjer"
            )) . '?msg=succm');
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displaySmjer"
            )) . "?msg=excep");
        }
    }
	
	 /**
     * Deletes Smjer via get request
     */
    public function deleteSmjer() {
        $this->checkRole();
        
		$this->idCheck("displaySmjer");
        
        $smjer = new \model\DBSmjer();
        try {
            $smjer->deleteRow(get("id"));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displaySmjer"
            )) . '?msg=succd');
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displaySmjer"
            )) . "?msg=excep");
        }
    }
	
			/**
         *Displays all "usluga" from database
         */
    public function displayUsluga(){
        $this->checkRole();
        $this->checkMessages();
	
	$usluga = new \model\DBUsluga();
	$usluge = null;
	try {
            $usluge = $usluga->getAllUsluga();
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $this->errorMessage = $handler;
        }
		
	echo new \view\Main(array(
            "body" => new \view\ozsn\UslugaList(array(
                "errorMessage" => $this->errorMessage,
                "resultMessage" => $this->resultMessage,
                "usluge" => $usluge
            )),
            "title" => "Lista usluga",
            "script" => new \view\scripts\ozsn\UslugaListJs()
        ));
	}

	/**
     * Inserts new data into database via post request
     */
    public function addUsluga() {
        $this->checkRole();

        $usluga = new \model\DBUsluga();
        $validacija = new \model\formModel\UslugaFormModel(array('nazivUsluge' => post("nazivUsluge")));
        $pov = $validacija->validate();
        if($pov !== true) {
            $message = $validacija->decypherErrors($pov);
            $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayUsluga"
            )) . "?msg=excep");
        }
        
        try {
            $usluga->addRow(post("nazivUsluga", null));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayUsluga"
            )) . '?msg=succa');
        } catch (\PDOException $e){
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayUsluga"
            )) . "?msg=excep");
        }
        
    }
	
	/**
     * Modifies usluga data via post request
     */
    public function modifyUsluga() {
        $this->checkRole();
        
        $usluga = new \model\DBUsluga();
        $validacija = new \model\formModel\UslugaFormModel(array('nazivUsluge' => post("nazivUsluge")));
        $pov = $validacija->validate();
        if($pov !== true) {
            $message = $validacija->decypherErrors($pov);
            $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayUsluga"
            )) . "?msg=excep");
        }
        try {
            $usluga->modifyRow(post($usluga->getPrimaryKeyColumn(), null), post('nazivUsluge', null));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayUsluga"
            )) . '?msg=succm');
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayUsluga"
            )) . "?msg=excep");
        }
    }
	/**
     * Deletes usluga via get request
     */
    public function deleteUsluga() {
        $this->checkRole();
        
        $this->idCheck("displayUsluga");
        
        $usluga = new \model\DBUsluga();
        try {
            $usluga->deleteRow(get("id"));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayUsluga"
            )) . '?msg=succd');
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayUsluga"
            )) . "?msg=excep");
        }
    }
			/**
         *Displays all "funkcija" from database
         */
    public function displayFunkcija(){
        $this->checkRole();
        $this->checkMessages();
	
	$funkcija = new \model\DBFunkcija();
	$funkcije = null;
	try {
            $funkcije = $funkcija->getAllFunkcija();
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $this->errorMessage = $handler;
        }
		
	echo new \view\Main(array(
            "body" => new \view\ozsn\FunkcijaList(array(
                "errorMessage" => $this->errorMessage,
                "resultMessage" => $this->resultMessage,
                "funkcije" => $funkcije
            )),
            "title" => "Lista funkcija",
            "script" => new \view\scripts\ozsn\FunkcijaListJs()
        ));
	}
	
	/**
* Inserts new data into database via post request
*/
public function addFunkcija() {
        $this->checkRole();

        $funkcija = new \model\DBFunkcija();
        $validacija = new \model\formModel\FunkcijaFormModel(array('nazivFunkcije' => post("nazivFunkcije")));
        $pov = $validacija->validate();
        if($pov !== true) {
            $message = $validacija->decypherErrors($pov);
            $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayFunkcija"
            )) . "?msg=excep");
        }
        
        try {
            $funkcija->addRow(post("nazivFunkcije", null));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayFunkcija"
            )) . '?msg=succa');
        } catch (\PDOException $e){
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayFunkcija"
            )) . "?msg=excep");
        }
    }
/**
     * Modifies funkcija data via post request
     */
    public function modifyFunkcija() {
        $this->checkRole();
        
        $funkcija = new \model\DBFunkcija();
        $validacija = new \model\formModel\RadnoMjestoFormModel(array('nazivFunkcije' => post("nazivFunkcije")));
        $pov = $validacija->validate();
        if($pov !== true) {
            $message = $validacija->decypherErrors($pov);
            $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayFunkcija"
            )) . "?msg=excep");
        }
        try {
            $funkcija->modifyRow(post($funkcija->getPrimaryKeyColumn(), null), post('nazivFunkcije', null));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayFunkcija"
            )) . '?msg=succm');
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayFunkcija"
            )) . "?msg=excep");
        }
    }
	/**
     * Deletes funkcija via get request
     */
    public function deleteFunkcija() {
        $this->checkRole();
        
		$this->idCheck("displayFunkcija");
        
        $funkcija = new \model\DBFunkcija();
        try {
            $funkcija->deleteRow(get("id"));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayFunkcija"
            )) . '?msg=succd');
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayFunkcija"
            )) . "?msg=excep");
        }
    }
	
		/**
         *Displays all "udruga" from database
         */
    public function displayUdruga(){
        $this->checkRole();
        $this->checkMessages();
	
	$udruga = new \model\DBUdruga();
	$udruge = null;
	try {
            $udruge = $udruga->getAllUdruga();
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $this->errorMessage = $handler;
        }
		
	echo new \view\Main(array(
            "body" => new \view\ozsn\UdrugaList(array(
                "errorMessage" => $this->errorMessage,
                "resultMessage" => $this->resultMessage,
                "udruge" => $udruge
            )),
            "title" => "Lista udruga",
            "script" => new \view\scripts\ozsn\UdrugaListJs()
        ));
	}
	/**
     * Inserts new data into database via post request
     */
    public function addUdruga() {
        $this->checkRole();

        $udruga = new \model\DBUdruga();
        $validacija = new \model\formModel\UdrugaFormModel(array('nazivUdruge' => post("nazivUdruge")));
        $pov = $validacija->validate();
        if($pov !== true) {
            $message = $validacija->decypherErrors($pov);
            $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayUdruga"
            )) . "?msg=excep");
        }
        
        try {
            $udruga->addRow(post("nazivUdruge", null));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayUdruga"
            )) . '?msg=succa');
        } catch (\PDOException $e){
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayUdruga"
            )) . "?msg=excep");
        }
		}
        /**
     * Modifies attribute data via post request
     */
    public function modifyUdruga() {
        $this->checkRole();
        
        $udruga = new \model\DBUdruga();
        $validacija = new \model\formModel\UdrugaFormModel(array('nazivUdruge' => post("nazivUdruge")));
        $pov = $validacija->validate();
        if($pov !== true) {
            $message = $validacija->decypherErrors($pov);
            $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayUdruga"
            )) . "?msg=excep");
        }
        try {
            $udruga->modifyRow(post($udruga->getPrimaryKeyColumn(), null), post('nazivUdruge', null));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayUdruga"
            )) . '?msg=succm');
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayUdruga"
            )) . "?msg=excep");
        }
    }
    /**
     * Deletes udruga via get request
     */
    public function deleteUdruga() {
        $this->checkRole();
        
        $this->idCheck("displayUdruga");
	
        $udruga = new \model\DBUdruga();
        try {
            $udruga->deleteRow(get("id"));
            
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayUdruga"
            )) . '?msg=succd');
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayUdruga"
            )) . "?msg=excep");
        }
    }
	
	/*************************************************************************
	 *					ISPRAVLJENO
	 *************************************************************************/
	public function displayTeamLeaders() {
		$this->checkRole();
		$this->checkMessages();
		
		$osoba = new \model\DBOsoba();
		$voditelji = null;
		$podrucje = new \model\DBPodrucje();
		$podrucja = array();
		try {
			$elektrijada = new \model\DBElektrijada();
			$idElektrijade = $elektrijada->getCurrentElektrijadaId();
			
			$podrucja = $podrucje->getAll();
			$voditelji = $osoba->getTeamLeaders($idElektrijade);
		} catch (app\model\NotFoundException $e) {
			$this->createMessage("Nepoznati identifikator!");
		} catch (\PDOException $e) {
			$handler = new \model\ExceptionHandlerModel($e);
			$this->createMessage($handler);
		}
		
		if (get("type") !== false) {
			$pomPolje = array("Područje", "Ime", "Prezime", "JMBAG", "Tip");
			$array = array();
			$array[] = $pomPolje;
			
			if ($voditelji !== null && count($voditelji)) {
				foreach ($voditelji as $v) {
					$array[] = array($v->nazivPodrucja, $v->ime, $v->prezime, $v->JMBAG, ($v->tip == "S" ? "Student" : ($v->tip == "D" ? "Djelatnik" : "Ozsn")));
				}
			}
			
			$path = $this->generateFile(get("type"), $array);
			
			echo new \view\ShowFile(array(
				"path" => $path,
				"type" => get("type")
			));
		}
		
		echo new \view\Main(array(
			"title" => "Voditelji",
			"body" => new \view\ozsn\TeamLeaders(array(
				"errorMessage" => $this->errorMessage,
				"resultMessage" => $this->resultMessage,
				"voditelji" => $voditelji,
				"podrucja" => $podrucja
			))
		));
	}
	
	public function addTeamLeader() {
		$this->checkRole();
		$this->checkMessages();
		
		$osoba = new \model\DBOsoba();
		$sudjelovanje = new \model\DBSudjelovanje();
		$velicina = new \model\DBVelMajice();
		$smjer = new \model\DBSmjer();
		$godina = new \model\DBGodStud();
		$mjesto = new \model\DBRadnoMjesto();
		$zavod = new \model\DBZavod();
		
		$smjerovi = null;
		$zavodi = null;
		$velicine = null;
		$godine = null;
		$mjesta = null;
		$idPodrucja = null;
		
		if (post("idDolazak") !== false || post("idPodrucja") !== false || get("id") !== false) {
			$idPodrucja = post("idDolazak") === false ? post("idPodrucja", NULL) : post("idDolazak");
			if ($idPodrucja === NULL)
				$idPodrucja = get("id");
			
			try {	
				// get drop down data
				$godine = $godina->getAllGodStud();
				$zavodi = $zavod->getAllZavod();
				$smjerovi = $smjer->getAllSmjer();
				$velicine = $velicina->getAllVelicina();
				$mjesta = $mjesto->getAllRadnoMjesto();
			} catch (app\model\NotFoundException $e) {
				$this->createMessage("Nepostojeći zapis!", "d3", "ozsn", "displayTeamLeaders");
			} catch (\PDOException $e) {
				$handler = new \model\ExceptionHandlerModel($e);
				$this->createMessage($handler, "d3", "ozsn", "displayTeamLeaders");
			}
			
		} else {
			$this->createMessage("Nepoznata disciplina!", "d3", "ozsn", "displayTeamLeaders");
		}
		
		if (!postEmpty() && false === post("idDolazak")) {
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
				$handler = new \model\ExceptionHandlerModel(new \PDOException(), $validacija->decypherErrors($pov));
				$_SESSION["exception"] = serialize($handler);
				preusmjeri(\route\Route::get('d3')->generate(array(
					"controller" => "ozsn",
					"action" => "addTeamLeader"
				)) . "?msg=excep&id=" . post("idPodrucja"));
			}
			try {
				if ($osoba->userExists(post("ferId")) === false) {
					// add new user
					$elektrijada = new \model\DBElektrijada();
					$idElektrijade = $elektrijada->getCurrentElektrijadaId();
					$osoba->addNewPerson(post("ime", NULL), post("prezime", NULL), post("mail", NULL), post("brojMob", NULL), 
							post("ferId"), post("password"), post("JMBAG", NULL), post("spol", "M"), post("datRod", NULL), 
							post("brOsobne", NULL), post("brPutovnice", NULL), post("osobnaVrijediDo", NULL), 
							post("putovnicaVrijediDo", NULL), "S", NULL, post("MBG", NULL), post("OIB", NULL), session("auth"), 
							post("aktivanDokument", "0"));
					
					// okay person added now let's add competition data
					if (post("tip") === 'S') {
						$sudjelovanje->addRow($osoba->getPrimaryKey(), $idElektrijade, post("tip", "S"), post("idVelicine", NULL), 
								post("idGodStud", NULL), post("idSmjera", NULL), NULL, NULL, NULL);
					} else {
						$sudjelovanje->addRow($osoba->getPrimaryKey(), $idElektrijade, post("tip", "D"), post("idVelicine", NULL), 
								post("idGodStud", NULL), NULL, post("idRadnogMjesta", NULL), post("idZavoda", NULL), NULL);
					}
					
					// now lets add him for the team leadership
					$imaAtribut = new \model\DBImaatribut();
					$atribut = new \model\DBAtribut();
					
					$id = $atribut->getTeamLeaderId();
					if ($id === false) {
						$atribut->addRow("Voditelj");
						$id = $atribut->getPrimaryKey();
					}
					
					$imaAtribut->addRow(post("idPodrucja"), $id, $sudjelovanje->getPrimaryKey());
					
					// everything's okay redirect
					preusmjeri(\route\Route::get('d3')->generate(array(
						"controller" => "ozsn",
						"action" => "displayTeamLeaders"
					)) . "?msg=succa");
				}
			} catch (app\model\NotFoundException $e) {
				$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Nepoznati identifikator!");
				$_SESSION["exception"] = serialize($handler);
				preusmjeri(\route\Route::get('d3')->generate(array(
					"controller" => "ozsn",
					"action" => "addTeamLeader"
				)) . "?msg=excep&id=" . post("idPodrucja"));
			} catch (\PDOException $e) {
				$handler = new \model\ExceptionHandlerModel($e);
				$_SESSION["exception"] = serialize($handler);
				preusmjeri(\route\Route::get('d3')->generate(array(
					"controller" => "ozsn",
					"action" => "addTeamLeader"
				)) . "?msg=excep&id=" . post("idPodrucja"));
			}
		} 
		
		echo new \view\Main(array(
			"title" => "Dodavanje Voditelja",
			"body" => new \view\ozsn\AddTeamLeader(array(
				"errorMessage" => $this->errorMessage,
				"resultMessage" => $this->resultMessage,
				"idPodrucja" => $idPodrucja,
				"radnaMjesta" => $mjesta,
				"velicine" => $velicine,
				"smjerovi" => $smjerovi,
				"godine" => $godine,
				"zavodi" => $zavodi
			))
		));
	}
	
	public function addExistingTeamLeader() {
		$this->checkRole();
		$this->checkMessages();
		
		$osoba = new \model\DBOsoba();
		$sudjelovanje = new \model\DBSudjelovanje();
		$velicina = new \model\DBVelMajice();
		$smjer = new \model\DBSmjer();
		$godina = new \model\DBGodStud();
		$mjesto = new \model\DBRadnoMjesto();
		$zavod = new \model\DBZavod();
		
		$smjerovi = null;
		$zavodi = null;
		$velicine = null;
		$godine = null;
		$mjesta = null;
		$idPodrucja = null;
		$osobe = null;
		
		if (post("idDolazak") !== false || post("idPodrucja") !== false || get("id") !== false) {
			$idPodrucja = post("idDolazak") === false ? post("idPodrucja", NULL) : post("idDolazak");
			if ($idPodrucja === NULL)
				$idPodrucja = get("id");
			
			try {	
				// get drop down data
				$godine = $godina->getAllGodStud();
				$zavodi = $zavod->getAllZavod();
				$smjerovi = $smjer->getAllSmjer();
				$velicine = $velicina->getAllVelicina();
				$mjesta = $mjesto->getAllRadnoMjesto();
				$osobe = $osoba->getAllPersons();
			} catch (app\model\NotFoundException $e) {
				$this->createMessage("Nepostojeći zapis!", "d3", "ozsn", "displayTeamLeaders");
			} catch (\PDOException $e) {
				$handler = new \model\ExceptionHandlerModel($e);
				$this->createMessage($handler, "d3", "ozsn", "displayTeamLeaders");
			}
			
		} else {
			$this->createMessage("Nepoznata disciplina!", "d3", "ozsn", "displayTeamLeaders");
		}
		
		if (!postEmpty() && false === post("idDolazak")) {
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
				$handler = new \model\ExceptionHandlerModel(new \PDOException(), $validacija->decypherErrors($pov));
				$_SESSION["exception"] = serialize($handler);
				preusmjeri(\route\Route::get('d3')->generate(array(
					"controller" => "ozsn",
					"action" => "addTeamLeader"
				)) . "?msg=excep&id=" . post("idPodrucja"));
			}
			try {
				if ($osoba->userExists(post("ferId")) === false) {
					// add new user
					$elektrijada = new \model\DBElektrijada();
					$idElektrijade = $elektrijada->getCurrentElektrijadaId();
					$osoba->addNewPerson(post("ime", NULL), post("prezime", NULL), post("mail", NULL), post("brojMob", NULL), 
							post("ferId"), post("password"), post("JMBAG"), post("spol", "M"), post("datRod", NULL), 
							post("brOsobne", NULL), post("brPutovnice", NULL), post("osobnaVrijediDo", NULL), 
							post("putovnicaVrijediDo", NULL), "S", NULL, post("MBG", NULL), post("OIB", NULL), sesion("auth"), 
							post("aktivanDokument", "0"));
					
					// okay person added now let's add competition data
					if (post("tip") === 'S') {
						$sudjelovanje->addRow($osoba->getPrimaryKey(), $idElektrijade, post("tip", "S"), post("idVelicine", NULL), 
								post("idGodStud", NULL), post("idSmjera", NULL), NULL, NULL, NULL);
					} else {
						$sudjelovanje->addRow($osoba->getPrimaryKey(), $idElektrijade, post("tip", "D"), post("idVelicine", NULL), 
								post("idGodStud", NULL), NULL, post("idRadnogMjesta", NULL), post("idZavoda", NULL), NULL);
					}
					
					// now lets add him for the team leadership
					$imaAtribut = new \model\DBImaatribut();
					$atribut = new \model\DBAtribut();
					
					$id = $atribut->getTeamLeaderId();
					if ($id === false) {
						$atribut->addRow("Voditelj");
						$id = $atribut->getPrimaryKey();
					}
					
					$imaAtribut->addRow(post("idPodrucja"), $id, $sudjelovanje->getPrimaryKey());
					
					// everything's okay redirect
					preusmjeri(\route\Route::get('d3')->generate(array(
						"controller" => "ozsn",
						"action" => "displayTeamLeaders"
					)) . "?msg=succa");
				}
			} catch (app\model\NotFoundException $e) {
				$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Nepoznati identifikator!");
				$_SESSION["exception"] = serialize($handler);
				preusmjeri(\route\Route::get('d3')->generate(array(
					"controller" => "ozsn",
					"action" => "addTeamLeader"
				)) . "?msg=excep&id=" . post("idPodrucja"));
			} catch (\PDOException $e) {
				$handler = new \model\ExceptionHandlerModel($e);
				$_SESSION["exception"] = serialize($handler);
				preusmjeri(\route\Route::get('d3')->generate(array(
					"controller" => "ozsn",
					"action" => "addTeamLeader"
				)) . "?msg=excep&id=" . post("idPodrucja"));
			}
		} 
		
		echo new \view\Main(array(
			"title" => "Dodavanje Voditelja",
			"body" => new \view\ozsn\AddExistingTeamLeader(array(
				"errorMessage" => $this->errorMessage,
				"resultMessage" => $this->resultMessage,
				"idPodrucja" => $idPodrucja,
				"radnaMjesta" => $mjesta,
				"velicine" => $velicine,
				"smjerovi" => $smjerovi,
				"godine" => $godine,
				"zavodi" => $zavodi
			))
		));
	}
	
	public function removeTeamLeader() {
		
	}
	
	public function modifyTeamLeader() {
		
	}
}
