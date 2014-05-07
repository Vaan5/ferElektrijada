<?php

namespace view\scripts\ozsn;
use app\view\AbstractView;

class VelMajiceListJs extends AbstractView {
    protected function outputHTML() {
?>
	<script src="../assets/js/confirm.js"></script>
	
	<script type="text/javascript">
		$(function(){
			// When Uredi is clicked, show form
			var idVelMajice;
			$('.editVelMajice').click(function () {
				idVelMajice = $(this).data("id");
				$('.modify-' + idVelMajice).hide();
				$('.modifyOn-' + idVelMajice).show();
			});
			
			// When Obriši is clicked, show confirmation
			$('.deleteVelMajice').confirm({
				text: "Jeste li sigurni da želite obrisati?",
				title: "Potrebna potvrda",
				confirmButton: "Obriši",
				cancelButton: "Odustani"
			});
			
			// When addVelMajice is clicked, show form for adding
			$('#addVelMajice').click( function () {
				$('.addVelMajice').hide();
				$('.addVelMajiceOn').show();
				$('.alert').hide();
			});
		});
	</script>    
<?php
    }
}