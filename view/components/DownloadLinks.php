<?php

namespace view\components;
use app\view\AbstractView;

class DownloadLinks extends AbstractView {
    private $route;
	
    protected function outputHTML() {
?>
	<a href="<?php echo $this->route . "?type=pdf";?>">PDF</a>
	<a href="<?php echo $this->route . "?type=xls";?>">XLS</a>
	<a href="<?php echo $this->route . "?type=xlsx";?>">XLSX</a>
<?php
    }

	public function setRoute($route) {
		$this->route = $route;
		return $this;
	}

}