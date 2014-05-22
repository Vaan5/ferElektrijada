<?php

namespace view\scripts\ozsn;
use app\view\AbstractView;

class HallOfFameJs extends AbstractView {
    protected function outputHTML() {
?>
	<script src="../assets/js/confirm.js"></script>
	
	<script type="text/javascript">
		$(document).ready(function() {
			$(".fancyboxLoader").fancybox();
		});
	</script>    
<?php
    }
}