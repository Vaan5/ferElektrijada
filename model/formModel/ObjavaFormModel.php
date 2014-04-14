<?php

namespace model\formModel;
use app\model\AbstractFormModel;

class ObjavaFormModel extends AbstractFormModel {
    
    protected function rules() {
        if ($this->rulesArray === null) {
            $this->rulesArray = array('datumObjave' => array('required', 'date'),
					'autorIme' => array('required', 'name'),
					'autorPrezime' => array('required', 'name'),
					'link' => array('url'),
					'idMedija' => array("required", "numbers"));
        }
        return $this->rulesArray;
    }
    
    public function decypherErrors($pov) {
        if(count($pov)) {
            foreach ($pov as $k => $v) {
                switch ($k) {
                    case 'datumObjave':
                        return $v === 'required' ? "Datum je obavezan!" : "Neispravan datum!";
		    case 'autorIme':
                        return $v === 'required' ? "Ime je obavezno!" : "Neispravno ime!";
		    case 'autorPrezime':
                        return $v === 'required' ? "Prezime je obavezno!" : "Neispravno prezime!";
		    case 'idMedija':
                        return $v === 'required' ? "Medij je obavezan!" : "Neispravan medij!";
		    case 'link':
                        return "Neispravan url!";
                    default:
                        break;
                }
            }
        }
        return null;
    }
}
