<?php

namespace model;
use app\model\AbstractFormModel;

class PersonFormModel extends AbstractFormModel {
    
    protected function rules() {
        return array('password' => array('required', 'password'),
            'ferId' => array('required', 'username'),
            'ime' => array('name'), 
            'prezime' => array('name'), 
            'mail' => array('mail'), 
            'brojMob' => array('numbers'), 
            'JMBAG' => array('jmbag'),
            'spol' => array('gender'), 
            'datRod' => array('date'), 
            'brOsobne' => array('safe'), 
            'brPutovnice' => array('safe'), 
            'osobnaVrijediDo' => array('date'), 
            'putovnicaVrijediDo' => array('date'),
            'MBG' => array('numbers'),
            'OIB' => array('oib'));
    }
    
    public function decypherErrors($pov) {
        if(count($pov)) {
            foreach ($pov as $k => $v) {
                switch ($k) {
                    case 'ferId':
                        return $v === 'required' ? 'Korisničko ime je obavezno' : 'Pogrešno korisničko ime';
                    case 'password':
                        return $v === 'required' ? 'Lozinka je obavezna' : 'Pogrešna lozinka';
                    case 'ime':
                        return 'Neispravno ime';
                    case 'prezime':
                        return 'Neispravno prezime';
                    case 'mail':
                        return 'Neispravan e-mail';
                    case 'brojMob':
                        return 'Neispravan broj mobitela';
                    case 'JMBAG':
                        return 'Neispravan JMBAG';
                    case 'spol':
                        return 'Neispravan spol';
                    case 'datRod':
                        return 'Neispravan datum rođenja';
                    case 'osobnaVrijediDo':
                        return 'Neispravan datum isteka osobne iskaznice';
                    case 'putovnicaVrijediDo':
                        return 'Neispravan datum isteka putovnice';
                    case 'MBG':
                        return 'Neispravan maticni broj osobe';
                    case 'OIB':
                        return 'Neispravan OIB';
                    default:
                        break;
                }
            }
        }
        return null;
    }
}
