<?php

namespace view\scripts;
use app\view\AbstractView;

class KontaktOsobeFormJs extends AbstractView {
    protected function outputHTML() {
?>
	<!-- Include files for validation -->
	<script src="../assets/js/jquery.validate.js"></script>
	
	<script type="text/javascript">
		$( document ).ready(function(){
			$('body').tooltip({
				selector: '.btn'
			});
			
			var i;
			var j;
			
			i = $('.lastMob').data("number");
			j = $('.lastMail').data("number");
			
			$('#dodajMobPolje').click(function () {
				i += 1;
				$('.brojeviMobitela').append('<div class="form-group" id="brojMoba' + i + '"><label for="mob" class="col-sm-3 control-label"></label><div class="col-sm-9"><div class="input-group"><input type="text" name="mob' + i + '" class="form-control" placeholder="Upišite broj mobitela" /><div class="input-group-btn"><button type="button" class="btn btn-danger removeMob" data-toggle="tooltip" data-removeId="' + i + '" data-placement="right" data-title="Obriši broj">x</button></div></div></div></div>');
				$('[name="mob'+i+'"]').rules('add', {
					validatePhone: true
				});
			});
			
			$('#dodajMailPolje').click(function () {
				j += 1;
				$('.mailovi').append('<div class="form-group" id="mailAdresa' + j + '"><label for="mail" class="col-sm-3 control-label"></label><div class="col-sm-9"><div class="input-group"><input type="text" name="mail' + j + '" class="form-control" placeholder="Upišite e-mail adresu" /><div class="input-group-btn"><button type="button" class="btn btn-danger removeMail" data-toggle="tooltip" data-removeId="' + j + '" data-placement="right" data-title="Obriši e-mail adresu">x</button></div></div</div></div>');
				$('[name="mail'+j+'"]').rules('add', {
					validateMail: true
				});
			});
			
			var removeMob;
			
			$(document).on( "click", '.removeMob', function(){
				removeMob = $(this).data("removeid");
				$('input[name="mob' + removeMob + '"]').rules( "remove" );
				$('input[name="mob' + removeMob + '"]').val("");
				$('#brojMoba' + removeMob).hide();

				if(removeMob == 1)
				{
					var k = 1;
					
					while(k <= $('.lastMob').data("number"))
					{
						if($('#brojMoba' + k).is(':visible'))
						{
							$('#brojMobaSpan' + k).show();
							break;
						}
						k++;
					}
				}
			});
				
			var removeMail;
			
			$(document).on( "click", '.removeMail', function(){
				removeMail = $(this).data("removeid");
				$('input[name="mail' + removeMail + '"]').rules( "remove" );
				$('input[name="mail' + removeMail + '"]').val("");
				$('#mailAdresa' + removeMail).hide();

				if(removeMail == 1)
				{
					var k = 1;
					
					while(k <= $('.lastMail').data("number"))
					{
						if($('#mailAdresa' + k).is(':visible'))
						{
							$('#mailAdresaSpan' + k).show();
							break;
						}
						k++;
					}
				}
			});
			
			/*	======  FORM VALIDATION ======
			*	Includes validator JS file, sets rules and messages
			*	On keyup or on submit checks if input is valid
			*	Prints error message in case it's not valid
			*/
		   
		   // Adding custom methods
		   jQuery.validator.addMethod("validateTSM", function(value, element) {
				if (!$('select[name="idTvrtke"]').val() && !$('select[name="idSponzora"]').val() && !$('select[name="idMedija"]').val()) return false;
				else return true;
			}, "Morate odabrati barem jednog sponzora, medij ili tvrtku!");
			
			jQuery.validator.addMethod("validateName", function(value, element) {
				if (value) return /^[A-ZČŠĐŽĆ][a-zčćžšđ]{2,}$/.test(value);
				else return true;
			}, "Neispravno ime");
			
			jQuery.validator.addMethod("validateSurname", function(value, element) {
				if (value) return /^[A-ZČŠĐŽĆ][a-zčćžšđ]{2,}$/.test(value);
				else return true;
			}, "Neispravno prezime");
			
			jQuery.validator.addMethod("validateNumbers", function(value, element) {
				if (value) return /^[0-9]{1,}$/.test(value);
				else return true;
			}, "Neispravan broj");
			
			jQuery.validator.addMethod("validatePhone", function(value, element) {
				if (value) return /^[0-9]{6,}$/.test(value);
				else return true;
			}, "Neispravan broj telefona");
			
			jQuery.validator.addMethod("validateRadnoMjesto", function(value, element) {
				if (value) return /^[A-Za-z0-9čćžšđČĆŽŠĐ]+$/.test(value);
				else return true;
			}, "Naziv radnog mjesta može sadržavati samo znamenke i slova!");
			
			jQuery.validator.addMethod("validateMail", function(value, element) {
				if (value) return /^[A-Za-z0-9_.+-]+@(?:[A-Za-z0-9]+\.)+[A-Za-z]{2,3}$/.test(value);
				else return true;
			}, "Neispravna e-mail adresa");
		   
			$("#kontaktOsobeForm").validate({
				rules: {
					idTvrtke: {
						validateTSM: true
					},
					idSponzora: {
						validateTSM: true
					},
					idMedija: {
						validateTSM: true
					},
					imeKontakt: {
						required: true,
						validateName: true
					},
					prezimeKontakt: {
						required: true,
						validateSurname: true
					},
					telefon: {
						validatePhone: true
					},
					radnoMjesto: {
						validateRadnoMjesto: true
					}
				},
				messages: {
					imeKontakt: {
						required: "Morate unijeti ime kontakt osobe"
					},
					prezimeKontakt: {
						required: "Morate unijeti prezime kontakt osobe"
					}
				}
			});
			
			$('[name^="mob"]').each(function() {
				$(this).rules('add', {
					validatePhone: true
				});
			});
			
			$('[name^="mail"]').each(function() {
				$(this).rules('add', {
					validateMail: true
				});
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