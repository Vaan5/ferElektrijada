<?php

	namespace model;
	use app\model\AbstractDBModel;
	
	class DBSudjelovanje extends AbstractDBModel {
	    
	    /**
	     *
	     * @var boolean 
	     */
	    private $isLoggedIn = false;
        
            public function getTable() {
                return 'radnomjesto';
            }
            
            public function getPrimaryKeyColumn(){
                return 'idRadnogMjesta';
            }
            
            public function getColumns(){
                return 'naziv';
            }
			}
?>
