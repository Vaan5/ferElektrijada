<?php

namespace view\components;
use app\view\AbstractView;

class AddNewLink extends AbstractView {
    private $link;
	private $class;
	private $buttonText;
	
    protected function outputHTML() {
?>
	<div class="btn-group">
		<a class="btn btn-default<?php if($this->class) echo ' ' . $this->class; ?>" href="<?php echo $this->link; ?>"><span class="glyphicon glyphicon-plus"></span> <?php echo $this->buttonText; ?></a>
	</div>	
<?php
    }

	public function setLink($link) {
		$this->link = $link;
		return $this;
	}
	
	public function setClass($class) {
		$this->class = $class;
		return $this;
	}
	
	public function setButtonText($buttonText) {
		$this->buttonText = $buttonText;
		return $this;
	}

}