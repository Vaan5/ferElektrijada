<?php

namespace model;
use app\model\AbstractFormModel;

class LoginFormModel extends AbstractFormModel {
    
    protected function rules() {
        return array('password' => array('password'),
            'username' => array('username'));
    }
}