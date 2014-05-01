<?php

namespace model\formModel;
use app\model\AbstractFormModel;

class ElekPodFormModel extends AbstractFormModel {
    
    protected function rules() {
        if ($this->rulesArray === null) {
            $this->rulesArray = array('rezultatGrupni' => array('required', 'numbers'),
										'ukupanBrojEkipa' => array('required', 'numbers'));
        }
        return $this->rulesArray;
    }
    
    public function decypherErrors($pov) {
        if(count($pov)) {
            foreach ($pov as $k => $v) {
                switch ($k) {
                    case 'rezultatGrupni':
                        return $v === 'required' ? "Rezultat je obavezan" : "Rezultat može biti samo brojčana vrijednost!";
					case 'ukupanBrojEkipa':
                        return $v === 'required' ? "Broj ekipa je obavezan" : "Broj ekipa može biti samo brojčana vrijednost!";
                    default:
                        break;
                }
            }
        }
        return null;
    }
}
