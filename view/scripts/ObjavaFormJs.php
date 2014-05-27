<?php

namespace view\scripts;
use app\view\AbstractView;

class ObjavaFormJs extends AbstractView {
    protected function outputHTML() {
?>
	<!-- Include JS and CSS files for dateTimePicker and validation -->
	<link href="../assets/css/datetimepicker.css" rel="stylesheet">
	<script src="../assets/js/datetimepicker.js"></script>
	<script src="../assets/js/jquery.validate.js"></script>
	
	<script type="text/javascript">
		$( document ).ready(function() {
			$('.datePicker').datetimepicker();
			
			/*	======  FORM VALIDATION ======
			*	Includes validator JS file, sets rules and messages
			*	On keyup or on submit checks if input is valid
			*	Prints error message in case it's not valid
			*/
		   
		   // Adding custom methods			
			jQuery.validator.addMethod("validateName", function(value, element) {
				if (value) return /^[A-ZČŠĐŽĆ][a-zčćžšđ]{2,}$/.test(value);
				else return true;
			}, "Neispravno ime");
			
			jQuery.validator.addMethod("validateSurname", function(value, element) {
				if (value) return /^[A-ZČŠĐŽĆ][a-zčćžšđ]{2,}$/.test(value);
				else return true;
			}, "Neispravno prezime");
			
			jQuery.validator.addMethod("validateUrl", function(value, element) {
				if (value) return /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/.test(value);
				else return true;
			}, "Neispravan unos linka");
		   
			$("#objavaForm").validate({
				rules: {
					"idElektrijade[]": {
						required: true
					},
					datumObjave: {
						required: true
					},
					autorIme: {
						validateName: true,
						required: true
					},
					autorPrezime: {
						validateSurname: true,
						required: true
					},
					link: {
						validateUrl: true
					},
					idMedija: {
						required: true
					}
				},
				messages: {
					"idElektrijade[]": {
						required: "Morate odabrati barem jednu elektrijadu"
					},
					datumObjave: {
						required: "Morate unijeti datum objave"
					},
					autorIme: {
						required: "Morate unijeti ime autora"
					},
					autorPrezime: {
						required: "Morate unijeti prezime autora"
					},
					idMedija: {
						required: "Morate odabrati medij"
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