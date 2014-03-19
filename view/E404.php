<?php

namespace view;

use app\view\AbstractView;

class E404 extends AbstractView {

    public function outputHTML() {
?>
<p>Dogodila se pogreška!</p>
<?php
    }

}