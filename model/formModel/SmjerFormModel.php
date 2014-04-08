<?php

namespace model\formModel;
use app\model\AbstractFormModel;

class SmjerFormModel extends AbstractFormModel {
    
    protected function rules() {
        if ($this->rulesArray === null) {
            $this->rulesArray = array('nazivSmjera' => array('required', 'words'));
        }
        return $this->rulesArray;
    }
    
    public function decypherErrors($pov) {
        if(count($pov)) {
            foreach ($pov as $k => $v) {
                switch ($k) {
                    case 'nazivSmjera':
                        return "Naziv smjera može sadržavati samo znamenke i slova!";
                    default:
                        break;
                }
            }
        }
        return null;
    }
}
