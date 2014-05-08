<?php

namespace view\scripts\ozsn;
use app\view\AbstractView;

class ZavodListJs extends AbstractView {
    protected function outputHTML() {
?>
	<script src="../assets/js/confirm.js"></script>
	
	<script type="text/javascript">
		$(function(){
			// When Uredi is clicked, show form
			var idZavoda;
			$('.editZavod').click(function () {
				idZavoda = $(this).data("id");
				$('.modify-' + idZavoda).hide();
				$('.modifyOn-' + idZavoda).show();
			});
			
			// When Obriši is clicked, show confirmation
			$('.deleteZavod').confirm({
				text: "Jeste li sigurni da želite obrisati?",
				title: "Potrebna potvrda",
				confirmButton: "Obriši",
				cancelButton: "Odustani"
			});
			
			// When addZavod is clicked, show form for adding
			$('#addZavod').click( function () {
				$('.addZavod').hide();
				$('.addZavodOn').show();
				$('.alert').hide();
			});
		});
	</script>    
<?php
    }
}