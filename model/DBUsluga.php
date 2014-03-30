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
                return 'usluga';
            }
            
            public function getPrimaryKeyColumn(){
                return 'idUsluge';
            }
            
            public function getColumns(){
                return 'nazivUsluge';
            }
			}
?>

