
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
                return 'smjer';
            }
            
            public function getPrimaryKeyColumn(){
                return 'idSmjera';
            }
            
            public function getColumns(){
                return 'nazivSmjera';
            }
			}
?>
