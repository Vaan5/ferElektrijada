<?php

namespace view\scripts\ozsn;
use app\view\AbstractView;

class RadnoMjestoListJs extends AbstractView {
    protected function outputHTML() {
?>
	<script src="../assets/js/confirm.js"></script>
	
	<script type="text/javascript">
		$(function(){
			// When Uredi is clicked, show form
			var idRadnogMjesta;
			$('.editRadnoMjesto').click(function () {
				idRadnogMjesta = $(this).data("id");
				$('.modify-' + idRadnogMjesta).hide();
				$('.modifyOn-' + idRadnogMjesta).show();
			});
			
			// When Obriši is clicked, show confirmation
			$('.deleteRadnoMjesto').confirm({
				text: "Jeste li sigurni da želite obrisati?",
				title: "Potrebna potvrda",
				confirmButton: "Obriši",
				cancelButton: "Odustani"
			});
			
			// When addRadnoMjesto is clicked, show form for adding
			$('#addRadnoMjesto').click( function () {
				$('.addRadnoMjesto').hide();
				$('.addRadnoMjestoOn').show();
				$('.alert').hide();
			});
		});
	</script>    
<?php
    }
}