<?php

        namespace model;
	use app\model\AbstractDBModel;
	
	class DBUdruga extends AbstractDBModel {
	    
	    /**
	     *
	     * @var boolean 
	     */
	    private $isLoggedIn = false;
            
            public function getTable(){
                return 'udruga';
            }
            
            public function getPrimaryKeyColumn(){
                return 'idUdruge';
            }
            
            public function getColumns(){
                return 'nazivUdruge';
            }
			}
?>