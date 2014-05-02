<?php

namespace model;
use app\model\AbstractFormModel;

class MediumPersonSearchFormModel extends AbstractFormModel {
    
    protected function rules() {
        if ($this->rulesArray === null) {
            $this->rulesArray = array(
                'ferId' => array('safe'),
                'ime' => array('safe'), 
                'prezime' => array('safe'),
                'JMBAG' => array('numbers'),
                'OIB' => array('numbers')
                );
        }
        return $this->rulesArray;
    }
    
    public function decypherErrors($pov) {
        if(count($pov)) {
            foreach ($pov as $k => $v) {
                switch ($k) {
                    case 'ferId':
                        return "Neispravno korisničko ime!";
                    case 'ime':
                        return "Neispravno ime!";
                    case 'prezime':
                        return "Neispravno prezime!";
                    case 'JMBAG':
                        return "Neispravan JMBAG!";
                    case 'OIB':
                        return "Neispravan OIB!";
                    default:
                        break;
                }
            }
        }
        return null;
    }
}
