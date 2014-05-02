<?php

namespace view\scripts\ozsn;
use app\view\AbstractView;

class ActiveObjavaListJs extends AbstractView {
    protected function outputHTML() {
?>
	<script src="../assets/js/confirm.js"></script>
	
	<script type="text/javascript">
		$(function(){			
			// When Obriši is clicked, show confirmation
			$('.obrisiActiveObjavu').confirm({
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