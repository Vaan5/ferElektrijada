<?php

namespace model;
use app\model\AbstractFormModel;

class ElektrijadaFormModel extends AbstractFormModel {
    
    protected function rules() {
        if($this->rulesArray === null) {
            $this->rulesArray = array('mjestoOdrzavanja' => array('required', 'words'),
                'datumPocetka' => array('required', 'date'),
                'datumKraja' => array('required', 'date'), 
                'ukupniRezultat' => array('numbers'),
                'drzava' => array('required', 'words'),
                'rokZaZnanje' => array('date'),
                'rokZaSport' => array('date'),
                'ukupanBrojSudionika' => array('numbers'));
        }
        return $this->rulesArray;
    }
    
    public function decypherErrors($pov) {
        if(count($pov)) {
            foreach ($pov as $k => $v) {
                switch ($k) {
                    case 'mjestoOdrzavanja':
                        return $v === 'required' ? 'Morate unijeti mjesto održavanja' : 'Pogrešno mjesto održavanja';
                    case 'datumPocetka':
                        return $v === 'required' ? 'Morate unijeti datum početka Elektrijade' : 'Pogrešan datum početka Elektrijade';
                    case 'datumKraja':
                        return $v === 'required' ? 'Morate unijeti datum završetka Elektrijade' : 'Pogrešan datum završetka Elektrijade';
                    case 'ukupniRezultat':
                        return 'Neispravan rezultat! Mora biti broj!';
                    case 'drzava':
                        return $v === 'required' ? 'Morate unijeti naziv države' : 'Pogrešan naziv države';
                    case 'rokZaZnanje':
                    case 'rokZaSport':
                        return 'Neispravan format datuma!';
                    case 'ukupanBrojSudionika':
                        return 'Morate unijeti brojcanu vrijednost za broj sudionika!';
                    default:
                        break;
                }
            }
        }
        return null;
    }
}
