<?php

        namespace model;
	use app\model\AbstractDBModel;
	
	class DBSudjelovanje extends AbstractDBModel {
	    
	    /**
	     *
	     * @var boolean 
	     */
	    private $isLoggedIn = false;
            
            public function getTable(){
                return 'nacinpromocije';
            }
            
            public function getPrimaryKeyColumn(){
                return 'idPromocije';
            }
            
            public function getColumns(){
                return 'tipPromocije';
            }
			}
?>