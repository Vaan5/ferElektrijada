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
	    "body" => new \view\ozsn\OzsnFunctionsList(array(
		"errorMessage" => $this->errorMessage,
		"resultMessage" => $this->resultMessage,
		"sveFunkcije" => $sveFunkcije,
		"funkcijeKorisnika" => $funkcijeKorisnika
	    )),
	    "title" => "Vaše Funkcije",
		"script" => new \view\scripts\ozsn\FunkcijaListJs()
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
	    "title" => "Vaše Udruge",
		"script" => new \view\scripts\ozsn\UdrugaListJs()
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
            "title" => "Načini Promocije",
			"script" => new \view\scripts\ozsn\MedijiListJs()
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
            "title" => "Aktualne objave",
			"script" => new \view\scripts\ozsn\ActiveObjavaListJs()
        ));
    }
    
	public function download() {
		$this->checkRole();
		$this->checkMessages();

		$this->idCheck("displayActiveObjava");

		$objava = new \model\DBObjava();
		try {
			$objava->load(get("id"));
		} catch (\app\model\NotFoundException $e) {
			$this->createMessage("Nepoznata objava!");
		} catch (\PDOException $e) {
			$handler = new \model\ExceptionHandlerModel($e);
			$this->createMessage($handler);
		}

		echo new \view\Download(array(
			"path" => $objava->dokument
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
            "body" => new \view\ozsn\ObjavaList(array(
                "errorMessage" => $this->errorMessage,
                "resultMessage" => $this->resultMessage,
		"objave" => $objave
            )),
            "title" => "Objave",
			"script" => new \view\scripts\ozsn\ObjavaListJs()
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
	    "title" => "Dodavanje Objave",
		"script" => new \view\scripts\ObjavaFormJs()
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
			if ($objava->dokument != NULL) {
			    $p = unlink($objava->dokument);
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
			$p = unlink($objava->dokument);
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
	    "title" => "Mijenjanje Objave",
		"script" => new \view\scripts\ObjavaFormJs()
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
            $usluga->addRow(post("nazivUsluge", null));
            
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
            
			if (get("m") !== false) {
				preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayUserUdruge"
				 )) . '?msg=succm');
			}
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

		$idPodrucja = null;
		$osobe = null;
		
		if (post("idDolazak") !== false || post("idPodrucja") !== false || get("id") !== false) {
			$idPodrucja = post("idDolazak") === false ? post("idPodrucja", NULL) : post("idDolazak");
			if ($idPodrucja === NULL)
				$idPodrucja = get("id");
			
			try {	
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
			try {
				
				$idPodrucja = post("idPodrucja");
				$imaatribut = new \model\DBImaatribut();
				$elektrijada = new \model\DBElektrijada();
				$idElektrijade = $elektrijada->getCurrentElektrijadaId();
				$sudjelovanje = new \model\DBSudjelovanje();
				$atribut = new \model\DBAtribut();
				
				foreach ($_POST as $k => $v) {
					if ($k !== 'idPodrucja') {
						if ($imaatribut->isTeamLeader($k, $idPodrucja, $idElektrijade))
								continue;
						// else add him
						$id = $atribut->getTeamLeaderId();
						if ($id === false) {
							$atribut->addRow("Voditelj");
							$id = $atribut->getPrimaryKey();
						}
						if (false !== ($prim = $sudjelovanje->exists($k, $idElektrijade))) {
							$imaatribut->addRow(post("idPodrucja"), $id, $prim);
						} else {
							$sudjelovanje->addRow($osoba->getPrimaryKey(), $idElektrijade, post("tip", "S"), NULL, 
								NULL, NULL, NULL, NULL, NULL);
							$imaatribut->addRow(post("idPodrucja"), $id, $sudjelovanje->getPrimaryKey());
						}
						
					}
				}
				// everything's okay redirect
				preusmjeri(\route\Route::get('d3')->generate(array(
					"controller" => "ozsn",
					"action" => "displayTeamLeaders"
				)) . "?msg=succa");
			} catch (app\model\NotFoundException $e) {
				$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Nepoznati identifikator!");
				$_SESSION["exception"] = serialize($handler);
				preusmjeri(\route\Route::get('d3')->generate(array(
					"controller" => "ozsn",
					"action" => "addExistingTeamLeader"
				)) . "?msg=excep&id=" . post("idPodrucja"));
			} catch (\PDOException $e) {
				$handler = new \model\ExceptionHandlerModel($e);
				$_SESSION["exception"] = serialize($handler);
				preusmjeri(\route\Route::get('d3')->generate(array(
					"controller" => "ozsn",
					"action" => "addExistingTeamLeader"
				)) . "?msg=excep&id=" . post("idPodrucja"));
			}
		} 
		
		echo new \view\Main(array(
			"title" => "Dodavanje Voditelja",
			"body" => new \view\ozsn\AddExistingTeamLeader(array(
				"errorMessage" => $this->errorMessage,
				"resultMessage" => $this->resultMessage,
				"idPodrucja" => $idPodrucja,
				"osobe" => $osobe
			))
		));
	}
	
	public function removeTeamLeader() {
		$this->checkRole();
		$this->checkMessages();
		
		$this->getParamCheck("idA", "displayTeamLeaders");
		$this->getParamCheck("idS", "displayTeamLeaders");
		
		try {
			$imaatribut = new \model\DBImaatribut();
			$sudjelovanje = new \model\DBSudjelovanje();
			$atribut = new \model\DBAtribut();
			
			$imaatribut->load(get("idA"));
			$sudjelovanje->load(get("idS"));
			
			$id = $atribut->getTeamLeaderId();
			if ($imaatribut->idAtributa != $id)
				$this->createMessage("Osoba nije voditelj!", "d3", "ozsn", "displayTeamLeaders");
			
			if ($imaatribut->idSudjelovanja != $sudjelovanje->getPrimaryKey())
				$this->createMessage("Osoba nije voditelj!", "d3", "ozsn", "displayTeamLeaders");
			
			$imaatribut->delete();
			$zast = false;
			if (session("auth") == $sudjelovanje->idOsobe)
				$zast = true;
			
			$podrucjeSudjelovanja = new \model\DBPodrucjeSudjelovanja();
			if (!$podrucjeSudjelovanja->isParticipating($sudjelovanje->getPrimaryKey()) && !$imaatribut->hasARole($sudjelovanje->getPrimaryKey())) {
				$sudjelovanje->delete();
			}
			
			if($zast)
				session_destroy ();
			
			// okay lets redirect
			preusmjeri(\route\Route::get('d3')->generate(array(
				"controller" => "ozsn",
				"action" => "displayTeamLeaders"
			))  . "?msg=succd");
		} catch (app\model\NotFoundException $e) {
			$this->createMessage("Nepoznati identifikator!", "d3", "ozsn", "displayTeamLeaders");
		} catch (\PDOException $e) {
			$handler = new \model\ExceptionHandlerModel($e);
			$this->createMessage($handler, "d3", "ozsn", "displayTeamLeaders");
		}
	}
	
	public function modifyTeamLeader() {
		$this->checkRole();
		$this->checkMessages();
		
		$osoba = new \model\DBOsoba();
		$sudjelovanje = new \model\DBSudjelovanje();
		$imaatribut = new \model\DBImaatribut();
		$atribut = new \model\DBAtribut();
		
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
		
		if (postEmpty()) {
			$this->getParamCheck("idA", "displayTeamLeaders");
			$this->getParamCheck("idS", "displayTeamLeaders");
			$idImaAtribut = get("idA");
			
			try {
				$sudjelovanje->load(get("idS"));
				$imaatribut->load(get("idA"));
				$id = $atribut->getTeamLeaderId();
				if ($imaatribut->idAtributa != $id)
					$this->createMessage("Osoba nije voditelj!", "d3", "ozsn", "displayTeamLeaders");

				if ($imaatribut->idSudjelovanja != $sudjelovanje->getPrimaryKey())
					$this->createMessage("Osoba nije voditelj!", "d3", "ozsn", "displayTeamLeaders");
				
				$osoba->load($sudjelovanje->idOsobe);

				if ($sudjelovanje->isStudent()) {
					$godine = $godina->getAllGodStud();
					$smjerovi = $smjer->getAllSmjer();
					
					$godina->loadIfExists($sudjelovanje->idGodStud);
					$smjer->loadIfExists($sudjelovanje->idSmjera);
					
				} else {
					$mjesta = $mjesto->getAllRadnoMjesto();
					$zavodi = $zavod->getAllZavod();
					$godine = $godina->getAllGodStud();
					
					$zavod->loadIfExists($sudjelovanje->idZavoda);
					$godina->loadIfExists($sudjelovanje->idGodStud);
					$mjesto->loadIfExists($sudjelovanje->idRadnogMjesta);
				}
				$velicine = $velicina->getAllVelicina();
				$velicina->loadIfExists($sudjelovanje->idVelicine);
				
			} catch (app\model\NotFoundException $e) {
				$this->createMessage("Nepoznati identifikator!", "d3", "ozsn", "displayTeamLeaders");
			} catch (\PDOException $e) {
				$handler = new \model\ExceptionHandlerModel($e);
				$this->createMessage($handler, "d3", "ozsn", "displayTeamLeaders");
			}
		} else {
			// process query
			$idImaAtribut = post("idPodrucja");
			
			try {
				$validacija = new \model\PersonFormModel(array(
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
					preusmjeri(\route\Route::get("d3")->generate(array(
						"controller" => "ozsn",
						"action" => "modifyTeamLeader"
					)) . "?msg=excep&idS=" . post("idSudjelovanja") . "&idA=" . $idImaAtribut);
				} else {
					// process query
					$osoba->modifyPerson(post("idOsobe"), post("ime", NULL), post("prezime", NULL), post("mail", NULL), 
							post("brojMob", NULL), post("ferId", NULL), NULL, post("JMBAG", NULL),
							post("spol", NULL), post("datRod", NULL), post("brOsobne", NULL), post("brPutovnice", NULL),
							post("osobnaVrijediDo", NULL), post("putovnicaVrijediDo", NULL), NULL, post("MBG", NULL), 
							post("OIB", NULL), post("aktivanDokument", "0"));
					
					$osoba->load(post("idOsobe"));
					// now add the competition data
					$sudjelovanje = new \model\DBSudjelovanje();
					$sudjelovanje->load(post("idSudjelovanja"));
					$sudjelovanje->tip = post("tip", "S");
					$sudjelovanje->save();
					
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
							$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Datoteka je prevelika! Maksimalna dozvoljena veličina je 1 MB!");
							$_SESSION["exception"] = serialize($handler);
							preusmjeri(\route\Route::get("d3")->generate(array(
								"controller" => "ozsn",
								"action" => "modifyTeamLeader"
							)) . "?msg=excep&idS=" . post("idSudjelovanja") . "&idA=" . $idImaAtribut);
						}
						if(!is_uploaded_file(files("tmp_name", "datoteka"))) {
							$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Morate poslati datoteku!");
							$_SESSION["exception"] = serialize($handler);
							preusmjeri(\route\Route::get("d3")->generate(array(
								"controller" => "ozsn",
								"action" => "modifyTeamLeader"
							)) . "?msg=excep&idS=" . post("idSudjelovanja") . "&idA=" . $idImaAtribut);
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
							preusmjeri(\route\Route::get("d3")->generate(array(
								"controller" => "ozsn",
								"action" => "modifyTeamLeader"
							)) . "?msg=excep&idS=" . post("idSudjelovanja") . "&idA=" . $idImaAtribut);
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
							$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Dogodio se problem sa spremanjem životopisa! Ostali podaci su ažurirani!");
							$_SESSION["exception"] = serialize($handler);
							preusmjeri(\route\Route::get("d3")->generate(array(
								"controller" => "ozsn",
								"action" => "modifyTeamLeader"
							)) . "?msg=excep&idS=" . post("idSudjelovanja") . "&idA=" . $idImaAtribut);
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
						"controller" => "ozsn",
						"action" => "displayTeamLeaders"
					)) . "?msg=succm");
					
				}
			} catch (\app\model\NotFoundException $e) {
				$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Nepostojeći identifikator!");
				$_SESSION["exception"] = serialize($handler);
				preusmjeri(\route\Route::get("d3")->generate(array(
					"controller" => "ozsn",
					"action" => "modifyTeamLeader"
				)) . "?msg=excep&idS=" . post("idSudjelovanja") . "&idA=" . $idImaAtribut);
			} catch (\PDOException $e) {
				$handler = new \model\ExceptionHandlerModel($e);
				$_SESSION["exception"] = serialize($handler);
				preusmjeri(\route\Route::get("d3")->generate(array(
					"controller" => "ozsn",
					"action" => "modifyTeamLeader"
				)) . "?msg=excep&idS=" . post("idSudjelovanja") . "&idA=" . $idImaAtribut);
			} 
		}
		
		echo new \view\Main(array(
			"title" => "Ažuriranje Podataka",
			"body" => new \view\ozsn\ModifyTeamLeader(array(
				"errorMessage" => $this->errorMessage,
				"resultMessage" => $this->resultMessage,
				"osoba" => $osoba,
				"sudjelovanje" => $sudjelovanje,
				"smjerovi" => $smjerovi,
				"zavodi" => $zavodi,
				"velicine" => $velicine,
				"mjesta" => $mjesta,
				"godine" => $godine,
				"velicina" => $velicina,
				"godina" => $godina,
				"mjesto" => $mjesto,
				"zavod" => $zavod,
				"smjer" => $smjer,
				"idimaatribut" => $idImaAtribut
			))
		));
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
	
	public function displayProfile() {
		$this->checkRole();
		$this->checkMessages();

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
			"body" => new \view\ozsn\Profile(array(
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
				)),
			"script" => new \view\scripts\PersonFormJs()
		));
    }
	
    public function modifyProfile() {
		$this->checkRole();
		$this->checkMessages();
		
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
				   $this->createMessage($validacija->decypherErrors($pov), "d3", "ozsn", "displayProfile");
				} else {
					// everything's ok ; insert new row
					// first check for passwords
					if (post("password") !== false) {
						if (post("password_new") !== false && post("password_new2") !== false) {
							$pov = $osoba->checkPassword(post("idOsobe"), post("password"));
							if ($pov === false || $pov->getPrimaryKey() != session('auth')) {
								$this->createMessage("Pogrešna stara lozinka!", "d3", "ozsn", "displayProfile");
							}
							if (post("password_new") !== post("password_new2")) {
								$this->createMessage("Nove lozinke se ne podudaraju!", "d3", "ozsn", "displayProfile");
							}
						} else {
							$this->createMessage("Ukoliko mijenjate lozinku, morate unijeti staru, kao i novu lozinku!", "d3", "ozsn", "displayProfile");
						}
					} else {
						if(post("password_new") !== false || post("password_new2") !== false) {
							$this->createMessage("Morate unijeti staru lozinku!", "d3", "ozsn", "displayProfile");
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
							$this->createMessage("Datoteka je prevelika! Maksimalna dozvoljena veličina je 1 MB!", "d3", "ozsn", "displayProfile");
						}
						if(!is_uploaded_file(files("tmp_name", "datoteka"))) {
							$this->createMessage("Morate poslati datoteku!", "d3", "ozsn", "displayProfile");
						}
						// check if it is a pdf
						if(function_exists('finfo_file')) {
							$finfo = \finfo_open(FILEINFO_MIME_TYPE);
							$mime = finfo_file($finfo, files("tmp_name", "datoteka"));
						} else {
							$mime = \mime_content_type(files("tmp_name", "datoteka"));
						}
						if($mime != 'application/pdf') {
							$this->createMessage("Životopis možete poslati samo u pdf formatu!", "d3", "ozsn", "displayProfile");
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
							$this->createMessage("Dogodio se problem sa spremanjem životopisa! Ostali podaci su ažurirani!", "d3", "ozsn", "displayProfile");
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
				$this->createMessage("Nepostojeći identifikator!", "d3", "ozsn", "displayProfile");
			} catch (\PDOException $e) {
				$handler = new \model\ExceptionHandlerModel($e);
				$this->createMessage($handler, "d3", "ozsn", "displayProfile");
			} 
		}
		$this->createMessage("Morate unijeti podatke!", "d3", "ozsn", "displayProfile");
    }
	
	public function searchContestants() {
		$this->checkRole();
		$this->checkMessages();
		
		$osoba = new \model\DBOsoba();
		
		$osobe = null;

		// parse search query if any
		if (!postEmpty()) {
			$validacija = new \model\MediumPersonSearchFormModel(array(
                'ferId' => post('ferId'),
                'ime' => post('ime'), 
                'prezime' => post('prezime'),
                'OIB' => post('OIB'),
                'JMBAG' => post('JMBAG')
            ));

			$pov = $validacija->validate();
			if ($pov !== true) {
				$this->createMessage($validacija->decypherErrors($pov), "d3", "ozsn", "searchContestants");
			}

			// everythings okay now lets search
			try {
				$osobe = $osoba->find(post('ime'), post('prezime'), post('ferId'), post('OIB'), post('JMBAG'), 'O');
				$osobe = $osobe === false ? array() : $osobe;
				$_SESSION['search'] = serialize(array(post('ime'), post('prezime'), post('ferId'), post('OIB'), post('JMBAG'), 'O'));
			} catch (\PDOException $e) {
				$handler = new \model\ExceptionHandlerModel($e);
				$this->createMessage($handler, "d3", "ozsn", "searchContestants");
			}
		}
		
		if (get("a") !== false) {
			try {
				$osobe = $osoba->getAllPersons('O');
				$_SESSION['search'] = 'a';
			} catch (\PDOException $e) {
				$handler = new \model\ExceptionHandlerModel($e);
				$this->createMessage($handler, "d3", "ozsn", "searchContestants");
			}
		}
		
		if (get("type") !== false) {
			try {
				if (session('search') === 'a') {
					$osobe = $osoba->getAllPersons('O');
				} else {
					$parametri = unserialize(session("search"));
					$osobe = $osoba->find($parametri[0], $parametri[1], $parametri[2], $parametri[3], $parametri[4], $parametri[5]);
					$osobe = $osobe === false ? array() : $osobe;
				}
			} catch (\PDOException $e) {
				$handler = new \model\ExceptionHandlerModel($e);
				$this->createMessage($handler, "d3", "ozsn", "searchContestants");
			}
			
			$pomPolje = array("Ime", "Prezime", "JMBAG", "OIB", "Korisničko ime");
			$array = array();
			$array[] = $pomPolje;
			
			if ($osobe !== null && count($osobe)) {
				foreach ($osobe as $v) {
					$array[] = array($v->ime, $v->prezime, $v->JMBAG, $v->OIB, $v->ferId);
				}
			}
			
			$path = $this->generateFile(get("type"), $array);
			
			echo new \view\ShowFile(array(
				"path" => $path,
				"type" => get("type")
			));
		}

		echo new \view\Main(array(
            "body" => new \view\ozsn\ContestantSearch(array(
                "osobe" => $osobe,
                "errorMessage" => $this->errorMessage,
                "resultMessage" => $this->resultMessage
            )),
            "title" => "Pretraga Sudionika",
        ));
	}
	
	public function addContestant() {
		$this->checkRole();
		$this->checkMessages();
		
		$osoba = new \model\DBOsoba();
		$sudjelovanje = new \model\DBSudjelovanje();
		$velicina = new \model\DBVelMajice();
		$smjer = new \model\DBSmjer();
		$godina = new \model\DBGodStud();
		$mjesto = new \model\DBRadnoMjesto();
		$zavod = new \model\DBZavod();
		$podrucje = new \model\DBPodrucje();
		$atribut = new \model\DBAtribut();
		
		$smjerovi = null;
		$zavodi = null;
		$velicine = null;
		$godine = null;
		$mjesta = null;
		$idPodrucja = null;
		$atributi = null;
		$podrucja = null;
		
		try {	
			// get drop down data
			$godine = $godina->getAllGodStud();
			$zavodi = $zavod->getAllZavod();
			$smjerovi = $smjer->getAllSmjer();
			$velicine = $velicina->getAllVelicina();
			$mjesta = $mjesto->getAllRadnoMjesto();
			$podrucja = $podrucje->getAll();
			$atributi = $atribut->getAllAtributes();
		} catch (app\model\NotFoundException $e) {
			$this->createMessage("Nepostojeći zapis!");
		} catch (\PDOException $e) {
			$handler = new \model\ExceptionHandlerModel($e);
			$this->createMessage($handler);
		}
		
		if (!postEmpty()) {
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
				$this->createMessage($validacija->decypherErrors($pov), "d3", "ozsn", "addContestant");
			}
			
			if (post("idPodrucja") === false && post("idAtributa") === true) {
				$this->createMessage("Da biste dodali atribut morate odabrati i disciplinu!", "d3", "ozsn", "addContestant");
			} else if (post("option") === false && !(post("idPodrucja") === false || post("idAtributa") === false)){
				$this->createMessage("Morate odabrati da li unosite samo atribut ili sudjelovanje u natjecanju!", "d3", "ozsn", "addContestant");
			}
			
			try {
				// add new user
				$elektrijada = new \model\DBElektrijada();
				$idElektrijade = $elektrijada->getCurrentElektrijadaId();
				$osoba->addNewPerson(post("ime", NULL), post("prezime", NULL), post("mail", NULL), post("brojMob", NULL), 
						post("ferId"), post("password"), post("JMBAG", NULL), post("spol", "M"), post("datRod", NULL), 
						post("brOsobne", NULL), post("brPutovnice", NULL), post("osobnaVrijediDo", NULL), 
						post("putovnicaVrijediDo", NULL), "S", NULL, post("MBG", NULL), post("OIB", NULL), session("auth"), 
						post("aktivanDokument", "0"));

				// okay person added now let's add competition data
				if (post("tip") === 'D') {
					$sudjelovanje->addRow($osoba->getPrimaryKey(), $idElektrijade, post("tip", "D"), post("idVelicine", NULL), 
							post("idGodStud", NULL), NULL, post("idRadnogMjesta", NULL), post("idZavoda", NULL), NULL);
				} else {
					$sudjelovanje->addRow($osoba->getPrimaryKey(), $idElektrijade, post("tip", "S"), post("idVelicine", NULL), 
							post("idGodStud", NULL), post("idSmjera", NULL), NULL, NULL, NULL);
				}

				// now lets add him to be part of the team
				if(post("idPodrucja") !== false && (post("option") === '0' || post("option") === '2')) {
					$podrucjeSudjelovanja = new \model\DBPodrucjeSudjelovanja();
					$podrucjeSudjelovanja->addRow(post("idPodrucja"), $sudjelovanje->getPrimaryKey(), NULL, 
							post("vrstaPodrucja", "0"), NULL, NULL);
				}
				
				if (post("idPodrucja") !== false && post("idAtributa") !== false && (post("option") === '1' || post("option") === '2')) {
					foreach (post("idAtributa") as $k => $v) {
						$imaAtribut = new \model\DBImaatribut();
						$imaAtribut->addRow(post("idPodrucja"), $v, $sudjelovanje->getPrimaryKey());
					}
				}

				// everything's okay redirect
				preusmjeri(\route\Route::get("d3")->generate(array(
					"controller" => "ozsn",
					"action" => "searchContestants"
				)) . "?msg=succa&a=1");
			} catch (app\model\NotFoundException $e) {
				$this->createMessage("Nepoznati identifikator!", "d3", "ozsn", "addContestant");
			} catch (\PDOException $e) {
				$handler = new \model\ExceptionHandlerModel($e);
				$this->createMessage($handler, "d3", "ozsn", "addContestant");
			}
		} 
		
		echo new \view\Main(array(
			"title" => "Dodavanje Sudionika",
			"body" => new \view\ozsn\AddContestant(array(
				"errorMessage" => $this->errorMessage,
				"resultMessage" => $this->resultMessage,
				"idPodrucja" => $idPodrucja,
				"radnaMjesta" => $mjesta,
				"velicine" => $velicine,
				"smjerovi" => $smjerovi,
				"godine" => $godine,
				"zavodi" => $zavodi,
				"podrucja" => $podrucja,
				"atributi" => $atributi
			))
		));
	}
	
	public function modifyContestant() {
		$this->checkRole();
		$this->checkMessages();
		
		$osoba = new \model\DBOsoba();
		$sudjelovanje = new \model\DBSudjelovanje();
		$velicina = new \model\DBVelMajice();
		$smjer = new \model\DBSmjer();
		$godina = new \model\DBGodStud();
		$mjesto = new \model\DBRadnoMjesto();
		$zavod = new \model\DBZavod();
		$podrucje = new \model\DBPodrucje();
		$atribut = new \model\DBAtribut();
		$elektrijada = new \model\DBElektrijada();
		$podrucjeSudjelovanja = new \model\DBPodrucjeSudjelovanja();
		$imaatribut = new \model\DBImaatribut();
		
		$smjerovi = null;
		$zavodi = null;
		$velicine = null;
		$godine = null;
		$mjesta = null;
		$atributi = null;
		$podrucja = null;
		$podrucjaSudjelovanja = null;
		$imaAtribute = null;
		$korisnikovaPodrucja = null;
		
		if (postEmpty()) {
			// display data
			$this->idCheck("searchContestants");
			
			try {
				$osoba->load(get("id"));
				
				$godine = $godina->getAllGodStud();
				$zavodi = $zavod->getAllZavod();
				$smjerovi = $smjer->getAllSmjer();
				$velicine = $velicina->getAllVelicina();
				$mjesta = $mjesto->getAllRadnoMjesto();
				$podrucja = $podrucje->getAll();
				$atributi = $atribut->getAllAtributes();
				
				$idElektrijade = $elektrijada->getCurrentElektrijadaId();
				$sudjelovanje->loadByContestant($osoba->getPrimaryKey(), $idElektrijade);
				
				$korisnikovaPodrucja = $podrucja;
				
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
				
				$podrucjaSudjelovanja = $podrucjeSudjelovanja->getAllContestantFields($sudjelovanje->getPrimaryKey());
				$imaAtribute = $imaatribut->getAllContestantAttributes($sudjelovanje->getPrimaryKey());
				
			} catch (app\model\NotFoundException $e) {
				$this->createMessage("Nepoznati sudionik", "d3", "ozsn", "searchContestants");
			} catch (\PDOException $e) {
				$handler = new \model\ExceptionHandlerModel($e);
				$this->createMessage($handler, "d3", "ozsn", "searchContestants");
			}
		} else {
			
			try {
				$validacija = new \model\PersonFormModel(array(
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
						"action" => "modifyContestant"
					)) . "?msg=excep&id=" . post("idOsobe"));
				}
				
				// everything's okay lets do the modifying
				
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
							"controller" => "ozsn",
							"action" => "modifyContestant"
						)) . "?msg=excep&id=" . post("idOsobe"));
					}
					if(!is_uploaded_file(files("tmp_name", "datoteka"))) {
						$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Morate poslati datoteku!");
						$_SESSION["exception"] = serialize($handler);
						preusmjeri(\route\Route::get('d3')->generate(array(
							"controller" => "ozsn",
							"action" => "modifyContestant"
						)) . "?msg=excep&id=" . post("idOsobe"));
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
							"controller" => "ozsn",
							"action" => "modifyContestant"
						)) . "?msg=excep&id=" . post("idOsobe"));
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
				preusmjeri(\route\Route::get('d3')->generate(array(
					"controller" => "ozsn",
					"action" => "searchContestants"
				)) . "?msg=succm&a=1");
			} catch (app\model\NotFoundException $e) {
				$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Nepoznati sudionik");
				$_SESSION["exception"] = serialize($handler);
				preusmjeri(\route\Route::get('d3')->generate(array(
					"controller" => "ozsn",
					"action" => "modifyContestant"
				)) . "?msg=excep&id=" . post("idOsobe"));
			} catch (\PDOException $e) {
				$handler = new \model\ExceptionHandlerModel($e);
				$_SESSION["exception"] = serialize($handler);
				preusmjeri(\route\Route::get('d3')->generate(array(
					"controller" => "ozsn",
					"action" => "modifyContestant"
				)) . "?msg=excep&id=" . post("idOsobe"));
			}
		}
		
		echo new \view\Main(array(
			"title" => "Ažuriranje Sudionika",
			"body" => new \view\ozsn\ModifyContestant(array(
				"errorMessage" => $this->errorMessage,
				"resultMessage" => $this->resultMessage,
				"osoba" => $osoba,
				"podrucjeSudjelovanja" => $podrucjeSudjelovanja,
				"sudjelovanje" => $sudjelovanje,
				"imaatribut" => $imaatribut,
				"imaAtribute" => $imaAtribute,
				"podrucjaSudjelovanja" => $podrucjaSudjelovanja,
				"smjerovi" => $smjerovi,
				"zavodi" => $zavodi,
				"velicine" => $velicine,
				"mjesta" => $mjesta,
				"godine" => $godine,
				"velicina" => $velicina,
				"godina" => $godina,
				"mjesto" => $mjesto,
				"zavod" => $zavod,
				"smjer" => $smjer,
				"podrucja" => $podrucja,
				"atributi" => $atributi,
				"korisnikovaPodrucja" => $korisnikovaPodrucja
			))
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
			$this->getParamCheck("idS", "searchContestants");
			$this->getParamCheck("idP", "searchContestants");
			$this->getParamCheck("vrsta", "searchContestants");
			$idSudjelovanja = get("idS");
			$idPodrucja = get("idP");
			$vrsta = get("vrsta");
			
			try {
				$sudjelovanje->load(get("idS"));
				$elektrijada = new \model\DBElektrijada();
				$idElektrijade = $elektrijada->getCurrentElektrijadaId();
				
				if ($sudjelovanje->idElektrijade != $idElektrijade)
					$this->createMessage("Ne možete mijenjati prošlogodišnje zapise!", "d3", "ozsn", "searchContestants");
				
				$podrucja = $podrucje->getAll();
				$atributi = $atribut->getAllAtributes();
				
				$podrucjeSudjelovanja = $podrucjeSudjelovanja->loadIfExists(get("idP"), get("idS"), get("vrsta"));
				
				$at = $imaatribut->getAllContestantAttributes(get("idS"));
				
				$korisnikoviAtributi = array();
				if (count($at)) {
					foreach ($at as $a) {
						if ($a->idPodrucja == get("idP"))
							$korisnikoviAtributi[] = $a->idAtributa;
					}
				}
				
			} catch (app\model\NotFoundException $e) {
				$this->createMessage("Nepoznati identifikator", "d3", "ozsn", "searchContestants");
			} catch (\PDOException $e) {
				$handler = new \model\ExceptionHandlerModel($e);
				$this->createMessage($handler, "d3", "ozsn", "searchContestants");
			}
		} else {
			$idSudjelovanja = post("idS");
			$idPodrucja = post("idP");
			$vrsta = post("vrsta");
			
			try {
				$validacija = new \model\formModel\AttributeFormModel(array("rezultatPojedinacni" => post("rezultatPojedinacni"),
																			"ukupanBrojSudionika" => post("ukupanBrojSudionika"),
																			"iznosUplate" => post("iznosUplate")));
				
				$pov = $validacija->validate();
				if ($pov !== true) {
					$handler = new \model\ExceptionHandlerModel(new \PDOException(), $validacija->decypherErrors($pov));
					$_SESSION["exception"] = serialize($handler);
					preusmjeri(\route\Route::get('d3')->generate(array(
						"controller" => "ozsn",
						"action" => "changeContestantAttributes"
					)) . "?msg=excep&idP=" . $idPodrucja . "&idS=" . $idSudjelovanja . "&vrsta=" . $vrsta);
				}
				
				if (post("rezultatPojedinacni", 0) > post("ukupanBrojSudionika", "0")) {
					$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Rezultat mora biti manji ili jednak broju sudionika!");
					$_SESSION["exception"] = serialize($handler);
					preusmjeri(\route\Route::get('d3')->generate(array(
						"controller" => "ozsn",
						"action" => "changeContestantAttributes"
					)) . "?msg=excep&idP=" . $idPodrucja . "&idS=" . $idSudjelovanja . "&vrsta=" . $vrsta);
				}
				
				// everythings okay lets add
				$podrucjeSudjelovanja = $podrucjeSudjelovanja->loadIfExists(post("idP"), post("idS"), post("vrsta"));
				if ($podrucjeSudjelovanja->getPrimaryKey() !== null) {
					$podrucjeSudjelovanja->modifyRow($podrucjeSudjelovanja->getPrimaryKey(), FALSE, 
							FALSE, post("rezultatPojedinacni", NULL), FALSE, post("ukupanBrojSudionika", NULL), 
							post("iznosUplate", NULL), post("valuta", "HRK"));
				} else {
					// add a new one
					$podrucjeSudjelovanja->addRow(post("idP"), post("idS"), post("rezultatPojedinacni", NULL), post("vrsta"),
							post("ukupanBrojSudionika", NULL), post("iznosUplate", NULL), post("valuta", "HRK"));
				}
				
				// now lets modify the attributes
				// first delete the old ones, and after that add new
				$imaatribut->deleteContestantsAttributes($idSudjelovanja, $idPodrucja);
				
				// now add the new ones if any
				foreach (post("idAtributa") as $k => $v) {
					if ($v !== '') {
						$imaatribut = new \model\DBImaatribut();
						$imaatribut->addRow($idPodrucja, $v, $idSudjelovanja);
					}
				}
				
				// success redirect
				preusmjeri(\route\Route::get("d3")->generate(array(
					"controller" => "ozsn",
					"action" => "searchContestants"
				)) . "?a=1&msg=succm");
			} catch (app\model\NotFoundException $e) {
				$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Nepoznati identifikator");
				$_SESSION["exception"] = serialize($handler);
				preusmjeri(\route\Route::get('d3')->generate(array(
					"controller" => "ozsn",
					"action" => "changeContestantAttributes"
				)) . "?msg=excep&idP=" . $idPodrucja . "&idS=" . $idSudjelovanja . "&vrsta=" . $vrsta);
			} catch (\PDOException $e) {
				$handler = new \model\ExceptionHandlerModel($e);
				$_SESSION["exception"] = serialize($handler);
				preusmjeri(\route\Route::get('d3')->generate(array(
					"controller" => "ozsn",
					"action" => "changeContestantAttributes"
				)) . "?msg=excep&idP=" . $idPodrucja . "&idS=" . $idSudjelovanja . "&vrsta=" . $vrsta);
			}
		}
		
		echo new \view\Main(array(
			"title" => "Ažuriranje Atributa",
			"body" => new \view\ozsn\ContestantAttributes(array(
				"errorMessage" => $this->errorMessage,
				"resultMessage" => $this->resultMessage,
				"podrucjeSudjelovanja" => $podrucjeSudjelovanja,
				"podrucja" => $podrucja,
				"atributi" => $atributi,
				"korisnikoviAtributi" => $korisnikoviAtributi,
				"idSudjelovanja" => $idSudjelovanja,
				"idPodrucja" => $idPodrucja,
				"vrsta" => $vrsta
			))
		));
	}
	
	public function deleteContestant() {
		$this->checkRole();
		$this->checkMessages();
		
		$this->idCheck("searchContestants");
		
		try {
			$osoba = new \model\DBOsoba();
			$osoba->load(get("id"));
			if ($osoba->uloga === 'S') {
				$osoba->delete();
				preusmjeri(\route\Route::get("d3")->generate(array(
					"controller" => "ozsn",
					"action" => "searchContestants"
				)) . "?msg=succd&a=1");
			} else {
				$this->createMessage("Osoba nije sudionik!", "d3", "ozsn", "searchContestants");
			}
		} catch (app\model\NotFoundException $e) {
			$this->createMessage("Nepoznati sudionik!", "d3", "ozsn", "searchContestants");
		} catch (\PDOException $e) {
			$handler = new \model\ExceptionHandlerModel($e);
			$this->createMessage($handler, "d3", "ozsn", "searchContestants");
		}
	}
	
	public function displayCollectedMoney() {
		$this->checkRole();
		$this->checkMessages();
		
		try {
			$podrucje = new \model\DBPodrucje();
			$podrucja = $podrucje->getAll();
		} catch (app\model\NotFoundException $e) {
			$this->createMessage("Nepoznati identifikator");
		} catch (\PDOException $e) {
			$handler = new \model\ExceptionHandlerModel($e);
			$this->createMessage($handler);
		}
		
		echo new \view\Main(array(
			"title" => "Uplate Po Disciplinama",
			"body" => new \view\ozsn\CollectedMoneyList(array(
				"errorMessage" => $this->errorMessage,
				"resultMessage" => $this->resultMessage,
				"podrucja" => $podrucja
			))
		));
	}
	
	public function disciplineMoney() {
		$this->checkRole();
		$this->checkMessages();
		
		$podrucje = new \model\DBPodrucje();
		$podrucjeSudjelovanja = new \model\DBPodrucjeSudjelovanja();
		$elektrijada = new \model\DBElektrijada();
		
		if (postEmpty()) {
			$this->idCheck("displayCollectedMoney");
			$idPodrucja = get("id");
			
			try {
				$podrucje->load(get("id"));
				$naziv = $podrucje->nazivPodrucja;
				
				$idElektrijade = $elektrijada->getCurrentElektrijadaId();
				$osobe = $podrucjeSudjelovanja->getCollectedMoney($idPodrucja, $idElektrijade);
			} catch (app\model\NotFoundException $e) {
				$this->createMessage("Nepoznati identifikator!", "d3", "ozsn", "displayCollectedMoney");
			} catch (\PDOException $e) {
				$handler = new \model\ExceptionHandlerModel($e);
				$this->createMessage($handler, "d3", "ozsn", "displayCollectedMoney");
			}
		} else {
			// process query
			$idPodrucja = post("idPodrucja");
			try {
				$podrucje->load($idPodrucja);
				$naziv = $podrucje->nazivPodrucja;
				foreach($_POST as $k => $r) {
					if ($k !== "idPodrucja" && $k[0] !== "v" && $r !== '') {
						$validacija = new \model\formModel\NumberValidationModel(array("decimal" => $r));
						$validacija->setRules(array("decimal" => array("decimal")));
						$pov = $validacija->validate();
						if ($pov !== true) {
							$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Iznos može biti samo brojčana vrijednost!");
							$_SESSION["exception"] = serialize($handler);
							preusmjeri(\route\Route::get('d3')->generate(array(
								"controller" => "ozsn",
								"action" => "disciplineMoney"
							)) . "?msg=excep&id=" . $idPodrucja);
						}
					}
				}
				
				// everything's okay lets add
				foreach($_POST as $k => $r) {
					if ($k !== "idPodrucja" && $k[0] !== "v") {
						$podrucjeSudjelovanja->modifyRow($k, FALSE, FALSE, FALSE, FALSE, FALSE, post($k, NULL), post("valuta" . $k, "HRK"));
					}
				}
				
				// success -> redirect
				preusmjeri(\route\Route::get('d3')->generate(array(
								"controller" => "ozsn",
								"action" => "disciplineMoney"
							)) . "?msg=succm&id=" . post("idPodrucja"));
			} catch (app\model\NotFoundException $e) {
				$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Nepoznati identifikator");
				$_SESSION["exception"] = serialize($handler);
				preusmjeri(\route\Route::get('d3')->generate(array(
					"controller" => "ozsn",
					"action" => "disciplineMoney"
				)) . "?msg=excep&id=" . $idPodrucja);
			} catch (\PDOException $e) {
				$handler = new \model\ExceptionHandlerModel($e);
				$_SESSION["exception"] = serialize($handler);
				preusmjeri(\route\Route::get('d3')->generate(array(
					"controller" => "ozsn",
					"action" => "disciplineMoney"
				)) . "?msg=excep&id=" . $idPodrucja);
			}
		}
		
		if (get("type") !== false) {
			$pomPolje = array("Ime", "Prezime", "JMBAG", "OIB", "Iznos", "Valuta");
			$array = array();
			$array[] = $pomPolje;
			
			if ($osobe !== null && count($osobe)) {
				foreach ($osobe as $v) {
					$array[] = array($v->ime, $v->prezime, $v->JMBAG, $v->OIB, $v->iznosUplate, $v->valuta);
				}
			}
			
			$path = $this->generateFile(get("type"), $array);
			
			echo new \view\ShowFile(array(
				"path" => $path,
				"type" => get("type")
			));
		}
		
		echo new \view\Main(array(
			"title" => $naziv,
			"body" => new \view\ozsn\DisciplineMoney(array(
				"errorMessage" => $this->errorMessage,
				"resultMessage" => $this->resultMessage,
				"osobe" => $osobe,
				"idPodrucja" => $idPodrucja
			))
		));
	}
	
	public function displayMoneySum() {
		$this->checkRole();
		$this->checkMessages();
		
		if (get("x") !== false) {
			try {
				$podrucjeSudjelovanja = new \model\DBPodrucjeSudjelovanja();
				$e = new \model\DBElektrijada();
				$idElektrijade = $e->getCurrentElektrijadaId();
				$novci = $podrucjeSudjelovanja->getMoneyStatistics($idElektrijade);
				
				$ukupno = $podrucjeSudjelovanja->getAllMoney($idElektrijade);
				$ukupno = $ukupno[0]->suma;
			} catch (\PDOException $e) {
				$handler = new \model\ExceptionHandlerModel($e);
				$this->createMessage($handler, "d3", "ozsn", "displayCollectedMoney");
			}
		} else {
			$this->createMessage("Greska autorizacije!", "d3", "ozsn", "displayCollectedMoney");
		}
		
		if (get("type") !== false) {
			$pomPolje = array("Podruje", "Ukupno");
			$array = array();
			$array[] = $pomPolje;
			
			if ($novci !== null && count($novci)) {
				foreach ($novci as $v) {
					$array[] = array($v->nazivPodrucja, $v->suma === null ? 0 :  $v->suma);
				}
			}
			$array[] = array("Ukupno", $ukupno === null ? 0 : $ukupno);
			
			$path = $this->generateFile(get("type"), $array);
			
			echo new \view\ShowFile(array(
				"path" => $path,
				"type" => get("type")
			));
		}
		
		echo new \view\Main(array(
			"title" => "Prikupljena Sredstva",
			"body" => new \view\ozsn\CollectedMoney(array(
				"errorMessage" => $this->errorMessage,
				"resultMessage" => $this->resultMessage,
				"ukupno" => $ukupno,
				"podrucja" => $novci
			))
		));
	}
	
	public function modifyElektrijada() {
		$this->checkRole();
		$this->checkMessages();
		
		$elektrijada = new \model\DBElektrijada();
		try {
			$idElektrijada = $elektrijada->getCurrentElektrijadaId();
			$elektrijada->load($idElektrijada);
		} catch (app\model\NotFoundException $e) {
			$this->createMessage("Nepoznata Elektrijada!");
		} catch (\PDOException $e) {
			$handler = new \model\ExceptionHandlerModel($e);
			$this->createMessage($handler);
		}
		
		if (!postEmpty()) {
			// modify Data
			$validacija = new \model\ElektrijadaFormModel(array(
                                                'mjestoOdrzavanja' => post('mjestoOdrzavanja'),
												'ukupniRezultat' => post('ukupniRezultat'),
												'drzava' => post('drzava'),
												'ukupanBrojSudionika' => post('ukupanBrojSudionika')
                                            ));
            $pov = $validacija->validate();
            if($pov !== true) {
				$this->createMessage($validacija->decypherErrors($pov), "d3", "ozsn", "modifyElektrijada");
			}
			
			if (post("ukupniRezultat", 0) > post("ukupanBrojSudionika", 0)) {
				$this->createMessage("Rezultat mora biti manji ili jednak ukupnom broju sudionika!", "d3", "ozsn", "modifyElektrijada");
			}
			
			try {
				$elektrijada->modifyRow($elektrijada->getPrimaryKey(), post('mjestoOdrzavanja', NULL), $elektrijada->datumPocetka, 
						$elektrijada->datumKraja, post('ukupniRezultat', NULL), post('drzava', NULL), $elektrijada->rokZaZnanje,
						$elektrijada->rokZaSport, post('ukupanBrojSudionika', NULL));
				
				// redirect with according message
				preusmjeri(\route\Route::get('d1')->generate() . "?msg=succel");
			} catch(\PDOException $e) {
				$handler = new \model\ExceptionHandlerModel($e);
				$this->createMessage($handler, "d3", "ozsn", "modifyElektrijada");
			}
		}
		
		echo new \view\Main(array(
			"title" => "Elektrijada",
			"body" => new \view\ozsn\ElektrijadaData(array(
				"errorMessage" => $this->errorMessage,
				"resultMessage" => $this->resultMessage,
				"elektrijada" => $elektrijada
			))
		));
	}
}
