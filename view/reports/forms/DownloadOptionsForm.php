<?php

namespace view\reports\forms;
use app\view\AbstractView;

class DownloadOptionsForm extends AbstractView {
    
    protected function outputHTML() {
?>
	<div class="checkbox">
            <label>
                Odaberite format izvješća: &nbsp;
            </label>
        </div>
	<div class="checkbox">
            <label>
                <input type="radio" name="type" value="pdf"> pdf &nbsp;
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="radio" name="type" value="xls"> xls &nbsp;
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="radio" name="type" value="xlsx"> xlsx &nbsp;
            </label>
        </div><br><br>
<?php
    }
    
}