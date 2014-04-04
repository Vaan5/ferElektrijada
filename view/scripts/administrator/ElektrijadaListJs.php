<?php

namespace view\scripts\administrator;
use app\view\AbstractView;

class ElektrijadaListJs extends AbstractView {
    protected function outputHTML() {
?>
	<script src="../assets/js/confirm.js"></script>
	
	<script type="text/javascript">
		$(function(){
			// When Obriši is clicked, show confirmation
			$('.obrisiElektrijadu').confirm({
				text: "Jeste li sigurni da želite obrisati? U nastavku je potreban unos lozinke za potvrdu",
				title: "Potrebna potvrda",
				confirmButton: "Nastavi",
				cancelButton: "Odustani"
			});
		});
	</script>    
<?php
    }
}