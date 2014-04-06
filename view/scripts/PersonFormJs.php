<?php

namespace view\scripts;
use app\view\AbstractView;

class PersonFormJs extends AbstractView {
    protected function outputHTML() {
?>
	<!-- Include JS and CSS files for dateTimePicker -->
	<link href="../assets/css/datetimepicker.css" rel="stylesheet">
	<script src="../assets/js/datetimepicker.js"></script>
	<script src="../assets/js/confirm.js"></script>
	
	<script type="text/javascript">	
		$( document ).ready(function() {
			$(function(){
				$('.datePicker').datetimepicker();
			});
			
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
		});
	</script>    
<?php
    }
}