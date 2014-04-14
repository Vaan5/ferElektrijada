<?php

namespace view\reports\forms;
use app\view\AbstractView;

class DownloadOptionsForm extends AbstractView {
    
    protected function outputHTML() {
?>
    <p>
	Odaberite format izvješća:
	<p>
	    <input type="radio" name="type" value="pdf" /> pdf
	    <br/>
	    <input type="radio" name="type" value="xls" /> xls
	    <br/>
	    <input type="radio" name="type" value="xlsx" /> xlsx
	</p>
    </p>
<?php
    }
    
}