<?php

namespace ctl;
use app\controller\Controller;

class Administrator implements Controller {
    
    private $errorMessage;
    private $resultMessage;
    
    private function checkRole() {
        // if you don't have the right permissions to do this, then get out of here
        if (!\model\DBOsoba::isLoggedIn() || \model\DBOsoba::getUserRole() !== 'A') {
            preusmjeri(\route\Route::get('d1')->generate() . "?msg=accessDenied");
        }
    }
    
    private function messageExists() {
        if ($this->errorMessage !== null || $this->resultMessage !== null)
            return true;
        return false;
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
    
    private function checkMessages() {
        switch(get("msg")) {
            case 'succ':
                $this->resultMessage = "Uspješno izmijenjena postojeća Elektrijada!";
                break;
            case 'succo':
                $this->resultMessage = "Uspješno ažurirani podaci o članu odbora!";
                break;
            case 'succp':
                $this->resultMessage = "Uspješno ažurirani podaci o osobi!";
                break;
            case 'del':
                $this->resultMessage = "Uspješno izbrisana Elektrijada!";
                break;
            case 'err':
                $this->errorMessage = "Zahtjevani zapis ne postoji!";
                break;
            case 'delo':
                $this->resultMessage = "Uspješno izbrisan član odbora!";
                break;
            case 'delp':
                $this->resultMessage = "Uspješno izbrisana osoba!";
                break;
            case 'derr':
                $this->errorMessage = "Dogodila se pogreška prilikom brisanja! Pokušajte ponovno!";
                break;
            case 'noel':
                $this->errorMessage = "Članove možete dodavati samo za trenutno aktivnu elektrijadu! Najprije stvorite novu elektrijadu!";
                break;
            case 'param':
                $this->errorMessage = "Popunite parametre pretrage!";
                break;
            case 'remSucc':
                $this->resultMessage = "Osoba uklonjena iz ovogodišnjeg odbora!";
                break;
            case 'passn':
                $this->errorMessage = "Morate unijeti lozinku";
                break;
            case 'passw':
                $this->errorMessage = "Neispravna lozinka";
                break;
            case 'pog':
                $this->errorMessage = "Dogodila se pogreška! Pokušajte ponovno kasnije!";
                break;
            case 'succProm':
                $this->resultMessage = "Uspješno promijenjena uloga osobe!";
                break;
            case 'alrO':
                $this->resultMessage = "Osoba je već član odbora!";
                break;
			case 'ozsnAddedSucc':
				$this->resultMessage = "Uspješno dodan član odbora!";
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
     * Action being called when the admin wants to change his personal data
     */
    public function changeProfile() {
        $this->checkRole();
        $this->checkMessages();
        $osoba = new \model\DBOsoba();
        
        if(!postEmpty()) {
            // here we do the magic
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
                $this->errorMessage = $validacija->decypherErrors($pov);
            } else {
                // everything's ok ; insert new row
                // first check for passwords
                if (post("password") !== false) {
                    if (post("password_new") !== false && post("password_new2") !== false) {
                        $pov = $osoba->checkAdmin(post("password"));
                        if ($pov === false || $pov->getPrimaryKey() != session('auth')) {
                            $handler = new \model\ExceptionHandlerModel(new \PDOException(), "Pogrešna stara lozinka");
							$this->createMessage($handler, "d3", "administrator", "changeProfile");
                        }
                        if (post("password_new") !== post("password_new2")) {
                            $handler = new \model\ExceptionHandlerModel(new \PDOException(), "Nove lozinke se ne podudaraju");
                            $this->createMessage($handler, "d3", "administrator", "changeProfile");
                        }
                    } else {
                        $handler = new \model\ExceptionHandlerModel(new \PDOException(), "Ukoliko mijenjate lozinku, morate unijeti staru, kao i novu lozinku!");
                        $this->createMessage($handler, "d3", "administrator", "changeProfile");
                    }
                } else {
                    if(post("password_new") !== false || post("password_new2") !== false) {
                        $handler = new \model\ExceptionHandlerModel(new \PDOException(), "Morate unijeti staru lozinku");
                        $this->createMessage($handler, "d3", "administrator", "changeProfile");
                    }
                }
                try {
                    $osoba->modifyRow(session("auth"), post('ime', null), post('prezime', null), post('mail', null), post('brojMob', null), post('ferId'), post('password_new', null), 
                        post('JMBAG', null), post('spol', null), post('datRod', null), post('brOsobne', null), post('brPutovnice', null), post('osobnaVrijediDo', null),
                        post('putovnicaVrijediDo', null), 'A', NULL, post('MBG', null), post('OIB', null), post("aktivanDokument", "0"));
                    // redirect with according message
					$_SESSION["user"] = $osoba->ime === NULL ? null : $osoba->ime;
                    preusmjeri(\route\Route::get('d1')->generate() . "?msg=profSucc");
                } catch(\PDOException $e) {
                    $handler = new \model\ExceptionHandlerModel($e);
                    $this->createMessage($handler, "d3", "administrator", "changeProfile");
                }
            }
        }
        // let's check if i got an existing table key and let's do magic
        try {
            $osoba->load(session("auth"));
        } catch (\app\model\NotFoundException $e) {
            preusmjeri(\route\Route::get('d1')->generate() . "?msg=e");
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $this->createMessage($handler, "d3", "administrator", "changeProfile");
        }
        
        echo new \view\Main(array(
            "body" => new \view\administrator\AdminProfile(array(
					"errorMessage" => $this->errorMessage,
					"resultMessage" => $this->resultMessage,
					"admin" => $osoba
				)),
            "title" => "Uređivanje profila",
            "script" => new \view\scripts\PersonFormJs(array(
				"modification" => true
			))
        ));
    }

    /**
     * adds new Ozsn member
     */
    public function addOzsn() {
        $this->checkRole();
        $this->checkMessages();
        
        // if you want to add someone
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
                $this->errorMessage = $validacija->decypherErrors($pov);
            } else {
                // everything is ok i add the person
                $osoba = new \model\DBOsoba();
                try {
                    $elektrijada = new \model\DBElektrijada();
                    if(($idElektrijade = $elektrijada->getCurrentElektrijadaId()) === false) {
                        // we can only add ozsn members for the current Elektrijada
                        preusmjeri(\route\Route::get('d3')->generate(array(
                            "controller" => "administrator",
                            "action" => "displayElektrijada"
                        )) . "?msg=noel");
                    }
                    $osoba->addNewPerson(post('ime', null), post('prezime', null), post('mail', null), post('brojMob', null), post('ferId'), post('password'), 
                        post('JMBAG', null), post('spol', null), post('datRod', null), post('brOsobne', null), post('brPutovnice', null), post('osobnaVrijediDo', null),
                        post('putovnicaVrijediDo', null), 'O', NULL, post('MBG', null), post('OIB', null), session("auth"), post("aktivanDokument", "0"));
                        // added successfully
                    // now assign them to the current Elektrijada
                    $obavlja = new \model\DBObavljaFunkciju();
                    $obavlja->addNewRow($osoba->getPrimaryKey(), NULL, $idElektrijade);
                    preusmjeri(\route\Route::get('d1')->generate() . "?msg=ozsnAddedSucc");
                } catch (\PDOException $e) {
                    $handler = new \model\ExceptionHandlerModel($e);
                    $this->errorMessage = $handler;
                }
            }
        }
        
        echo new \view\Main(array(
            "body" => new \view\administrator\OzsnAdding(array(
                    "errorMessage" => $this->errorMessage
                )),
            "title" => "Novi član",
            "script" => new \view\scripts\PersonFormJs()
        ));
        
    }
    
    /**
     * shows simple search form
     */
    public function searchOzsn() {
        $this->checkRole();
        $this->checkMessages();
        
        echo new \view\Main(array(
            "body" => new \view\administrator\OzsnSearch(array(
                "errorMessage" => $this->errorMessage,
                "resultMessage" => $this->resultMessage
            )),
            "title" => 'Pretraga'
        ));
    }
    
    /**
     * processes search query and displays results
     */
    public function displayOzsn() {
        $this->checkRole();
        $this->checkMessages();
        $osobe = array();
        $osoba = new \model\DBOsoba();
        
		try {
			if(!postEmpty()) {
				// search db
				// first validate input
				$validacija = new \model\SimplePersonSearchFormModel(array(
					'ferId' => post('ferId'),
					'ime' => post('ime'), 
					'prezime' => post('prezime')
				));

				$pov = $validacija->validate();
				if($pov !== true) {
					$this->errorMessage = $validacija->decypherErrors($pov);
				} else {
					// ok the data is correct now lets find what they're looking for
					$osobe = $osoba->findActiveOzsnMembers(post('ime'), post('prezime'), post('ferId'));
					$_SESSION['search'] = serialize(array(post('ime'), post('prezime'), post('ferId')));
					if($osobe === false)
						$this->errorMessage = "Nije pronađen niti jedan član!";
				}
			} else if (get("a") !== false) {
				// get all ozsn members
				$osobe = $osoba->getAllActiveOzsn();
				$_SESSION['search'] = serialize(array('a'));
				if($osobe === false)
					$this->errorMessage = "Ne postoji niti jedan član, koji zadovoljava zahtjeve pretrage!";
			} else if (get("type") !== false) {
				$polje = unserialize(session("search"));
				if (count($polje) == 1) {
					$osobe = $osoba->getAllActiveOzsn();
				} else {
					$osobe = $osoba->findActiveOzsnMembers($polje[0], $polje[1], $polje[2]);
				}
				
				$pomPolje = array("Ime", "Prezime", "Email", "Mobitel", "JMBAG", "Spol", "Datum rođenja", "Osobna iskaznica", "Vrijedi do",
					"Putovnica", "Vrijedi do", "Aktivan dokument", "MBO", "OIB");
				$array = array();
				$array[] = $pomPolje;

				if ($osobe && $osobe !== null && count($osobe)) {
					foreach ($osobe as $v) {
						$array[] = array($v->ime, $v->prezime, $v->mail, $v->brojMob, $v->JMBAG, ($v->spol == "M" ? "Muško" : "Žensko"),
							$v->datRod, $v->brOsobne, $v->osobnaVrijediDo, $v->brPutovnice, $v->putovnicaVrijediDo, 
									($v->aktivanDokument == "0" ? "Putovnica" : "Osobna"), $v->MBG, $v->OIB);
					}
				}

				$path = $this->generateFile(get("type"), $array);

				echo new \view\ShowFile(array(
					"path" => $path,
					"type" => get("type")
				));
			} else {
				preusmjeri(\route\Route::get('d3')->generate(array(
					"controller" => "administrator",
					"action" => "searchOzsn"
				)) . '?msg=param');
			}
		} catch (\app\model\NotFoundException $e) {
			$this->createMessage("Nepoznati identifikator!", "d3", "administrator", "searchOzsn");
		} catch (\PDOException $e) {
			$handler = new \model\ExceptionHandlerModel($e);
			$this->createMessage($handler, "d3", "administrator", "searchOzsn");
		}
        
        echo new \view\Main(array(
            "body" => new \view\administrator\OzsnList(array(
                "osobe" => $osobe,
                "errorMessage" => $this->errorMessage,
                "resultMessage" => $this->resultMessage
            )),
            "title" => "Popis članova Odbora",
            "script" => new \view\scripts\administrator\OzsnListJs
        ));
    }
    
    /**
     * changes ozsn member data
     */
    public function modifyOzsn() {
        $this->checkRole();
        $this->checkMessages();
        $osoba = new \model\DBOsoba();
        
        if(!postEmpty()) {
            // here we do the magic
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
                $this->errorMessage = $validacija->decypherErrors($pov);
                try {
                    $osoba->load(post('idOsobe'));
                } catch (\app\model\NotFoundException $e) {
                    
                } catch (\PDOException $e) {
                    $handler = new \model\ExceptionHandlerModel($e);
                    $this->errorMessage = $handler;
                }
            } else {
                // first check passwords
                if (post("password") !== false) {
                    if (post("password_new") !== false && post("password_new2") !== false) {
                        $pov = $osoba->checkAdmin(post("password"));
                        if ($pov === false || $pov->getPrimaryKey() != session('auth')) {
                            $handler = new \model\ExceptionHandlerModel(new \PDOException(), "Pogrešna stara lozinka");
                            $_SESSION["exception"] = serialize($handler);
                            preusmjeri(\route\Route::get('d3')->generate(array(
                                "controller" => "administrator",
                                "action" => "modifyOzsn"
                             )) . "?msg=excep&id=" . post("idOsobe"));
                        }
                        if (post("password_new") !== post("password_new2")) {
                            $handler = new \model\ExceptionHandlerModel(new \PDOException(), "Nove lozinke se ne podudaraju");
                            $_SESSION["exception"] = serialize($handler);
                            preusmjeri(\route\Route::get('d3')->generate(array(
                                "controller" => "administrator",
                                "action" => "modifyOzsn"
                             )) . "?msg=excep&id=" . post("idOsobe"));
                        }
                    } else {
                        $handler = new \model\ExceptionHandlerModel(new \PDOException(), "Ukoliko mijenjate lozinku, morate unijeti staru, kao i novu lozinku!");
                        $_SESSION["exception"] = serialize($handler);
                        preusmjeri(\route\Route::get('d3')->generate(array(
                            "controller" => "administrator",
                            "action" => "modifyOzsn"
                         )) . "?msg=excep&id=" . post("idOsobe"));
                    }
                } else {
                    if (post("password_new") !== false || post("password_new2") !== false) {
                        $handler = new \model\ExceptionHandlerModel(new \PDOException(), "Morate unijeti staru lozinku");
                        $_SESSION["exception"] = serialize($handler);
                        preusmjeri(\route\Route::get('d3')->generate(array(
                            "controller" => "administrator",
                            "action" => "modifyOzsn"
                         )) . "?msg=excep&id=" . post("idOsobe"));
                    }
                }
                
                // everything's ok ; insert new row
                try {
                    $osoba->modifyRow(post($osoba->getPrimaryKeyColumn()), post('ime', null), post('prezime', null), post('mail', null), post('brojMob', null), post('ferId'), post('password_new', null), 
                        post('JMBAG', null), post('spol', null), post('datRod', null), post('brOsobne', null), post('brPutovnice', null), post('osobnaVrijediDo', null),
                        post('putovnicaVrijediDo', null), 'O', NULL, post('MBG', null), post('OIB', null), post("aktivanDokument", "0"));
                    // redirect with according message
                    preusmjeri(\route\Route::get('d3')->generate(array(
                        "controller" => "administrator",
                        "action" => "searchOzsn"
                    )) . "?msg=succo");
                } catch(\PDOException $e) {
                    $handler = new \model\ExceptionHandlerModel($e);
                    $_SESSION["exception"] = serialize($handler);
                    preusmjeri(\route\Route::get('d3')->generate(array(
                        "controller" => "administrator",
                        "action" => "modifyOzsn"
                     )) . "?msg=excep&id=" . post("idOsobe"));
                }
            }
        } else {
            if(get("id") !== false) {
                // let's check if i got an existing table key and let's do magic
                try {
                    $osoba->load(get("id"));
                } catch (\app\model\NotFoundException $e) {
                    preusmjeri(\route\Route::get('d1')->generate() . "?msg=e");
                }
            } else {
                preusmjeri(\route\Route::get('d1')->generate() . "?msg=e");
            }
        }
        
        echo new \view\Main(array(
            "body" => new \view\administrator\OzsnModification(array(
                "errorMessage" => $this->errorMessage,
                "osoba" => $osoba
            )),
            "title" => "Ažuriranje Člana Odbora",
            "script" => new \view\scripts\PersonFormJs(array(
				"modification" => "true"
			))
        ));  
        
    }
    
    /**
     * removes row from table osoba
     */
    public function deleteOzsn() {
        $this->checkRole();
        $osoba = new \model\DBOsoba();
        
        if(get('id') === false) {
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "administrator",
                "action" => "searchOzsn"
            )) . "?msg=err");
        } else {
            try {
                $osoba->load(get('id'));
                
                if($osoba->deleteOsoba(get('id')) === false) {
                    preusmjeri(\route\Route::get('d3')->generate(array(
                        "controller" => "administrator",
                        "action" => "searchOzsn"
                    )) . "?msg=derr");
                }
                
                preusmjeri(\route\Route::get('d3')->generate(array(
                                    "controller" => "administrator",
                                    "action" => "searchOzsn"
                                )) . "?msg=delo");
            } catch (\app\model\NotFoundException $e) {
                preusmjeri(\route\Route::get('d3')->generate(array(
                    "controller" => "administrator",
                    "action" => "searchOzsn"
                )) . "?msg=err");
            } catch (\PDOException $e) {
                $handler = new \model\ExceptionHandlerModel($e);
                $_SESSION["exception"] = serialize($handler);
                preusmjeri(\route\Route::get('d3')->generate(array(
                    "controller" => "administrator",
                    "action" => "searchOzsn"
                 )) . "?msg=excep");
            }
        }
    }
    
    /**
     * removes row from table obavljaFunkciju which belongs to the current Elektrijada
     */
    public function removeOzsnFromCurrentElektrijada() {
        $this->checkRole();
        $osoba = new \model\DBOsoba();
        
        if(get('id') === false) {
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "administrator",
                "action" => "searchOzsn"
            )) . "?msg=err");
        } else {
            try {
                $osoba->load(get('id'));
                $obavlja = new \model\DBObavljaFunkciju();
                $elektrijada = new \model\DBElektrijada();
                $i = $elektrijada->getCurrentElektrijadaId();
                if ($i === false)
                    preusmjeri (\route\Route::get('d3')->generate(array(
                            "controller" => "administrator",
                            "action" => "searchOzsn"
                        )) . "?msg=pog");
                
                $obavlja->deleteRows(get('id'), $i);
                
                if(get('a') == 1)
				{
					preusmjeri(\route\Route::get('d3')->generate(array(
										"controller" => "administrator",
										"action" => "displayPersons"
									)) . "?a=1&msg=remSucc");
				}
				
				else if(get('a') == 2)
				{
					preusmjeri(\route\Route::get('d3')->generate(array(
										"controller" => "administrator",
										"action" => "listOldOzsn"
									)) . "?msg=remSucc");
				}
				
				else
				{
					preusmjeri(\route\Route::get('d3')->generate(array(
										"controller" => "administrator",
										"action" => "searchOzsn"
									)) . "?msg=remSucc");
				}
            } catch (\app\model\NotFoundException $e) {
				if(get('a') == 1)
				{
					preusmjeri(\route\Route::get('d3')->generate(array(
						"controller" => "administrator",
						"action" => "displayPersons"
					)) . "?a=1&msg=err");
				}
				else if(get('a') == 1)
				{
					preusmjeri(\route\Route::get('d3')->generate(array(
						"controller" => "administrator",
						"action" => "listOldOzsn"
					)) . "&msg=err");
				}
				else
				{
					preusmjeri(\route\Route::get('d3')->generate(array(
						"controller" => "administrator",
						"action" => "searchOzsn"
					)) . "?msg=err");
				}
            } catch (\PDOException $e) {
                if(get('a') == 1)
				{
					preusmjeri(\route\Route::get('d3')->generate(array(
						"controller" => "administrator",
						"action" => "displayPersons"
					)) . "?a=1&msg=derr");
				}
				else if(get('a') == 1)
				{
					preusmjeri(\route\Route::get('d3')->generate(array(
						"controller" => "administrator",
						"action" => "listOldOzsn"
					)) . "?&msg=derr");
				}
				else
				{
					preusmjeri(\route\Route::get('d3')->generate(array(
						"controller" => "administrator",
						"action" => "searchOzsn"
					)) . "?msg=derr");
				}
            }
        }
    }
    
    /**
     * Changes the role status of the person which id was sent via get request to 'O' (if he isn't A or O)
     */
    public function promoteToOzsn() {
        $this->checkRole();
        $this->checkMessages();
        
        if (get('id') !== false) {
            try {
                $elektrijada = new \model\DBElektrijada();
                $i = $elektrijada->getCurrentElektrijadaId();
                if ($i === false)
                    preusmjeri(\route\Route::get('d3')->generate(array(
                    "controller" => "administrator",
                    "action" => "searchPersons"
                )) . "?msg=noel");
                
                $osoba = new \model\DBOsoba();
                $osoba->promoteToOzsn(get('id'));
                
                $obavlja = new \model\DBObavljaFunkciju();
                $pov = $obavlja->ozsnExists(get('id'), $i);
                if ($pov !== true && $osoba->uloga !== 'A') {
                    $obavlja->addNewRow(get('id'), NULL, $i);
                    preusmjeri(\route\Route::get('d3')->generate(array(
                        "controller" => "administrator",
                        "action" => "searchPersons"
                    )) . "?msg=succProm");
                } else {
                    preusmjeri(\route\Route::get('d3')->generate(array(
                        "controller" => "administrator",
                        "action" => "searchPersons"
                    )) . "?msg=alrO");
                }
            } catch (\app\model\NotFoundException $e) {
                preusmjeri(\route\Route::get('d3')->generate(array(
                    "controller" => "administrator",
                    "action" => "searchPersons"
                )) . "?msg=err");
            } catch (\PDOException $e) {
                preusmjeri(\route\Route::get('d3')->generate(array(
                    "controller" => "administrator",
                    "action" => "searchPersons"
                )) . "?msg=pog");
            }
        } else {
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "administrator",
                "action" => "searchPersons"
            )) . "?msg=err");
        }
    }
    
    /**
     * Shows simple form for searching persons in DBOsoba
     */
    public function searchPersons() {
        $this->checkRole();
        $this->checkMessages();
        
        echo new \view\Main(array(
            "body" => new \view\administrator\PersonSearch(array(
                "errorMessage" => $this->errorMessage,
                "resultMessage" => $this->resultMessage
            )),
            "title" => "Pretraga"
        ));
    }
    
    /**
     * Processes the search query from action above
     */
    public function displayPersons() {
        $this->checkRole();
        $this->checkMessages();
        $osobe = array();
        $osoba = new \model\DBOsoba();
		$clanovi = array();
		
		try {
			$pom = $osoba->getAllActiveOzsn();
			if ($pom && count($pom)) {
				foreach ($pom as $v) {
					$clanovi[] = $v->getPrimaryKey();
				}
			}
		} catch (\PDOException $e) {
			$this->createMessage("Dogodila se greška! Pokušajte kasnije!");
		}
        
		if (get("type")) {
			// generate file
			$pomPolje = array("Ime", "Prezime", "Email", "Mobitel", "JMBAG", "Spol", "Datum rođenja", "Osobna iskaznica", "Vrijedi do",
				"Putovnica", "Vrijedi do", "Aktivan dokument", "MBO", "OIB");
			$array = array();
			$array[] = $pomPolje;
			if (session('search') === 'all') {
				$osobe = $osoba->getAllPersons();
			} else {
				$polje = unserialize(session('search'));
				$osobe = $osoba->find($polje[0], $polje[1], $polje[2], $polje[3], $polje[4]);
			}
			if($osobe !== false && count($osobe)) {
				foreach ($osobe as $v) {
					$array[] = array($v->ime, $v->prezime, $v->mail, $v->brojMob, $v->JMBAG, ($v->spol == "M" ? "Muško" : "Žensko"),
						$v->datRod, $v->brOsobne, $v->osobnaVrijediDo, $v->brPutovnice, $v->putovnicaVrijediDo, 
								($v->aktivanDokument == "0" ? "Putovnica" : "Osobna"), $v->MBG, $v->OIB);
				}
			}
			$path = $this->generateFile(get("type"), $array);

			echo new \view\ShowFile(array(
			"path" => $path,
			"type" => get("type")
			));
		}
	
        if(!postEmpty()) {
            // search db
            // first validate input
            $validacija = new \model\MediumPersonSearchFormModel(array(
					'ferId' => post('ferId'),
					'ime' => post('ime'), 
					'prezime' => post('prezime'),
					'OIB' => post('OIB'),
					'JMBAG' => post('JMBAG')
				));
            
            $pov = $validacija->validate();
            if($pov !== true) {
                $this->errorMessage = $validacija->decypherErrors($pov);
            } else {
                // ok the data is correct now lets find what they're looking for
                $osobe = $osoba->find(post('ime'), post('prezime'), post('ferId'), post('OIB'), post('JMBAG'));
		
				$_SESSION['search'] = serialize(array(post('ime'), post('prezime'), post('ferId'), post('OIB'), post('JMBAG')));
		
                if($osobe === false)
                    $this->errorMessage = "Nije pronađena niti jedna osoba!";
            }
        } else if (get("a") !== false) {
            // get all persons
            $osobe = $osoba->getAllPersons();
			$_SESSION['search'] = 'all';
            if($osobe === false)
                $this->errorMessage = "Ne postoji niti jedna osoba!";
        } else {
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "administrator",
                "action" => "searchPersons"
            )) . '?msg=param');
        }
        
        echo new \view\Main(array(
            "body" => new \view\administrator\PersonList(array(
                "osobe" => $osobe,
                "errorMessage" => $this->errorMessage,
                "resultMessage" => $this->resultMessage,
				"clanovi" => $clanovi
            )),
            "title" => "Popis osoba",
            "script" => new \view\scripts\administrator\PersonListJs
        ));
    }
    
    /**
     * Shows last ozsn members from last year
     */
    public function listOldOzsn() {
        $this->checkRole();
        $this->checkMessages();
        $osoba = new \model\DBOsoba();
        $elektrijada = new \model\DBElektrijada();
        $clanovi = null;
		$aktivniClanovi = array();
        
		try {
			$pom = $osoba->getAllActiveOzsn();
			if ($pom && count($pom)) {
				foreach ($pom as $v) {
					$aktivniClanovi[] = $v->getPrimaryKey();
				}
			}
		} catch (\PDOException $e) {
			$this->createMessage("Dogodila se greška! Pokušajte kasnije!");
		}
		
        if(get("id") !== false) {
            // i add only one member
            try {
                $osoba->load(get('id'));
                // check if he is an OZSN
                if($osoba->isOzsnMember() === false) {
                    preusmjeri(\route\Route::get('d1')->generate() . "?msg=notOzsn");
                }
                // now add a new row in the DBObavljaFunkciju
                $obavljaFunkciju = new \model\DBObavljaFunkciju();
                $i = $elektrijada->getCurrentElektrijadaId();
                
                if($obavljaFunkciju->ozsnExists(get('id'), $i) === true) {
                    $this->errorMessage = "Član je već zadužen za aktualnu Elektrijadu!";
                } else {
                    if($i === false) {
                        preusmjeri(\route\Route::get('d1')->generate() . "?msg=err");
                    }
                    $obavljaFunkciju->addNewRow($osoba->getPrimaryKey(), NULL, $i);

                    // everything's ok
                    //preusmjeri(\route\Route::get('d1')->generate() . "?msg=ozsnAddedSucc");
					preusmjeri(\route\Route::get('d3')->generate(array(
						"controller" => "administrator",
						"action" => "listOldOzsn"
					)) . "?msg=ozsnAddedSucc");
                }
            } catch (\app\model\NotFoundException $e) {
                preusmjeri(\route\Route::get('d1')->generate() . "?msg=dunno");
            } catch (\PDOException $e) {
                preusmjeri(\route\Route::get('d1')->generate() . "?msg=err");
            }
        } else if (get("a") !== false) {
            // i add everyone from last year
            $clanovi = $osoba->getOldOzsn();
            $i = $elektrijada->getCurrentElektrijadaId();
            if($i === false) {
                preusmjeri(\route\Route::get('d1')->generate() . "?msg=err");
            }
            if(count($clanovi)) {
                foreach ($clanovi as $c) {
                    $obavljaFunkciju = new \model\DBObavljaFunkciju();
                    if($obavljaFunkciju->ozsnExists($c->getPrimaryKey(), $i) !== true) {
                        $obavljaFunkciju->addNewRow($c->getPrimaryKey(), NULL, $i);
                    }
                }
                preusmjeri(\route\Route::get('d1')->generate() . "?msg=ozsnl");
            } else {
                preusmjeri(\route\Route::get('d1')->generate() . "?msg=err");
            }
        } else {
            // just display last year's Ozsn
            try {
                $clanovi = $osoba->getOldOzsn();
                if (!count($clanovi))
                    $this->errorMessage = "Ne postoje zapisi o prošlogodišnjim članovima odbora!";                
            } catch(\PDOException $e) {
                $handler = new \model\ExceptionHandlerModel($e);
                $_SESSION["exception"] = serialize($handler);
                preusmjeri(\route\Route::get('d1')->generate() . "?msg=excep");
            }
            
        }
		
		if (get("type") !== false) {
			$pomPolje = array("Ime", "Prezime", "Email", "Mobitel", "JMBAG", "Spol", "Datum rođenja", "Osobna iskaznica", "Vrijedi do",
				"Putovnica", "Vrijedi do", "Aktivan dokument", "MBO", "OIB");
			$array = array();
			$array[] = $pomPolje;

			if ($clanovi !== null && count($clanovi)) {
				foreach ($clanovi as $v) {
					$array[] = array($v->ime, $v->prezime, $v->mail, $v->brojMob, $v->JMBAG, ($v->spol == "M" ? "Muško" : "Žensko"),
						$v->datRod, $v->brOsobne, $v->osobnaVrijediDo, $v->brPutovnice, $v->putovnicaVrijediDo, 
								($v->aktivanDokument == "0" ? "Putovnica" : "Osobna"), $v->MBG, $v->OIB);
				}
			}

			$path = $this->generateFile(get("type"), $array);

			echo new \view\ShowFile(array(
				"path" => $path,
				"type" => get("type")
			));
		}
        
        echo new \view\Main(array(
            "body" => new \view\administrator\OldOzsn(array(
                "errorMessage" => $this->errorMessage,
                "resultMessage" => $this->resultMessage,
                "clanovi" => $clanovi,
				"aktivniClanovi" => $aktivniClanovi
            )),
            "title" => "Prošlogodišnji Članovi"
        ));
    }
    public function deletePerson() {
        $this->checkRole();
        $osoba = new \model\DBOsoba();
        
        if(get('id') === false) {
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "administrator",
                "action" => "searchPersons"
            )) . "?msg=err");
        } else {
            try {
                $osoba->load(get('id'));
                
                if($osoba->deleteOsoba(get('id')) === false) {
                    preusmjeri(\route\Route::get('d3')->generate(array(
                        "controller" => "administrator",
                        "action" => "searchPersons"
                    )) . "?msg=derr");
                }
                
                preusmjeri(\route\Route::get('d3')->generate(array(
                                    "controller" => "administrator",
                                    "action" => "searchPersons"
                                )) . "?msg=delp");
            } catch (\app\model\NotFoundException $e) {
                preusmjeri(\route\Route::get('d3')->generate(array(
                    "controller" => "administrator",
                    "action" => "searchPersons"
                )) . "?msg=err");
            } catch (\PDOException $e) {
                $handler = new \model\ExceptionHandlerModel($e);
                $_SESSION["exception"] = serialize($handler);
                preusmjeri(\route\Route::get('d3')->generate(array(
                    "controller" => "administrator",
                    "action" => "searchPersons"
                 )) . "?msg=excep");
            }
        }
    }
    
    public function modifyPerson() {
        $this->checkRole();
        $this->checkMessages();
        $osoba = new \model\DBOsoba();
        
        if(!postEmpty()) {
            // here we do the magic
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
                $this->errorMessage = $validacija->decypherErrors($pov);
                try {
                    $osoba->load(post('idOsobe'));
                } catch (\app\model\NotFoundException $e) {
                    
                } catch (\PDOException $e) {
                    $handler = new \model\ExceptionHandlerModel($e);
                    $this->errorMessage = $handler;
                }
            } else {
                // first check passwords
                if (post("password") !== false) {
                    if (post("password_new") !== false && post("password_new2") !== false) {
                        $pov = $osoba->checkAdmin(post("password"));
                        if ($pov === false || $pov->getPrimaryKey() != session('auth')) {
                            $handler = new \model\ExceptionHandlerModel(new \PDOException(), "Pogrešna stara lozinka");
                            $_SESSION["exception"] = serialize($handler);
                            preusmjeri(\route\Route::get('d3')->generate(array(
                                "controller" => "administrator",
                                "action" => "modifyPerson"
                             )) . "?msg=excep&id=" . post("idOsobe"));
                        }
                        if (post("password_new") !== post("password_new2")) {
                            $handler = new \model\ExceptionHandlerModel(new \PDOException(), "Nove lozinke se ne podudaraju");
                            $_SESSION["exception"] = serialize($handler);
                            preusmjeri(\route\Route::get('d3')->generate(array(
                                "controller" => "administrator",
                                "action" => "modifyPerson"
                             )) . "?msg=excep&id=" . post("idOsobe"));
                        }
                    } else {
                        $handler = new \model\ExceptionHandlerModel(new \PDOException(), "Ukoliko mijenjate lozinku, morate unijeti staru, kao i novu lozinku!");
                        $_SESSION["exception"] = serialize($handler);
                        preusmjeri(\route\Route::get('d3')->generate(array(
                            "controller" => "administrator",
                            "action" => "modifyPerson"
                         )) . "?msg=excep&id=" . post("idOsobe"));
                    }
                } else {
                    if (post("password_new") !== false || post("password_new2") !== false) {
                        $handler = new \model\ExceptionHandlerModel(new \PDOException(), "Morate unijeti staru lozinku");
                        $_SESSION["exception"] = serialize($handler);
                        preusmjeri(\route\Route::get('d3')->generate(array(
                            "controller" => "administrator",
                            "action" => "modifyPerson"
                         )) . "?msg=excep&id=" . post("idOsobe"));
                    }
                }
                
                // everything's ok ; insert new row
                try {
                    $osoba->modifyPerson(post($osoba->getPrimaryKeyColumn()), post('ime', null), post('prezime', null), post('mail', null), post('brojMob', null), post('ferId'), post('password_new', null), 
                        post('JMBAG', null), post('spol', null), post('datRod', null), post('brOsobne', null), post('brPutovnice', null), post('osobnaVrijediDo', null),
                        post('putovnicaVrijediDo', null), NULL, post('MBG', null), post('OIB', null));
                    // redirect with according message
                    preusmjeri(\route\Route::get('d3')->generate(array(
                        "controller" => "administrator",
                        "action" => "searchPersons"
                    )) . "?msg=succp");
                } catch(\PDOException $e) {
                    $handler = new \model\ExceptionHandlerModel($e);
                    $_SESSION["exception"] = serialize($handler);
                    preusmjeri(\route\Route::get('d3')->generate(array(
                        "controller" => "administrator",
                        "action" => "modifyPerson"
                     )) . "?msg=excep&id=" . post("idOsobe"));
                }
            }
        } else {
            if(get("id") !== false) {
                // let's check if i got an existing table key and let's do magic
                try {
                    $osoba->load(get("id"));
                } catch (\app\model\NotFoundException $e) {
                    preusmjeri(\route\Route::get('d1')->generate() . "?msg=e");
                }
            } else {
                preusmjeri(\route\Route::get('d1')->generate() . "?msg=e");
            }
        }
        
        echo new \view\Main(array(
            "body" => new \view\administrator\PersonModification(array(
                "errorMessage" => $this->errorMessage,
                "osoba" => $osoba
            )),
            "title" => "Ažuriranje osoba",
            "script" => new \view\scripts\PersonFormJs(array(
				"modification" => true
			))
        ));  
        
    }
    
    /****************** ELEKTRIJADA stuff **************************/
    
    /**
     * adds a new row in elektrijada table
     */
    public function addElektrijada() {
        $this->checkRole();
        
        if(!postEmpty()) {
            $validacija = new \model\ElektrijadaFormModel(array(
                                                'mjestoOdrzavanja' => post('mjestoOdrzavanja'),
                                            'datumPocetka' => post('datumPocetka'),
                                            'datumKraja' => post('datumKraja'), 
                                            'ukupniRezultat' => post('ukupniRezultat'),
                                            'drzava' => post('drzava'),
                                            'rokZaZnanje' => post('rokZaZnanje'),
                                            'rokZaSport' => post('rokZaSport'),
                                            'ukupanBrojSudionika' => post('ukupanBrojSudionika')
                                            ));
            $pov = $validacija->validate();
            if($pov !== true) {
                $message = $validacija->decypherErrors($pov);
				$this->createMessage($message, "d3", "administrator", "addElektrijada");
            } else {
				if (post('datumPocetka') !== false && post('datumKraja') !== false) {
					$datp = strtotime(post('datumPocetka'));
					$datk = strtotime(post('datumKraja'));
					if ($datp >= $datk) {
						$this->createMessage("Datum početka mora biti manji od datuma kraja Elektrijade!", "d3", "administrator", "addElektrijada");
					}
				}
				
				if (post("ukupniRezultat", "0") > post("ukupanBrojSudionika", "0")) {
					$this->createMessage("Rezultat mora biti manji od broja sudionika!", "d3", "administrator", "addElektrijada");
				}
                // everything's ok i add the new Elektrijada data
                $elektrijada = new \model\DBElektrijada();
                
                try {
                    $p =$elektrijada->addNewElektrijada(post('mjestoOdrzavanja'), post('datumPocetka'), 
                            post('datumKraja'), post('ukupniRezultat', NULL), post('drzava'), post('rokZaZnanje', NULL),
                            post('rokZaSport', NULL), post('ukupanBrojSudionika', NULL));
                    if ($p === false) {
                        $this->errorMessage = "Već postoji Elektrijada za tu godinu";
                    } else {
						$bus = new \model\DBBus();
						$bus->clearBuses();
                        preusmjeri(\route\Route::get('d1')->generate() . "?msg=elekAddSucc");
                    }
                } catch (\PDOException $e) {
                    $handler = new \model\ExceptionHandlerModel($e);
                    $this->createMessage($handler, "d3", "administrator", "addElektrijada");
                }
                
            }
        }
        
        $this->checkMessages();
        echo new \view\Main(array(
            "body" => new \view\administrator\ElektrijadaAdding(array(
                "errorMessage" => $this->errorMessage
            )),
            "title" => "Dodavanje Elektrijade",
            "script" => new \view\scripts\ElektrijadaFormJs()
        ));
    }
    
    /**
     * display all Elektrijada data which exists in table
     */
    public function displayElektrijada() {
        $this->checkRole();
        $this->checkMessages();
        
        $e = new \model\DBElektrijada();
        try {
            $elektrijade = $e->getElektrijada();
        } catch (\PDOException $e) {
            $handler = new \model\ExceptionHandlerModel($e);
            $_SESSION["exception"] = serialize($handler);
            preusmjeri(\route\Route::get('d1')->generate() . "?msg=excep");
        }
        if(count($elektrijade) === 0) {
            $this->errorMessage = "Ne postoji niti jedan zapis o Elektrijadi!";
        }
		
		if (get("type") !== false) {
			$pomPolje = array("Mjesto održavanja", "Početak", "Kraj", "Rezultat", "Ukupno sudionika", "Država");
			$array = array();
			$array[] = $pomPolje;

			if ($elektrijade !== null && count($elektrijade)) {
				foreach ($elektrijade as $v) {
					$array[] = array($v->mjestoOdrzavanja, $v->datumPocetka, $v->datumKraja, $v->ukupniRezultat, $v->ukupanBrojSudionika,
						$v->drzava);
				}
			}

			$path = $this->generateFile(get("type"), $array);

			echo new \view\ShowFile(array(
				"path" => $path,
				"type" => get("type")
			));
		}
        
        echo new \view\Main(array(
            "body" => new \view\administrator\ElektrijadaList(array(
                "elektrijade" => $elektrijade,
                "errorMessage" => $this->errorMessage,
                "resultMessage" => $this->resultMessage
            )),
            "title" => "Popis Elektrijada",
            "script" => new \view\scripts\administrator\ElektrijadaListJs()
        ));
    }
    
    /**
     * Action for modifying existing Elektrijada data
     */
    public function modifyElektrijada() {
        $this->checkRole();
        $elektrijada = new \model\DBElektrijada();
        
        if(!postEmpty()) {
            // here we do the magic
            if($elektrijada->elektrijadaExists(post("idElektrijade")) !== false) {
                $elektrijada->load(post("idElektrijade"));
            }
            $validacija = new \model\ElektrijadaFormModel(array(
                                                'mjestoOdrzavanja' => post('mjestoOdrzavanja'),
                                            'datumPocetka' => post('datumPocetka'),
                                            'datumKraja' => post('datumKraja'), 
                                            'ukupniRezultat' => post('ukupniRezultat'),
                                            'drzava' => post('drzava'),
                                            'rokZaZnanje' => post('rokZaZnanje'),
                                            'rokZaSport' => post('rokZaSport'),
                                            'ukupanBrojSudionika' => post('ukupanBrojSudionika')
                                            ));
            $pov = $validacija->validate();
            if($pov !== true) {
                $message = $validacija->decypherErrors($pov);
                $handler = new \model\ExceptionHandlerModel(new \PDOException(), $message);
                $_SESSION["exception"] = serialize($handler);
                preusmjeri(\route\Route::get('d3')->generate(array(
                    "controller" => "administrator",
                    "action" => "modifyElektrijada"
                )) . "?msg=excep&id=" . post($elektrijada->getPrimaryKeyColumn()));
            } else {
                // everything's ok ; insert new row
                try {
					if (post('datumPocetka') !== false && post('datumKraja') !== false) {
						$datp = strtotime(post('datumPocetka'));
						$datk = strtotime(post('datumKraja'));
						if ($datp >= $datk) {
							$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Datum početka mora biti manji od datuma kraja Elektrijade!");
							$_SESSION["exception"] = serialize($handler);
							preusmjeri(\route\Route::get('d3')->generate(array(
								"controller" => "administrator",
								"action" => "modifyElektrijada"
							)) . "?msg=excep&id=" . post($elektrijada->getPrimaryKeyColumn()));
						}
					}
					if (post("ukupniRezultat", "0") > post("ukupanBrojSudionika", "0")) {
						$handler = new \model\ExceptionHandlerModel(new \PDOException(), "Rezultat mora biti manji od broja sudionika!");
						$_SESSION["exception"] = serialize($handler);
						preusmjeri(\route\Route::get('d3')->generate(array(
							"controller" => "administrator",
							"action" => "modifyElektrijada"
						)) . "?msg=excep&id=" . post($elektrijada->getPrimaryKeyColumn()));
					}
                    if ($elektrijada->existsElektrijadaWithYearDifferentFrom(post('datumPocetka'), post($elektrijada->getPrimaryKeyColumn()))) {
                        $this->errorMessage = "Već postoji Elektrijada za tu godinu";
                    } else {
                        $elektrijada->modifyRow(post($elektrijada->getPrimaryKeyColumn()), post('mjestoOdrzavanja'), post('datumPocetka'), 
                                post('datumKraja'), post('ukupniRezultat', NULL), post('drzava'), post('rokZaZnanje', NULL),
                                post('rokZaSport', NULL), post('ukupanBrojSudionika', NULL));
                        // redirect with according message
                        preusmjeri(\route\Route::get('d3')->generate(array(
                            "controller" => "administrator",
                            "action" => "displayElektrijada"
                        )) . "?msg=succ");
                    }
                } catch(\PDOException $e) {
                    $handler = new \model\ExceptionHandlerModel($e);
                    $_SESSION["exception"] = serialize($handler);
                    preusmjeri(\route\Route::get('d3')->generate(array(
                        "controller" => "administrator",
                        "action" => "modifyElektrijada"
                     )) . "?msg=excep&id=" . post($elektrijada->getPrimaryKeyColumn()));
                }
            }
        } else {
            if(get("id") !== false) {
                // let's check if i got an existing table key and let's do magic
                if($elektrijada->elektrijadaExists(get("id")) === false) {
                    preusmjeri(\route\Route::get('d1')->generate() . "?msg=e");
                } else {
                    $elektrijada->load(get("id"));
                }
            } else {
                preusmjeri(\route\Route::get('d1')->generate() . "?msg=e");
            }
        }
        
        $this->checkMessages();
        echo new \view\Main(array(
            "body" => new \view\administrator\ElektrijadaModification(array(
                "elektrijada" => $elektrijada,
                "errorMessage" => $this->errorMessage
            )),
            "title" => "Ažuriranje Elektrijade",
            "script" => new \view\scripts\ElektrijadaFormJs()
        ));
    }
    
    /**
     * deletes an existing row in table
     */
    public function deleteElektrijada() {
        $this->checkRole();
        $elektrijada = new \model\DBElektrijada();
        
        if(get('id') === false) {
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "administrator",
                "action" => "displayElektrijada"
            )) . "?msg=err");
        } else {
            if($elektrijada->elektrijadaExists(get("id")) === false) {
                preusmjeri(\route\Route::get('d3')->generate(array(
                    "controller" => "administrator",
                    "action" => "displayElektrijada"
                )) . "?msg=err");
            } else {
                if($elektrijada->deleteElektrijada(get('id')) === false) {
                    preusmjeri(\route\Route::get('d3')->generate(array(
                        "controller" => "administrator",
                        "action" => "displayElektrijada"
                    )) . "?msg=derr");
                }
            }
        }
        
        preusmjeri(\route\Route::get('d3')->generate(array(
            "controller" => "administrator",
            "action" => "displayElektrijada"
        )) . "?msg=del");
    }
    
    public function doubleCheckAdmin() {
        $this->checkRole();
        $this->checkMessages();
        $osoba = new \model\DBOsoba();

        if (!postEmpty()) {
            if (post("pass") === false)
                preusmjeri(\route\Route::get('d3')->generate(array(
                            "controller" => "administrator",
                            "action" => "doubleCheckAdmin"
                        )) . "?id=" . post('id') . "&msg=passn");
            else {
                $pov = $osoba->checkAdmin(post('pass'));
                if ($pov === false)
                    preusmjeri(\route\Route::get('d3')->generate(array(
                            "controller" => "administrator",
                            "action" => "doubleCheckAdmin"
                        )) . "?id=" . post('id') . "&msg=passw");
                else {
                    if($_SESSION['auth'] !== $pov->getPrimaryKey())
                        preusmjeri(\route\Route::get('d1')->generate() . "&msg=accessDenied");
                    else {
                        preusmjeri(\route\Route::get('d3')->generate(array(
                            "controller" => "administrator",
                            "action" => "deleteElektrijada"
                        )) . "?id=" . post('id'));
                    }
                }
            }
        }
        
        echo new \view\Main(array(
            "body" => new \view\administrator\AdminDoubleCheck(array(
                "errorMessage" => $this->errorMessage,
                "id" => get('id')
            )),
            "title" => "Provjera identiteta",
			"script" => new \view\scripts\LoginFormJs()
        ));
    }
}
