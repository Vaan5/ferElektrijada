<?php

namespace ctl;
use app\controller\Controller;
use \PDOException;

class Ozsn implements Controller {
    
    private $errorMessage;
    private $resultMessage;
    
    private function checkRole() {
        // you must be logged in, and an Ozsn member with or without leadership
        if (!(\model\DBOsoba::isLoggedIn() && (\model\DBOsoba::getUserRole() === 'O' || \model\DBOsoba::getUserRole() === 'OV'))) {
            preusmjeri(\route\Route::get('d1')->generate() . "?msg=accessDenied");
        }
    }
    
    /**
     * function to check if get("id") is a number
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
	    $handler = new \model\ExceptionHandlerModel($e, "Da biste dodali logotip, morate unijeti i podatke o sponzoru!");
	    $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "addSponzor"
            )) . "?msg=excep");
	}
	
	// check if the image is too big
	if (files("tmp_name", "datoteka") !== false && files("size", "datoteka") > 1024 * 1024) {
	    $handler = new \model\ExceptionHandlerModel($e, "Veličina slike mora biti manja od 1MB!");
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
		    $mime = null;
		    $finfo = null;
		    // check file type -finfo extension needs to be enables in PHP
		    if(function_exists('finfo_file')) {
			$finfo = \finfo_open(FILEINFO_MIME_TYPE);
			$mime = finfo_file($finfo, files("tmp_name", "datoteka"));
		    } else {
			$mime = \mime_content_type(files("tmp_name", "datoteka"));
		    }
		    
		    if($mime != 'image/jpeg') {
			$handler = new \model\ExceptionHandlerModel($e, "Možete poslati logotip samo u jpeg (jpg) formatu!");
			$_SESSION["exception"] = serialize($handler);
			preusmjeri(\route\Route::get('d3')->generate(array(
			    "controller" => "ozsn",
			    "action" => "addSponzor"
			)) . "?msg=excep");
		    }
		    
		    // now i should add the file to my file system
		    $img = imagecreatefromjpeg(files("tmp_name", "datoteka"));
		    if ($img === false) {
			$handler = new \model\ExceptionHandlerModel($e, "Ne mogu obraditi predani logo!");
			$_SESSION["exception"] = serialize($handler);
			preusmjeri(\route\Route::get('d3')->generate(array(
			    "controller" => "ozsn",
			    "action" => "addSponzor"
			)) . "?msg=excep");
		    }
		    
		    // resize logo
		    list($width, $height) = getimagesize(files("tmp_name", "datoteka"));
		    $image_p = imagecreatetruecolor(300, 200);
		    imagecopyresampled($image_p, $img, 0, 0, 0, 0, 300, 200, $width, $height);
		    
		    // save resized image
		    $putanja = "./logotip/" . $idSponzora . ".jpeg";
		    $pov = imagejpeg($image_p, $putanja);
		    if ($pov !== true) {
			// add path to db
			$sponzor->addLogo($idSponzora, $putanja);			
		    } else {
			$handler = new \model\ExceptionHandlerModel($e, "Problem s spremanjem slike!");
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
		$kategorija->load($imaSponzora->idKategorijeSponzora);
		$promocija->load($imaSponzora->idPromocije);
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
	
	// check if the image is too big
	if (files("tmp_name", "datoteka") !== false && files("size", "datoteka") > 1024 * 1024) {
	    $handler = new \model\ExceptionHandlerModel($e, "Veličina slike mora biti manja od 1MB!");
	    $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "modifySponzor"
            )) . "?msg=excep&id=" . get("id"));
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
		    $mime = null;
		    $finfo = null;
		    // check file type -finfo extension needs to be enables in PHP
		    if(function_exists('finfo_file')) {
			$finfo = \finfo_open(FILEINFO_MIME_TYPE);
			$mime = finfo_file($finfo, files("tmp_name", "datoteka"));
		    } else {
			$mime = \mime_content_type(files("tmp_name", "datoteka"));
		    }
		    
		    if($mime != 'image/jpeg') {
			$handler = new \model\ExceptionHandlerModel($e, "Možete poslati logotip samo u jpeg (jpg) formatu!");
			$_SESSION["exception"] = serialize($handler);
			preusmjeri(\route\Route::get('d3')->generate(array(
			    "controller" => "ozsn",
			    "action" => "modifySponzor"
			)) . "?msg=excep&id=" . get("id"));
		    }
		    
		    // now i should add the file to my file system
		    $img = imagecreatefromjpeg(files("tmp_name", "datoteka"));
		    if ($img === false) {
			$handler = new \model\ExceptionHandlerModel($e, "Ne mogu obraditi predani logo!");
			$_SESSION["exception"] = serialize($handler);
			preusmjeri(\route\Route::get('d3')->generate(array(
			    "controller" => "ozsn",
			    "action" => "modifySponzor"
			)) . "?msg=excep&id=" . get("id"));
		    }
		    
		    // resize logo
		    list($width, $height) = getimagesize(files("tmp_name", "datoteka"));
		    $image_p = imagecreatetruecolor(300, 200);
		    imagecopyresampled($image_p, $img, 0, 0, 0, 0, 300, 200, $width, $height);
		    
		    // save resized image over the old one if there was any
		    $putanja = "./logotip/" . $idSponzora . ".jpeg";
		    $pov = imagejpeg($image_p, $putanja);
		    if ($pov !== true) {
			// add path to db
			$sponzor->addLogo($idSponzora, $putanja);			
		    } else {
			$handler = new \model\ExceptionHandlerModel($e, "Problem s spremanjem slike!");
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
		$kategorija->load($imaSponzora->idKategorijeSponzora);
		$promocija->load($imaSponzora->idPromocije);
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
	    $elektrijada = new \model\DBElektrijada();
	    $id = $elektrijada->getCurrentElektrijadaId();
            $spon->deleteAreaRow(get("id"), $id);
            
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
        $mail = new \model\DBEmailAdrese();
        $mob = new \model\DBBrojeviMobitela();
        $kontak = new \model\DBKontaktOsobe();
        
        // get company data and sponsor data
        $tvrtke = $tvrtka->getAll();
        $sponzori = $sponzor->getAll();
        
        if (!postEmpty()) {
            $validacija = new \model\formModel\KontaktOsobeFormModel(array(
                "imeKontakt" => post("imeKontakt"),
                "prezimeKontakt" => post("prezimeKontakt"),
                "telefon" => post("telefon"),
                "radnoMjesto" => post("radnoMjesto"),
                "idTvrtke" => post("idTvrtke"),
                "idSponzora" => post("idSponzora")
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
            
            // check if atleast one idTvrtke or idSponzora is given
            if (post('idTvrtke') === false && false === post('idSponzora')) {
                $handler = new \model\ExceptionHandlerModel(new \PDOException(), "Morate odabrati barem jednog sponzora ili tvrtku!");
                $_SESSION["exception"] = serialize($handler);
                preusmjeri(\route\Route::get('d3')->generate(array(
                    "controller" => "ozsn",
                    "action" => "addContact"
                 )) . "?msg=excep");
            }
            
            // now we check the mail addresses and phone numbers
            // if you entered a number that already exists we won't add another one, just gonna add it
            $i = 1;
            while (post("mob" . $i) !== false) {
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
                $i = $i + 1;
            }
            
            $k = 1;
            while (post("mail" . $i) !== false) {
                $validator = new \model\formModel\NumberValidationModel(array("mail" => post("mail" . $k)));
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
                $k = $k + 1;
            }
            
            // now i have checked all of the data, next i go add the new contact
            try {
                $kontak->addNewContact(post("imeKontakt"), post("prezimeKontakt"), post("telefon", null), post('radnoMjesto', null),
                        post('idTvrtke', NULL), post('idSponzora', null));
                // now lets add the phone numbers and e-mails
                for ($j = 1; $j < $i; $j = $j + 1) {
                    $mob->addNewOrIgnore($kontak->getPrimaryKey(), post("mob" . $j));
                }
                
                for ($j = 1; $j < $k; $j = $j + 1) {
                    $mail->addNewOrIgnore($kontak->getPrimaryKey(), post("mail" . $j));
                }
                
                preusmjeri(\route\Route::get('d1')->generate() . "msg=succContact");
                
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
                        "sponzori" => $sponzori)),
            "title" => "Dodavanje Kontakta"
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
                "kontakti" => $kontakti
            )),
            "title" => "Kontakt Osobe"
        ));
    }
    
    /**
     * Deletes contact via get request
     */
    public function deleteContact() {
        $this->checkRole();
        
        if (get('id') === false) {
            $handler = new \model\ExceptionHandlerModel(new \PDOException(), "Nepoznati zapis!");
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "ozsn",
                "action" => "displayAtribut"
            )) . "?msg=excep");
        }
        
        $kontakt = new \model\DBKontaktOsobe();
        try {
            $kontakt->deleteRow(get("id"));
            
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
                "naziv" => $nazivi
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
        $validacija = new \model\formModel\ZavodFormModel(array('nazivZavoda' => post("nazivZavoda"),'skraceniNaziv'=>post("SkraceniNaziv")));
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
        $validacija = new \model\formModel\ZavodFormModel(array('nazivZavoda' => post("nazivZavoda"),'skraceniNaziv'=>post("SkraceniNaziv")));
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
	
}
