<?php

namespace view\scripts;
use app\view\AbstractView;

class ElektrijadaListJs extends AbstractView {
    protected function outputHTML() {
?>
	<script src="../assets/js/confirm.js"></script>
	
	<script type="text/javascript">
		$(function(){
			// When Obriši is clicked, show confirmation
			$('.obrisiElektrijadu').confirm({
				text: "Jeste li sigurni da želite obrisati?",
				title: "Potrebna potvrda",
				confirmButton: "Obriši",
				cancelButton: "Odustani"
			});
		});
	</script>    
<?php
    }
}