<?php

namespace view;
use app\view\AbstractView;

class Download extends AbstractView {

    private $path;
    
    protected function outputHTML() {
        if (file_exists($this->path)) {
	    header('Content-Description: File Transfer');
	    header('Content-Type: application/octet-stream');
	    header('Content-Disposition: attachment; filename='.basename($this->path));
	    header('Expires: 0');
	    header('Cache-Control: must-revalidate');
	    header('Pragma: public');
	    header('Content-Length: ' . filesize($this->path));
	    ob_clean();
	    flush();
	    readfile($this->path);
	    exit;
	}
    }
    
    public function setPath($path) {
	$this->path = $path;
	return $this;
    }

}