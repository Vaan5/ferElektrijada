<?php

namespace model;
use app\model\AbstractFormModel;

class LoginFormModel extends AbstractFormModel {
    
    protected function rules() {
        if ($this->rulesArray === null) {
            $this->rulesArray = array('password' => array('password'),
                'username' => array('username'));
        }
        return $this->rulesArray;
    }
}