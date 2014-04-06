<?php

namespace view\scripts\ozsn;
use app\view\AbstractView;

class AtributListJs extends AbstractView {
    protected function outputHTML() {
?>
	<script src="../assets/js/confirm.js"></script>
	
	<script type="text/javascript">
		$(function(){
			// When Uredi is clicked, show form
			var idAtributa;
			$('.editAtribut').click(function () {
				$idAtributa = $(this).data("id");
				$('.modify-' + $idAtributa).hide();
				$('.modifyOn-' + $idAtributa).show();
			});
			
			// When Obriši is clicked, show confirmation
			$('.deleteAtribut').confirm({
				text: "Jeste li sigurni da želite obrisati?",
				title: "Potrebna potvrda",
				confirmButton: "Obriši",
				cancelButton: "Odustani"
			});
			
			// When addAtribut is clicked, show form for adding
			$('#addAtribut').click( function () {
				$('.addAtribut').hide();
				$('.addAtributOn').show();
				$('.alert').hide();
			});
		});
	</script>    
<?php
    }
}