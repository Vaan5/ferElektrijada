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
                "nacini" => $kategorije
            )),
            "title" => "Kategorije Sponzora",
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
	
	
}
