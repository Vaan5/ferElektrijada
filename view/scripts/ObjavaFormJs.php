<?php

namespace view\scripts;
use app\view\AbstractView;

class ObjavaFormJs extends AbstractView {
    protected function outputHTML() {
?>
	<!-- Include JS and CSS files for dateTimePicker -->
	<link href="../assets/css/datetimepicker.css" rel="stylesheet">
	<script src="../assets/js/datetimepicker.js"></script>
	
	<script type="text/javascript">
		$(function(){
			$('.datePicker').datetimepicker();
		});
	</script>
<?php
    }
}