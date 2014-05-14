<?php

namespace view\scripts\administrator;
use app\view\AbstractView;

class AdminDoubleCheckJs extends AbstractView {
    protected function outputHTML() {
		/*	Validates password confirm form
		 *	Includes validator JS file
		 *	On keyup or on submit checks if pass field is empty
		 *	Prints error message
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