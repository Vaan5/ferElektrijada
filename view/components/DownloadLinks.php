<?php

namespace view\components;
use app\view\AbstractView;

class DownloadLinks extends AbstractView {
    private $route;
	private $onlyParam = true;
	
    protected function outputHTML() {
		$param = $this->onlyParam === true ? "?type=" : "&type=";
?>
	<a href="<?php echo $this->route . $param . "pdf";?>">PDF</a>
	<a href="<?php echo $this->route . $param . "xls";?>">XLS</a>
	<a href="<?php echo $this->route . $param . "xlsx";?>">XLSX</a>
<?php
    }

	public function setRoute($route) {
		$this->route = $route;
		return $this;
	}
	
	public function setOnlyParam($onlyParam) {
		$this->onlyParam = $onlyParam;
		return $this;
	}

}