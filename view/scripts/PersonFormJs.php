<?php

namespace view\scripts;
use app\view\AbstractView;

class PersonFormJs extends AbstractView {
    protected function outputHTML() {
?>
	<!-- Include JS and CSS files for dateTimePicker, confirm and validator -->
	<link href="../assets/css/datetimepicker.css" rel="stylesheet">
	<script src="../assets/js/datetimepicker.js"></script>
	<script src="../assets/js/confirm.js"></script>
	<script src="../assets/js/jquery.validate.js"></script>
	
	<script type="text/javascript">
		$( document ).ready(function() {
			// Datepicker for choosing date
			$('.datePicker').datetimepicker();

			// Change password
			$("#promjeniLozinku").click(function() {
				$("#promjeniLozinku").hide();
				$(".promjeniLozinku").show();
				$("#passBr").hide();
			});

			// When Obriši is clicked, show confirmation
			$('.deletePerson').confirm({
				text: "Jeste li sigurni da želite obrisati?",
				title: "Potrebna potvrda",
				confirmButton: "Obriši",
				cancelButton: "Odustani"
			});
			
			/*	======  FORM VALIDATION ======
			*	Includes validator JS file, sets rules and messages
			*	On keyup or on submit checks if input is valid
			*	Prints error message in case it's not valid
			*/
			$("#personForm").validate({
				rules: {
					ferId: {
						required: true
					},
					password_new: {
						required: {
							depends: function(element){
								return $("input[name=password]").val()!=""
							}
						}
					},
					password_new2: {
						equalTo: "input[name=password_new]"
					}
				},
				messages: {
					ferId: "Morate unijeti korisničko ime",
					password_new: "Morate unijeti novu lozinku",
					password_new2: {
						equalTo: "Ponovljena lozinka ne odgovara novoj lozinci"
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