<?php

namespace model\formModel;
use app\model\AbstractFormModel;

class TvrtkaAssignFormModel extends AbstractFormModel {
    
    protected function rules() {
        if ($this->rulesArray === null) {
            $this->rulesArray = array('iznosRacuna' => array('required', 'decimal'),
					'nacinPlacanja' => array('words'),
					'napomena' => array('alnumpunct'),
					'idUsluge' => array('required', 'numbers'));
        }
        return $this->rulesArray;
    }
    
    public function decypherErrors($pov) {
        if(count($pov)) {
            foreach ($pov as $k => $v) {
                switch ($k) {
                    case 'iznosRacuna':
                        return $v == 'required' ? "Iznos računa je obavezno polje" : "Iznos računa je decimalni broj!";
		    case 'nacinPlacanja':
                        return "Način plaćanja može sadržavati samo znamenke i slova!";
		    case 'napomena':
			return "Napomena može sadržavati samo znamenke i slova, te osnovne interpunkcijske znakove!";
		    case 'idUsluge':
			return $v === 'required' ? "Morate odabrati uslugu" : "Nepoznata usluga!";
                    default:
                        break;
                }
            }
        }
        return null;
    }
}
