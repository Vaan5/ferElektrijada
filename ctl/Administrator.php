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
            case 'del':
                $this->resultMessage = "Uspješno izbrisana Elektrijada!";
                break;
            case 'err':
                $this->errorMessage = "Zahtjevani zapis ne postoji!";
                break;
            case 'derr':
                $this->errorMessage = "Dogodila se pogreška prilikom brisanja! Pokušajte ponovno!";
                break;
            default:
                break;
        }
    }

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
                                        'zivotopis' => post('zivotopis'),
                                        'mbrOsigOsobe' => post('mbrOsigOsobe'),
                                        'OIB' => post('OIB')));
            $pov = $validacija->validate();
            if($pov !== true) {
                $this->errorMessage = $validacija->decypherErrors($pov);
            } else {
                // everything is ok i add the person
                $osoba = new \model\DBOsoba();
                try {
                    $osoba->addNewPerson(post('ime', null), post('prezime', null), post('mail', null), post('brojMob', null), post('ferId'), post('password'), 
                        post('JMBAG', null), post('spol', null), post('datRod', null), post('brOsobne', null), post('brPutovnice', null), post('osobnaVrijediDo', null),
                        post('putovnicaVrijediDo', null), 'O', post('zivotopis', null), post('mbrOsigOsobe', null), post('OIB', null));
                        // added successfully
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
        
        $elektrijade = \model\DBElektrijada::getElektrijada();
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
                    $this->errorMessage = "Ne postoji zapis s predanim identifikatorom!";
                } else {
                    $elektrijada->load(get("id"));
                }
            } else {
                $this->errorMessage = "Ne postoji zapis s predanim identifikatorom!";
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
