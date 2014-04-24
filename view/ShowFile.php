<?php

namespace view;
use app\view\AbstractView;

class ShowFile extends AbstractView {
    private $path;
    private $type;
    
    protected function outputHTML() {
	
	switch ($this->type) {
	    case 'pdf':
		header("Content-type: application/pdf");
		header("Content-Disposition: attachment; filename=" . basename($this->path));
		@readfile($this->path);
		die();
		break;
	    case 'xls':
		header('Content-disposition: attachment; filename='.basename('./' .$this->path));
		header("Content-Type: application/vnd.ms-excel");
		header('Content-Length: ' . filesize('./' .$this->path));
		header('Content-Transfer-Encoding: binary');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		ob_clean();
		flush();
		@readfile('./' .$this->path);
		unlink('./' .$this->path);
		die();
		break;
	    case 'xlsx':
		header('Content-disposition: attachment; filename='.basename('./' .$this->path));
		header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Length: ' . filesize('./' .$this->path));
		header('Content-Transfer-Encoding: binary');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		ob_clean();
		flush();
		@readfile('./' .$this->path);
		unlink('./' .$this->path);
		die();
		break;
	    default:
		break;
	}
    }
    
    public function setPath($path) {
	$this->path = $path;
	return $this;
    }

    public function setType($type) {
	$this->type = $type;
	return $this;
    }

}