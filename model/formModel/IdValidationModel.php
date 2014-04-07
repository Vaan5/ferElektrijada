<?php

namespace model\formModel;
use app\model\AbstractFormModel;

class IdValidationModel extends AbstractFormModel {
    
    protected function rules() {
        if ($this->rulesArray === null) {
            $this->rulesArray = array('id' => array('required', 'numbers'));
        }
        return $this->rulesArray;
    }
    
    public function decypherErrors($pov) {
        if(count($pov)) {
            foreach ($pov as $k => $v) {
                switch ($k) {
                    case 'id':
                        return "Nepostojeći zapis!";
                    default:
                        break;
                }
            }
        }
        return null;
    }
}
