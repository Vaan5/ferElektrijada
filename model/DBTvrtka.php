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
                return 'tvrtka';
            }
            
            public function getPrimaryKeyColumn(){
                return 'idTvrtke';
            }
            
            public function getColumns(){
                return array ('imeTvrtke', 'adresaTvrtke');
            }
			}
?>

