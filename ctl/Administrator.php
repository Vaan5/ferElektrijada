<?php

namespace ctl;
use app\controller\Controller;

class Administrator implements Controller {
    
    private $errorMessage;

    public function addOzsn() {
        // if you don't have the right permissions to do this, then get out of here
        if (!\model\DBOsoba::isLoggedIn() || \model\DBOsoba::getUserRole() !== 'A') {
            preusmjeri(\route\Route::get('d1')->generate() . "?msg=accessDenied");
        }
        
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
                }catch (Exception $e) {
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
}
