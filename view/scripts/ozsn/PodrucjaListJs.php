<?php

namespace view\scripts\ozsn;
use app\view\AbstractView;

class PodrucjaListJs extends AbstractView {
    protected function outputHTML() {
?>
	<script src="../assets/js/confirm.js"></script>
	
	<script type="text/javascript">
		$(function(){
			// When Uredi is clicked, show form
			var idPodrucja;
			$('.editPodrucje').click(function () {
				idPodrucja = $(this).data("id");
				$('.modify-' + idPodrucja).hide();
				$('.modifyOn-' + idPodrucja).show();
			});
			
			// When Obriši is clicked, show confirmation
			$('.deletePodrucje').confirm({
				text: "Jeste li sigurni da želite obrisati?",
				title: "Potrebna potvrda",
				confirmButton: "Obriši",
				cancelButton: "Odustani"
			});
			
			// When addPodrucje is clicked, show form for adding
			$('#addPodrucje').click( function () {
				$('.addPodrucje').hide();
				$('.addPodrucjeOn').show();
				$('.alert').hide();
			});
		});
	</script>    
<?php
    }
}