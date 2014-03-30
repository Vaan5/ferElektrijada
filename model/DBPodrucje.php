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
                return 'podrucje';
            }
            
            public function getPrimaryKeyColumn(){
                return 'idPodrucja';
            }
            
            public function getColumns(){
                return array ('nazivPodrucja', 'idNadredjenog');
            }
			}
?>