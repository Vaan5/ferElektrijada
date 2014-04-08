<?php

namespace view\scripts\ozsn;
use app\view\AbstractView;

class NacinPromocijeListJs extends AbstractView {
    protected function outputHTML() {
?>
	<script src="../assets/js/confirm.js"></script>
	
	<script type="text/javascript">
		$(function(){
			// When Uredi is clicked, show form
			var idPromocije;
			$('.editNacinPromocije').click(function () {
				$idPromocije = $(this).data("id");
				$('.modify-' + $idPromocije).hide();
				$('.modifyOn-' + $idPromocije).show();
			});
			
			// When Obriši is clicked, show confirmation
			$('.deleteNacinPromocije').confirm({
				text: "Jeste li sigurni da želite obrisati?",
				title: "Potrebna potvrda",
				confirmButton: "Obriši",
				cancelButton: "Odustani"
			});
			
			// When addNacinPromocije is clicked, show form for adding
			$('#addNacinPromocije').click( function () {
				$('.addNacinPromocije').hide();
				$('.addNacinPromocijeOn').show();
				$('.alert').hide();
			});
		});
	</script>    
<?php
    }
}