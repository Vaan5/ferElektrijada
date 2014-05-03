<?php

namespace view\components;
use app\view\AbstractView;

class DownloadLinks extends AbstractView {
    private $route;
	private $onlyParam = true;
	
    protected function outputHTML() {
		$param = $this->onlyParam === true ? "?type=" : "&type=";
?>
	<div style="float:right;">
		
		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Izvoz <span class="caret"></span></button>
			<ul class="dropdown-menu" role="menu">
				<li><a href="<?php echo $this->route . $param . "pdf";?>">PDF</a></li>
				<li><a href="<?php echo $this->route . $param . "xls";?>">XSL</a></li>
				<li><a href="<?php echo $this->route . $param . "xlsx";?>">XSLX</a></li>
			</ul>
		</div>		
	</div>
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