<?php

namespace model\formModel;
use app\model\AbstractFormModel;

class ZavodFormModel extends AbstractFormModel {
    
    protected function rules() {
        if ($this->rulesArray === null) {
            $this->rulesArray = array('nazivZavoda' => array('required', 'alnum'),'skraceniNaziv' => array('required', 'alnum'));
        }
        return $this->rulesArray;
    }
    
    public function decypherErrors($pov) {
        if(count($pov)) {
            foreach ($pov as $k => $v) {
                switch ($k) {
                    case 'nazivZavoda':
                        return "Naziv zavoda može sadržavati samo znamenke i slova!";
					case 'skraceniNaziv':
                        return "Skraćeni naziv može sadržavati samo znamenke i slova!";
                    default:
                        break;
                }
            }
        }
        return null;
    }
}
