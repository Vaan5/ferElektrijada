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
			var idVelicine;
			$('.urediVelicinu').click(function () {
				$idVelicine = $(this).data("id");
				$('#span-' + $idVelicine).hide();
				$('#uredi-' + $idVelicine).hide();
				$('#obrisi-' + $idVelicine).hide();
				$('#input-' + $idVelicine).show();
				$('#submit-' + $idVelicine).show();
			});
			
			// When Obriši is clicked, show confirmation
			$('.obrisiVelicinu').confirm({
				text: "Jeste li sigurni da želite obrisati?",
				title: "Potrebna potvrda",
				confirmButton: "Obriši",
				cancelButton: "Odustani"
			});
			
			// When addAtribut is clicked, show form for adding
			$('#addVelicina').click( function () {
				$('#addVelicina').hide();
				$('#addVelicina_input').show();
				$('#addVelicina_submit').show();
			});
		});
	</script>    
<?php
    }
}