<?php

namespace view\scripts;
use app\view\AbstractView;

class AreaSponzorFormJs extends AbstractView {
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
			
			jQuery.validator.addMethod("validateAlnumpunct", function(value, element) {
				if (value) return /^[A-Za-z0-9čćžšđČĆŽŠĐ \t\n\r._,-]+$/.test(value);
				else return true;
			}, "Neispravan unos (samo znamenke, slova, razmaci i osnovni interpunkcijski znakovi)");
		   
			$("#areaSponzorForm").validate({
				rules: {
					iznosDonacije: {
						validateDecimal: true,
						required: true
					},
					napomena: {
						validateAlnumpunct: true
					},
					idSponzora: {
						required: true
					},
					idPodrucja: {
						required: true
					}
				},
				messages: {
					iznosDonacije: {
						required: "Morate unijeti iznos donacije"
					},
					idSponzora: {
						required: "Morate odabrati sponzora"
					},
					idPodrucja: {
						required: "Morate odabrati područje"
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