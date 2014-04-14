<?php

namespace model\formModel;
use app\model\AbstractFormModel;

class KontaktOsobeFormModel extends AbstractFormModel {
    
    protected function rules() {
        if ($this->rulesArray === null) {
            $this->rulesArray = array('imeKontakt' => array('required', 'name'),
                'prezimeKontakt' => array('required', 'name'),
                'telefon' => array('phone'),
                'radnoMjesto' => array('alnum'),
                'idTvrtke' => array('numbers'),
                'idSponzora' => array('numbers'),
		'idMedija' => array('numbers'));
        }
        return $this->rulesArray;
    }
    
    public function decypherErrors($pov) {
        if(count($pov)) {
            foreach ($pov as $k => $v) {
                switch ($k) {
                    case 'imeKontakt':
                        return "Ime mora započinjati velikim slovom!";
                    case 'prezimeKontakt':
                        return "Prezime mora započinjati velikim slovom!";
                    case 'telefon':
                        return "Broj telefona smije sadržavati samo znamenke!";
                    case 'radnoMjesto':
                        return "Naziv radnog mjesta može sadržavati samo znamenke i slova!";
                    case 'idTvrtke':
                        return "Pogrešan identifikator tvrtke!";
                    case 'idSponzora':
                        return "Pogrešan identifikator sponzora!";
		    case 'idMedija':
                        return "Pogrešan identifikator medija!";
                    default:
                        break;
                }
            }
        }
        return null;
    }
}
