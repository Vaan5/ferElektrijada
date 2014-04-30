<?php

namespace view\scripts\ozsn;
use app\view\AbstractView;

class SmjerListJs extends AbstractView {
    protected function outputHTML() {
?>
	<script src="../assets/js/confirm.js"></script>
	
	<script type="text/javascript">
		$(function(){
			// When Uredi is clicked, show form
			var idSmjera;
			$('.editSmjer').click(function () {
				idSmjera = $(this).data("id");
				$('.modify-' + idSmjera).hide();
				$('.modifyOn-' + idSmjera).show();
			});
			
			// When Obriši is clicked, show confirmation
			$('.deleteSmjer').confirm({
				text: "Jeste li sigurni da želite obrisati?",
				title: "Potrebna potvrda",
				confirmButton: "Obriši",
				cancelButton: "Odustani"
			});
			
			// When addSmjer is clicked, show form for adding
			$('#addSmjer').click( function () {
				$('.addSmjer').hide();
				$('.addSmjerOn').show();
				$('.alert').hide();
			});
		});
	</script>    
<?php
    }
}