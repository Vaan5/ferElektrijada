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
                <input type="radio" name="type" value="pdf"> <img src="../assets/img/pdf.gif" width="22" height="28"> PDF &nbsp;
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="radio" name="type" value="xls"> <img src="../assets/img/xls.png" width="22" height="22"> XLS &nbsp;
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="radio" name="type" value="xlsx"> <img src="../assets/img/xlsx.jpg" width="22" height="22"> XLSX &nbsp;
            </label>
        </div><br><br>
<?php
    }
    
}