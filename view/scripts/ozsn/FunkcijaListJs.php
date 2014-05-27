<?php

namespace view\scripts\ozsn;
use app\view\AbstractView;

class FunkcijaListJs extends AbstractView {
    protected function outputHTML() {
?>
	<script src="../assets/js/confirm.js"></script>
	
	<script type="text/javascript">
		$(function(){
			// When Uredi is clicked, show form
			var idFunkcije;
			$('.editFunkcija').click(function () {
				idFunkcije = $(this).data("id");				
				$('.modify-' + idFunkcije).hide();
				$('.modifyOn-' + idFunkcije).show();
			});
			
			// When Obriši is clicked, show confirmation
			$('.deleteFunkcija').confirm({
				text: "Jeste li sigurni da želite obrisati?",
				title: "Potrebna potvrda",
				confirmButton: "Obriši",
				cancelButton: "Odustani"
			});
			
			// When addFunkcija is clicked, show form for adding
			$('#addFunkcija').click( function () {
				$('.addFunkcija').hide();
				$('.addFunkcijaOn').show();
				$('.alert').hide();
			});
		});
	</script>    
<?php
    }
}