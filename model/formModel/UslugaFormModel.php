<?php

namespace model\formModel;
use app\model\AbstractFormModel;

class UslugaFormModel extends AbstractFormModel {
    
    protected function rules() {
        if ($this->rulesArray === null) {
            $this->rulesArray = array('nazivUsluge' => array('required', 'words'));
        }
        return $this->rulesArray;
    }
    
    public function decypherErrors($pov) {
        if(count($pov)) {
            foreach ($pov as $k => $v) {
                switch ($k) {
                    case 'nazivUsluge':
                        return "Naziv usluge može sadržavati samo znamenke i slova!";
                    default:
                        break;
                }
            }
        }
        return null;
    }
}
