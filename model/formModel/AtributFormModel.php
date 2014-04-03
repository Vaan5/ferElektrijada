<?php

namespace model\formModel;
use app\model\AbstractFormModel;

class AtributFormModel extends AbstractFormModel {
    
    protected function rules() {
        if ($this->rulesArray === null) {
            $this->rulesArray = array('nazivAtributa' => array('required', 'alnum'));
        }
        return $this->rulesArray;
    }
    
    public function decypherErrors($pov) {
        if(count($pov)) {
            foreach ($pov as $k => $v) {
                switch ($k) {
                    case 'nazivAtributa':
                        return "Naziv atributa može sadržavati samo znamenke i slova!";
                    default:
                        break;
                }
            }
        }
        return null;
    }
}
