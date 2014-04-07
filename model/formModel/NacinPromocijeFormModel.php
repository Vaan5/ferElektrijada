<?php

namespace model\formModel;
use app\model\AbstractFormModel;

class NacinPromocijeFormModel extends AbstractFormModel {
    
    protected function rules() {
        if ($this->rulesArray === null) {
            $this->rulesArray = array('tipPromocije' => array('required', 'alnumpunct'));
        }
        return $this->rulesArray;
    }
    
    public function decypherErrors($pov) {
        if(count($pov)) {
            foreach ($pov as $k => $v) {
                switch ($k) {
                    case 'tipPromocije':
                        return "Tip promocije može sadržavati samo slova, znamenke, razmak, te osnovne interpunkcijske znakove!";
                    default:
                        break;
                }
            }
        }
        return null;
    }
}
