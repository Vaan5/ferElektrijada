<?php

namespace view\scripts\ozsn;
use app\view\AbstractView;

class UdrugaListJs extends AbstractView {
    protected function outputHTML() {
?>
	<script src="../assets/js/confirm.js"></script>
	
	<script type="text/javascript">
		$(function(){
			// When Uredi is clicked, show form
			var idUdruge;
			$('.editUdruga').click(function () {
				idUdruge = $(this).data("id");
				$('.modify-' + idUdruge).hide();
				$('.modifyOn-' + idUdruge).show();
			});
			
			// When Obriši is clicked, show confirmation
			$('.deleteUdruga').confirm({
				text: "Jeste li sigurni da želite obrisati?",
				title: "Potrebna potvrda",
				confirmButton: "Obriši",
				cancelButton: "Odustani"
			});
			
			// When addUdruga is clicked, show form for adding
			$('#addUdruga').click( function () {
				$('.addUdruga').hide();
				$('.addUdrugaOn').show();
				$('.alert').hide();
			});
		});
	</script>    
<?php
    }
}