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
			$('.urediAtribut').click(function () {
				$idAtributa = $(this).data("id");
				$('#span-' + $idAtributa).hide();
				$('#uredi-' + $idAtributa).hide();
				$('#obrisi-' + $idAtributa).hide();
				$('#input-' + $idAtributa).show();
				$('#submit-' + $idAtributa).show();
			});
			
			// When Obriši is clicked, show confirmation
			$('.obrisiAtribut').confirm({
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