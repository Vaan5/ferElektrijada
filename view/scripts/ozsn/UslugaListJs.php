<?php

namespace view\scripts\ozsn;
use app\view\AbstractView;

class UslugaListJs extends AbstractView {
    protected function outputHTML() {
?>
	<script src="../assets/js/confirm.js"></script>
	
	<script type="text/javascript">
		$(function(){
			// When Uredi is clicked, show form
			var idUsluge;
			$('.editUsluga').click(function () {
				idUsluge = $(this).data("id");
				$('.modify-' + idUsluge).hide();
				$('.modifyOn-' + idUsluge).show();
			});
			
			// When Obriši is clicked, show confirmation
			$('.deleteUsluga').confirm({
				text: "Jeste li sigurni da želite obrisati?",
				title: "Potrebna potvrda",
				confirmButton: "Obriši",
				cancelButton: "Odustani"
			});
			
			// When addUsluga is clicked, show form for adding
			$('#addUsluga').click( function () {
				$('.addUsluga').hide();
				$('.addUslugaOn').show();
				$('.alert').hide();
			});
		});
	</script>    
<?php
    }
}