<?php

namespace model\formModel;
use app\model\AbstractFormModel;

class AreaSponzorFormModel extends AbstractFormModel {
    
    protected function rules() {
        if ($this->rulesArray === null) {
            $this->rulesArray = array('iznosDonacije' => array('required', 'decimal'),
					'napomena' => array('alnumpunct'),
					'idSponzora' => array('required', 'numbers'),
					'idPodrucja' => array('required', 'numbers'));
        }
        return $this->rulesArray;
    }
    
    public function decypherErrors($pov) {
        if(count($pov)) {
            foreach ($pov as $k => $v) {
                switch ($k) {
		    case 'iznosDonacije':
                        return "Iznos donacije je obavezan, te može biti samo decimalni broj s decimalnom točkom!";
		    case 'napomena':
                        return "Napomena može sadržavati samo znamenke i slova, razmake i osnovne interpunkcijske znakove!";
		    case 'idSponzora':
                        return $v === 'required' ? 'Izbor sponzora je obavezan!' : 'Nepoznati sponzor!';
		    case 'idPodrucja':
                        return $v === 'required' ? 'Izbor područja je obavezan!' : 'Nepoznato područje!';
                    default:
                        break;
                }
            }
        }
        return null;
    }
}
