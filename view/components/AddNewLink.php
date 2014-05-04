<?php

namespace view\components;
use app\view\AbstractView;

class AddNewLink extends AbstractView {
    private $link;
	private $buttonText;
	
    protected function outputHTML() {
?>
	<div class="btn-group">
		<a class="btn btn-default" href="<?php echo $this->link; ?>"><span class="glyphicon glyphicon-plus"></span> <?php echo $this->buttonText; ?></a>
	</div>	
<?php
    }

	public function setLink($link) {
		$this->link = $link;
		return $this;
	}
	
	public function setButtonText($buttonText) {
		$this->buttonText = $buttonText;
		return $this;
	}

}