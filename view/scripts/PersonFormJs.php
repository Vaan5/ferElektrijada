<?php

namespace view\scripts;
use app\view\AbstractView;

class PersonFormJs extends AbstractView {
	private $modification = false;
	
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
		   
		   // Adding custom methods
			jQuery.validator.addMethod("validateUsername", function(value, element) {
				if (value) return /^[a-zA-Z0-9]{3,16}$/.test(value);
				else return true;
			}, "Neispravno korisničko ime");
			
			jQuery.validator.addMethod("validatePassword", function(value, element) {
				if (value) return /^[a-zA-Z0-9]{3,18}$/.test(value);
				else return true;
			}, "Neispravna lozinka");
			
			jQuery.validator.addMethod("validateName", function(value, element) {
				if (value) return /^[A-ZČŠĐŽĆ][a-zčćžšđ]{2,}$/.test(value);
				else return true;
			}, "Neispravno ime");
			
			jQuery.validator.addMethod("validateSurname", function(value, element) {
				if (value) return /^[A-ZČŠĐŽĆ][a-zčćžšđ]{2,}$/.test(value);
				else return true;
			}, "Neispravno prezime");
			
			jQuery.validator.addMethod("validateMail", function(value, element) {
				if (value) return /^[A-Za-z0-9_.+-]+@(?:[A-Za-z0-9]+\.)+[A-Za-z]{2,3}$/.test(value);
				else return true;
			}, "Neispravna e-mail adresa");
			
			jQuery.validator.addMethod("validateNumbers", function(value, element) {
				if (value) return /^[0-9]{1,}$/.test(value);
				else return true;
			}, "Neispravan broj");
			
			jQuery.validator.addMethod("validateJmbag", function(value, element) {
				if (value) return /^[0-9]{10}$/.test(value);
				else return true;
			}, "Neispravan JMBAG");
			
			jQuery.validator.addMethod("validateOib", function(value, element) {
				if (value) return /^[0-9]{11}$/.test(value);
				else return true;
			}, "Neispravan OIB");
		   
			$("#personForm").validate({
				rules: {
					ferId: {
						required: true,
						validateUsername: true
					},
					password: {
						<?php if(!$this->modification) echo "required: true,"; ?>
						validatePassword: true
					},
<?php if ($this->modification) { ?>
					password_new: {
						validatePassword: true,
						required: {
							depends: function(element){
								return $("input[name=password]").val()!=""
							}
						}
					},
					password_new2: {
						validatePassword: true,
						equalTo: "input[name=password_new]"
					},
<?php } ?>
					ime: {
						validateName: true
					},
					prezime: {
						validateSurname: true
					},
					mail: {
						validateMail: true
					},
					brojMob: {
						validateNumbers: true
					},
					JMBAG: {
						validateJmbag: true
					},
					OIB: {
						validateOib: true
					},
					MBG: {
						validateNumbers: true
					}
				},
				messages: {
					ferId: {
						required: "Morate unijeti korisničko ime"
					},
					password: {
						required: "Morate unijeti lozinku"
					},
					password_new: {
						required: "Morate unijeti novu lozinku"
					},
					password_new2: {
						equalTo: "Ponovljena lozinka ne odgovara novoj lozinci"
					},
					brojMob: {
						validateNumbers: "Neispravan broj mobitela"
					},
					MBG: {
						validateNumbers: "Neispravan matični broj osiguranika"
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
	
	public function setModification($modification) {
		$this->modification = $modification;
		return $this;
	}
}