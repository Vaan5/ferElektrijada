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
                return 'kategorija';
            }
            
            public function getPrimaryKeyColumn(){
                return 'idKategorijeSponzora';
            }
            
            public function getColumns(){
                return 'tipKategorijeSponzora';
            }
			}
?>

