<?php

namespace view\scripts;
use app\view\AbstractView;

class TvrtkaAssignFormJs extends AbstractView {
    protected function outputHTML() {
?>
	<!-- Include files for validation -->
	<script src="../assets/js/jquery.validate.js"></script>
	
	<script type="text/javascript">
		$( document ).ready(function() {
			/*	======  FORM VALIDATION ======
			*	Includes validator JS file, sets rules and messages
			*	On keyup or on submit checks if input is valid
			*	Prints error message in case it's not valid
			*/
		   
		   // Adding custom methods			
			jQuery.validator.addMethod("validateDecimal", function(value, element) {
				if (value) return /^[0-9]+(\.[0-9]+)?$/.test(value);
				else return true;
			}, "Neispravan unos decimalnog broja (koristiti točku)");
			
			jQuery.validator.addMethod("validateWords", function(value, element) {
				if (value) return /^[A-Za-z0-9čćžšđČĆŽŠĐ -]+$/.test(value);
				else return true;
			}, "Neispravan unos");
			
			jQuery.validator.addMethod("validateAlnumpunct", function(value, element) {
				if (value) return /^[A-Za-z0-9čćžšđČĆŽŠĐ \t\n\r._,-]+$/.test(value);
				else return true;
			}, "Neispravan unos napomene");
		   
			$("#tvrtkaAssignForm").validate({
				rules: {
					iznosRacuna: {
						required: true,
						validateDecimal: true
					},
					nacinPlacanja: {
						validateWords: true
					},
					napomena: {
						validateAlnumpunct: true
					},
					idUsluge: {
						required: true
					}
				},
				messages: {
					iznosRacuna: {
						required: "Morate unijeti iznos računa"
					},
					idUsluge: {
						required: "Morate odabrati uslugu"
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