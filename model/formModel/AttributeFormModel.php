<?php

namespace model\formModel;
use app\model\AbstractFormModel;

class AttributeFormModel extends AbstractFormModel {
    
    protected function rules() {
        if ($this->rulesArray === null) {
            $this->rulesArray = array('rezultatPojedinacni' => array('numbers'),
				'ukupanBrojSudionika' => 'numbers',
				'iznosUplate' => 'decimal');
        }
        return $this->rulesArray;
    }
    
    public function decypherErrors($pov) {
        if(count($pov)) {
            foreach ($pov as $k => $v) {
                switch ($k) {
                    case 'rezultatPojedinacni':
                        return "Rezultat može biti samo broj!";
					case 'ukupanBrojSudionika':
                        return "Broj sudionika može biti samo brojčana vrijednost!";
					case 'iznosUplate':
                        return "Iznos uplate može biti samo brojčana vrijednost!";
                    default:
                        break;
                }
            }
        }
        return null;
    }
}
