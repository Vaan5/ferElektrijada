<?php

namespace model\formModel;
use app\model\AbstractFormModel;

class NumberValidationModel extends AbstractFormModel {
    
    protected function rules() {
        if ($this->rulesArray === null) {
            $this->rulesArray = array('number' => array('phone'));
        }
        return $this->rulesArray;
    }
    
    public function decypherErrors($pov) {
        if(count($pov)) {
            foreach ($pov as $k => $v) {
                switch ($k) {
                    case 'number':
                        return "Neispravan telefonski broj!";
                    default:
                        break;
                }
            }
        }
        return null;
    }
}
