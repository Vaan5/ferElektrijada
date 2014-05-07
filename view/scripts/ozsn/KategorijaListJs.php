<?php

namespace view\scripts\ozsn;
use app\view\AbstractView;

class KategorijaListJs extends AbstractView {
    protected function outputHTML() {
?>
	<script src="../assets/js/confirm.js"></script>
	
	<script type="text/javascript">
		$(function(){
			// When Uredi is clicked, show form
			var idKategorijeSponzora;
			$('.editKategorija').click(function () {
				idKategorijeSponzora = $(this).data("id");
				$('.modify-' + idKategorijeSponzora).hide();
				$('.modifyOn-' + idKategorijeSponzora).show();
			});
			
			// When Obriši is clicked, show confirmation
			$('.deleteKategorija').confirm({
				text: "Jeste li sigurni da želite obrisati?",
				title: "Potrebna potvrda",
				confirmButton: "Obriši",
				cancelButton: "Odustani"
			});
			
			// When addKategorija is clicked, show form for adding
			$('#addKategorija').click( function () {
				$('.addKategorija').hide();
				$('.addKategorijaOn').show();
				$('.alert').hide();
			});
		});
	</script>    
<?php
    }
}