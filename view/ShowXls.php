<?php

namespace view;
use app\view\AbstractView;

class ShowXls extends AbstractView {
    
    private $fileName;
    
    protected function outputHTML() {
        header('Content-disposition: attachment; filename='.basename($this->fileName));
	header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Length: ' . filesize($this->fileName));
	header('Content-Transfer-Encoding: binary');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	ob_clean();
	flush(); 
	
	readfile($this->fileName);
	unlink($this->fileName);
	die();
    }
    
    public function setFileName($fileName) {
	$this->fileName = $fileName;
	return $this;
    }

}