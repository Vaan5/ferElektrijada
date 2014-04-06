<?php

namespace model\formModel;
use app\model\AbstractFormModel;

class MailValidationModel extends AbstractFormModel {
    
    protected function rules() {
        if ($this->rulesArray === null) {
            $this->rulesArray = array('mail' => array('mail'));
        }
        return $this->rulesArray;
    }
    
    public function decypherErrors($pov) {
        if(count($pov)) {
            foreach ($pov as $k => $v) {
                switch ($k) {
                    case 'mail':
                        return "Neispravna mail adresa!";
                    default:
                        break;
                }
            }
        }
        return null;
    }
}
