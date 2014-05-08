<?php

namespace view\scripts\ozsn;
use app\view\AbstractView;

class GodStudListJs extends AbstractView {
    protected function outputHTML() {
?>
	<script src="../assets/js/confirm.js"></script>
	
	<script type="text/javascript">
		$(function(){
			// When Uredi is clicked, show form
			var idGodStud;
			$('.editGodStud').click(function () {
				idGodStud = $(this).data("id");
				$('.modify-' + idGodStud).hide();
				$('.modifyOn-' + idGodStud).show();
			});
			
			// When Obriši is clicked, show confirmation
			$('.deleteGodStud').confirm({
				text: "Jeste li sigurni da želite obrisati?",
				title: "Potrebna potvrda",
				confirmButton: "Obriši",
				cancelButton: "Odustani"
			});
			
			// When addGodStud is clicked, show form for adding
			$('#addGodStud').click( function () {
				$('.addGodStud').hide();
				$('.addGodStudOn').show();
				$('.alert').hide();
			});
		});
	</script>    
<?php
    }
}