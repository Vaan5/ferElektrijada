<?php

namespace view\scripts\ozsn;
use app\view\AbstractView;

class TvrtkaListJs extends AbstractView {
    protected function outputHTML() {
?>
	<script src="../assets/js/confirm.js"></script>
	
	<script type="text/javascript">
		$(function(){
			// When Uredi is clicked, show form
			var idTvrtke;
			$('.editTvrtka').click(function () {
				idTvrtke = $(this).data("id");
				$('.modify-' + idTvrtke).hide();
				$('.modifyOn-' + idTvrtke).show();
			});
			
			// When Obriši is clicked, show confirmation
			$('.deleteTvrtka').confirm({
				text: "Jeste li sigurni da želite obrisati?",
				title: "Potrebna potvrda",
				confirmButton: "Obriši",
				cancelButton: "Odustani"
			});
			
			// When addTvrtka is clicked, show form for adding
			$('#addTvrtka').click( function () {
				$('.addTvrtka').hide();
				$('.addTvrtkaOn').show();
				$('.alert').hide();
			});
		});
	</script>    
<?php
    }
}