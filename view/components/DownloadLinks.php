<?php

namespace view\components;
use app\view\AbstractView;

class DownloadLinks extends AbstractView {
    private $route;
	private $onlyParam = true;
	
    protected function outputHTML() {
		$param = $this->onlyParam === true ? "?type=" : "&type=";
?>
        <div class="form-group" style="float:right;">
		
		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Izvoz <span class="caret"></span></button>
			<ul class="dropdown-menu" role="menu">
                            <li><a href="<?php echo $this->route . $param . "pdf";?>"><img src="../assets/img/pdf.gif" width="22" height="28"> PDF</a></li>
				<li><a href="<?php echo $this->route . $param . "xls";?>"><img src="../assets/img/xls.png" width="22" height="22"> XLS</a></li>
				<li><a href="<?php echo $this->route . $param . "xlsx";?>"><img src="../assets/img/xlsx.jpg" width="22" height="22"> XLSX</a></li>
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