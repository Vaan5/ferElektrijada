<?php

namespace app\model;

interface FormModel extends Model {
    
    public function validate();
}