<?php

namespace app\model;

interface DBModel extends Model {
    
    /**
     * 
     * @param mixed $primaryKey
     */
    public function load($primaryKey);
    
    public function save();
    
    public function delete();
}