<?php

namespace view\scripts;
use app\view\AbstractView;

class LoginFormJs extends AbstractView {
    protected function outputHTML() {
?>
		<script src="../assets/js/jquery.validate.js"></script>

		<script type="text/javascript">
			$(function(){
				/*	======  FORM VALIDATION ======
				*	Includes validator JS file, sets rules and messages
				*	On keyup or on submit checks if input is valid
				*	Prints error message in case it's not valid
				*/
			   
			   // Adding custom methods
			   jQuery.validator.addMethod("validateUsername", function(value, element) {
					if (value) return /^[a-zA-Z0-9]{3,16}$/.test(value);
					else return true;
				}, "Neispravno korisničko ime");

				jQuery.validator.addMethod("validatePassword", function(value, element) {
					if (value) return /^[a-zA-Z0-9]{3,18}$/.test(value);
					else return true;
				}, "Neispravna lozinka");
				
				$("#loginForm").validate({
					rules: {
						userName: {
							required: true,
							validateUsername: true,
						},
						pass: {
							required: true,
							validatePassword: true,
						}
					},
					messages: {
						userName: {
							required: "Morate unijeti korisničko ime"
						},
						pass: {
							required: "Morate unijeti lozinku"
						}
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
