<?php

namespace model\formModel;
use app\model\AbstractFormModel;

class MedijFormModel extends AbstractFormModel {
    
    protected function rules() {
        if ($this->rulesArray === null) {
            $this->rulesArray = array('nazivMedija' => array('required', 'words'));
        }
        return $this->rulesArray;
    }
    
    public function decypherErrors($pov) {
        if(count($pov)) {
            foreach ($pov as $k => $v) {
                switch ($k) {
                    case 'nazivMedija':
                        return "Naziv medija može sadržavati samo slova i znamenke!";
                    default:
                        break;
                }
            }
        }
        return null;
    }
}
