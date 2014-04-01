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
    
    private function checkMessages() {
        switch(get("msg")) {
            case 'succ':
                $this->resultMessage = "Uspješno izmijenjena postojeća Elektrijada!";
                break;
            case 'succo':
                $this->resultMessage = "Uspješno ažurirani podaci o članu odbora!";
                break;
            case 'del':
                $this->resultMessage = "Uspješno izbrisana Elektrijada!";
                break;
            case 'err':
                $this->errorMessage = "Zahtjevani zapis ne postoji!";
                break;
            case 'delo':
                $this->errorMessage = "Uspješno izbrisan član odbora!";
                break;
            case 'derr':
                $this->errorMessage = "Dogodila se pogreška prilikom brisanja! Pokušajte ponovno!";
                break;
            case 'noel':
                $this->errorMessage = "Članove možete dodavati samo za trenutno aktivnu elektrijadu! Najprije stvorite novu elektrijadu!";
                break;
            default:
                break;
        }
    }
    
    /**
     * Action being called when the admin wants to change his personal data
     */
    public function changeProfile() {
        $this->checkRole();
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
                                        'OIB' => post('OIB')));
            $pravila = $validacija->getRules();
            $pravila['password'] = array('password');
            $validacija->setRules($pravila);
            $pov = $validacija->validate();
            if($pov !== true) {
                $this->errorMessage = $validacija->decypherErrors($pov);
            } else {
                // everything's ok ; insert new row
                try {
                    $osoba->modifyRow(session("auth"), post('ime', null), post('prezime', null), post('mail', null), post('brojMob', null), post('ferId'), post('password', null), 
                        post('JMBAG', null), post('spol', null), post('datRod', null), post('brOsobne', null), post('brPutovnice', null), post('osobnaVrijediDo', null),
                        post('putovnicaVrijediDo', null), 'O', NULL, post('MBG', null), post('OIB', null));
                    // redirect with according message
                    preusmjeri(\route\Route::get('d1')->generate() . "?msg=profSucc");
                } catch(PDOException $e) {
                    $this->errorMessage = "Greška prilikom unosa podataka! Već postoji član s takvim podacima!";
                }
            }
        }
        // let's check if i got an existing table key and let's do magic
        try {
            $osoba->load(session("auth"));
        } catch (\app\model\NotFoundException $e) {
            preusmjeri(\route\Route::get('d1')->generate() . "?msg=e");
        }
        
        echo new \view\Main(array(
            "body" => new \view\administrator\AdminProfile(array(
                "errorMessage" => $this->errorMessage,
                "resultMessage" => $this->resultMessage,
                "admin" => $osoba
            )),
            "title" => "Uređivanje profila"
        ));
    }

    /**
     * adds new Ozsn member
     */
    public function addOzsn() {
        $this->checkRole();
        
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
                    if($idElektrijade = $elektrijada->getCurrentElektrijadaId() === false) {
                        // we can only add ozsn members for the current Elektrijada
                        preusmjeri(\route\Route::get('d3')->generate(array(
                            "controller" => "administrator",
                            "action" => "displayElektrijada"
                        )) . "?msg=noel");
                    }
                    $osoba->addNewPerson(post('ime', null), post('prezime', null), post('mail', null), post('brojMob', null), post('ferId'), post('password'), 
                        post('JMBAG', null), post('spol', null), post('datRod', null), post('brOsobne', null), post('brPutovnice', null), post('osobnaVrijediDo', null),
                        post('putovnicaVrijediDo', null), 'O', NULL, post('MBG', null), post('OIB', null));
                        // added successfully
                    // now assign them to the current Elektrijada
                    $obavlja = new \model\DBObavljaFunkciju();
                    $obavlja->addNewRow($osoba->getPrimaryKey(), NULL, $idElektrijade);
                    preusmjeri(\route\Route::get('d1')->generate() . "?msg=ozsnAddedSucc");
                } catch (\PDOException $e) {
                    $this->errorMessage = "Pogreška prilikom dodavanja u bazu! Provjerite da li korisnik s takvim podacima već ne postoji!";
                }
            }
        }
        
        echo new \view\Main(array(
            "body" => new \view\administrator\OzsnAdding(array(
                    "errorMessage" => $this->errorMessage
                )),
            "title" => "Dodavanje članova odbora"
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
                $osobe = $osoba->findOzsnMembers();
                if($osobe === false)
                    $this->errorMessage = "Nije pronađen niti jedan član!";
            }
        } else if (get("a") !== false) {
            // get all ozsn members
            $osobe = $osoba->getAllOzsn();
            if($osobe === false)
                $this->errorMessage = "Ne postoji niti jedan član, koji zadovoljava zahtjeve pretrage!";
        } else {
            preusmjeri(\route\Route::get('d3')->generate(array(
                "controller" => "administrator",
                "action" => "searchOzsn"
            )));
        }
        
        echo new \view\Main(array(
            "body" => new \view\administrator\OzsnList(array(
                "osobe" => $osobe,
                "errorMessage" => $this->errorMessage,
                "resultMessage" => $this->resultMessage
            )),
            "title" => "Popis članova Odbora"
        ));
    }
    
    /**
     * changes ozsn member data
     */
    public function modifyOzsn() {
        $this->checkRole();
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
                                        'OIB' => post('OIB')));
            $pov = $validacija->validate();
            if($pov !== true) {
                $this->errorMessage = $validacija->decypherErrors($pov);
            } else {
                // everything's ok ; insert new row
                try {
                    $osoba->modifyRow(post($osoba->getPrimaryKeyColumn()), post('ime', null), post('prezime', null), post('mail', null), post('brojMob', null), post('ferId'), post('password'), 
                        post('JMBAG', null), post('spol', null), post('datRod', null), post('brOsobne', null), post('brPutovnice', null), post('osobnaVrijediDo', null),
                        post('putovnicaVrijediDo', null), 'O', NULL, post('MBG', null), post('OIB', null));
                    // redirect with according message
                    preusmjeri(\route\Route::get('d3')->generate(array(
                        "controller" => "administrator",
                        "action" => "searchOzsn"
                    )) . "?msg=succo");
                } catch(PDOException $e) {
                    $this->errorMessage = "Greška prilikom unosa podataka! Već postoji član s takvim podacima!";
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
            "title" => "Ažuriranje članova Odbora"
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
            }
        }
    }
    
    /**
     * Shows last ozsn members from last year
     */
    public function listOldOzsn() {
        $this->checkRole();
        $osoba = new \model\DBOsoba();
        $elektrijada = new \model\DBElektrijada();
        $clanovi = null;
        
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
                if($i === false) {
                    preusmjeri(\route\Route::get('d1')->generate() . "?msg=err");
                }
                $obavljaFunkciju->addNewRow($osoba->getPrimaryKey(), NULL, $i);
                
                // everything's ok
                preusmjeri(\route\Route::get('d1')->generate() . "?msg=ozsnAddedSucc");
                
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
                    $obavljaFunkciju->addNewRow($c->getPrimaryKey(), NULL, $i);
                }
            } else {
                preusmjeri(\route\Route::get('d1')->generate() . "?msg=err");
            }
        } else {
            // just display last year's Ozsn
            try {
                $clanovi = $osoba->getOldOzsn();
                if (!count($clanovi))
                    $this->errorMessage = "Ne postoje zapisi o prošlogodišnjim članovima odbora!";                
            } catch(PDOException $e) {
                $this->errorMessage = "Pogreška prilikom dohvata prošlogodišnjih članova odbora!";
            }
            
        }
        
        echo new \view\Main(array(
            "body" => new \view\administrator\OldOzsn(array(
                "errorMessage" => $this->errorMessage,
                "clanovi" => $clanovi
            )),
            "title" => "Prošlogodišnji članovi odbora"
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
                                            'drzava' => post('drzava')
                                            ));
            $pov = $validacija->validate();
            if($pov !== true) {
                $this->errorMessage = $validacija->decypherErrors($pov);
            } else {
                // everything's ok i add the new Elektrijada data
                $elektrijada = new \model\DBElektrijada();
                
                try {
                    $elektrijada->addNewElektrijada(post('mjestoOdrzavanja'), post('datumPocetka'), 
                            post('datumKraja'), post('ukupniRezultat', NULL), post('drzava'));
                    preusmjeri(\route\Route::get('d1')->generate() . "?msg=elekAddSucc");
                } catch (PDOException $e) {
                    $this->errorMessage = "Pogreška prilikom dodavanja u bazu! Provjerite da li elektrijada s takvim podacima već ne postoji!";
                }
                
            }
        }
        
        echo new \view\Main(array(
            "body" => new \view\administrator\ElektrijadaAdding(array(
                "errorMessage" => $this->errorMessage
            )),
            "title" => "Dodavanje Elektrijade"
        ));
    }
    
    /**
     * display all Elektrijada data which exists in table
     */
    public function displayElektrijada() {
        $this->checkRole();
        $this->checkMessages();
        
        $e = new \model\DBElektrijada();
        $elektrijade = $e->getElektrijada();
        if(count($elektrijade) === 0) {
            $this->errorMessage = "Ne postoji niti jedan zapis o Elektrijadi!";
        }
        
        echo new \view\Main(array(
            "body" => new \view\administrator\ElektrijadaList(array(
                "elektrijade" => $elektrijade,
                "errorMessage" => $this->errorMessage,
                "resultMessage" => $this->resultMessage
            )),
            "title" => "Popis Elektrijada"
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
            $validacija = new \model\ElektrijadaFormModel(array(
                                                'mjestoOdrzavanja' => post('mjestoOdrzavanja'),
                                            'datumPocetka' => post('datumPocetka'),
                                            'datumKraja' => post('datumKraja'), 
                                            'ukupniRezultat' => post('ukupniRezultat'),
                                            'drzava' => post('drzava')
                                            ));
            $pov = $validacija->validate();
            if($pov !== true) {
                $this->errorMessage = $validacija->decypherErrors($pov);
            } else {
                // everything's ok ; insert new row
                try {
                    $elektrijada->modifyRow(post($elektrijada->getPrimaryKeyColumn()), post('mjestoOdrzavanja'), post('datumPocetka'), 
                            post('datumKraja'), post('ukupniRezultat', NULL), post('drzava'));
                    // redirect with according message
                    preusmjeri(\route\Route::get('d3')->generate(array(
                        "controller" => "administrator",
                        "action" => "displayElektrijada"
                    )) . "?msg=succ");
                } catch(PDOException $e) {
                    $this->errorMessage = "Greška prilikom unosa podataka! Već postoji elektrijada s takvim podacima!";
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
        
        echo new \view\Main(array(
            "body" => new \view\administrator\ElektrijadaModification(array(
                "elektrijada" => $elektrijada,
                "errorMessage" => $this->errorMessage
            )),
            "title" => "Ažuriranje Elektrijade"
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
}
