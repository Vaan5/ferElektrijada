<?php

namespace view\scripts\administrator;
use app\view\AbstractView;

class AdminDoubleCheckJs extends AbstractView {
    protected function outputHTML() {
		/*	======  FORM VALIDATION ======
		 *	Includes validator JS file, sets rules and messages
		 *	On keyup or on submit checks if input is valid
		 *	Prints error message in case it's not valid
		 */
?>
	<script src="../assets/js/jquery.validate.js"></script>
	
	<script type="text/javascript">
		$(function(){
			$("#loginForm").validate({
				rules: {
					pass: "required"
				},
				messages: {
					pass: "Morate unijeti lozinku!"
				}
			});
		});
	</script>
	
	<style type="text/css">
		.error
		{
			border-color: red;
			color: red;
			padding-top:4px;
		}
	</style>
<?php
    }
}