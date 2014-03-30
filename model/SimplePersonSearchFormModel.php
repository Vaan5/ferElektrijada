<?php

namespace model;
use app\model\AbstractFormModel;

class SimplePersonSearchFormModel extends AbstractFormModel {
    
    protected function rules() {
        return array(
            'ferId' => array('username'),
            'ime' => array('name'), 
            'prezime' => array('name') 
            );
    }
    
    public function decypherErrors($pov) {
        if(count($pov)) {
            foreach ($pov as $k => $v) {
                switch ($k) {
                    case 'ferId':
                        return "Neispravno korisničko ime!";
                    case 'ime':
                        return "Neispravno ime!";
                    case 'prezime':
                        return "Neispravno prezime!";
                    default:
                        break;
                }
            }
        }
        return null;
    }
}
