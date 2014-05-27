<?php

namespace view\scripts;
use app\view\AbstractView;

class ElektrijadaFormJs extends AbstractView {
    protected function outputHTML() {
?>
	<!-- Include JS and CSS files for dateTimePicker and validation -->
	<link href="../assets/css/datetimepicker.css" rel="stylesheet">
	<script src="../assets/js/datetimepicker.js"></script>
	<script src="../assets/js/jquery.validate.js"></script>
	
	<script type="text/javascript">
		$( document ).ready(function() {
			// Datepicker for choosing date
			$('.datePicker').datetimepicker();
			
			/*	======  FORM VALIDATION ======
			*	Includes validator JS file, sets rules and messages
			*	On keyup or on submit checks if input is valid
			*	Prints error message in case it's not valid
			*/
		   
		   // Adding custom methods			
			jQuery.validator.addMethod("validateNumbers", function(value, element) {
				if (value) return /^[0-9]{1,}$/.test(value);
				else return true;
			}, "Neispravan broj");
			
			jQuery.validator.addMethod("validateWords", function(value, element) {
				if (value) return /^[A-Za-z0-9čćžšđČĆŽŠĐ -]+$/.test(value);
				else return true;
			}, "Neispravan naziv");
			
			jQuery.validator.addMethod("validateUkupniRezultat", function(value, element) {
				if (value && parseInt(value, 10) > parseInt($('input[name="ukupanBrojSudionika"]').val(), 10)) return false;
				else return true;
			}, "Ukupni rezultat mora biti manji od broja sudionika!");
			
			jQuery.validator.addMethod("validateDatumKraja", function(value, element) {
				if (value && (new Date(value).getTime() <= new Date($('input[name="datumPocetka"]').val()).getTime())) return false;
				else return true;
			}, "Datum kraja mora biti veći od datuma početka!");
		   
			$("#elektrijadaForm").validate({
				rules: {
					mjestoOdrzavanja: {
						required: true,
						validateWords: true
					},
					datumPocetka: {
						required: true
					},
					datumKraja: {
						required: true,
						validateDatumKraja: true
					},
					ukupniRezultat: {
						validateNumbers: true,
						validateUkupniRezultat: true
					},
					drzava: {
						required: true,
						validateWords: true
					},
					ukupanBrojSudionika: {
						validateNumbers: true
					}
				},
				messages: {
					mjestoOdrzavanja: {
						required: "Morate unijeti mjesto održavanja",
						validateWords: "Pogrešno mjesto održavanja"
					},
					datumPocetka: {
						required: "Morate unijeti datum početka Elektrijade"
					},
					datumKraja: {
						required: "Morate unijeti datum završetka Elektrijade"
					},
					ukupniRezultat: {
						validateNumbers: "Neispravan rezultat! Mora biti broj!"
					},
					drzava: {
						required: "Morate unijeti naziv države",
						validateWords: "Pogrešan naziv države"
					},
					ukupanBrojSudionika: {
						validateNumbers: "Morate unijeti brojčanu vrijednost za broj sudionika!"
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