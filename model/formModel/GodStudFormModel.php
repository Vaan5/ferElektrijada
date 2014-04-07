<?php

namespace model\formModel;
use app\model\AbstractFormModel;

class GodStudFormModel extends AbstractFormModel {
    
    protected function rules() {
        if ($this->rulesArray === null) {
            $this->rulesArray = array('studij' => array('required', 'alnum'),'godina' => array('required', 'alnum'));
        }
        return $this->rulesArray;
    }
    
    public function decypherErrors($pov) {
        if(count($pov)) {
            foreach ($pov as $k => $v) {
                switch ($k) {
                    case 'studij':
                        return "Studij može sadržavati samo znamenke i slova!";
					case 'godina':
                        return "Godina može sadržavati samo znamenke i slova!";
                    default:
                        break;
                }
            }
        }
        return null;
    }
}
