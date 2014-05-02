<?php

namespace view\scripts\ozsn;
use app\view\AbstractView;

class MedijiListJs extends AbstractView {
    protected function outputHTML() {
?>
	<script src="../assets/js/confirm.js"></script>
	
	<script type="text/javascript">
		$(function(){
			// When Uredi is clicked, show form
			var idMedija;
			$('.editMedij').click(function () {
				idMedija = $(this).data("id");
				$('.modify-' + idMedija).hide();
				$('.modifyOn-' + idMedija).show();
			});
			
			// When Obriši is clicked, show confirmation
			$('.deleteMedij').confirm({
				text: "Jeste li sigurni da želite obrisati?",
				title: "Potrebna potvrda",
				confirmButton: "Obriši",
				cancelButton: "Odustani"
			});
			
			// When addMedij is clicked, show form for adding
			$('#addMedij').click( function () {
				$('.addMedij').hide();
				$('.addMedijOn').show();
				$('.alert').hide();
			});
		});
	</script>    
<?php
    }
}