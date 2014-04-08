<?php

namespace model\formModel;
use app\model\AbstractFormModel;

class SponzorFormModel extends AbstractFormModel {
    
    protected function rules() {
        if ($this->rulesArray === null) {
            $this->rulesArray = array('imeTvrtke' => array('required', 'alnum'),
					'adresaTvrtke' => array('required', 'alnumpunct'),
					'iznosDonacije' => array('decimal'),
					'napomena' => array('alnumpunct'));
        }
        return $this->rulesArray;
    }
    
    public function decypherErrors($pov) {
        if(count($pov)) {
            foreach ($pov as $k => $v) {
                switch ($k) {
                    case 'imeTvrtke':
                        return "Ime tvrtke može sadržavati samo znamenke i slova!";
		    case 'adresaTvrtke':
                        return "Adresa može sadržavati samo znamenke i slova, razmake i osnovne interpunkcijske znakove!";
		    case 'iznosDonacije':
                        return "Iznos donacije može biti samo decimalni broj s decimalnom točkom!";
		    case 'napomena':
                        return "Napomena može sadržavati samo znamenke i slova, razmake i osnovne interpunkcijske znakove!";
                    default:
                        break;
                }
            }
        }
        return null;
    }
}
