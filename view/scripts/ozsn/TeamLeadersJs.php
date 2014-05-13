<?php

namespace view\scripts\ozsn;
use app\view\AbstractView;

class TeamLeadersJs extends AbstractView {
    protected function outputHTML() {
?>
	<script src="../assets/js/confirm.js"></script>
	
	<script type="text/javascript">
		$(function(){
			// When dodajVoditelja is clicked, show form for adding
			$('.dodajVoditelja').click( function () {
				$('.dodajVoditelja').hide();
				$('.dodajVoditeljaOn').show();
				$('.alert').hide();
			});
			
			$('.chooseType').change(function () {
				var action = $(this).val() == "Novi korisnik" ? "<?php echo \route\Route::get('d3')->generate(array(
										"controller" => "ozsn",
										"action" => "addTeamLeader"
									)) ?>" : "<?php echo \route\Route::get('d3')->generate(array(
										"controller" => "ozsn",
										"action" => "addExistingTeamLeader"
									)) ?>";
				$('.addForm').attr("action", action);
			});
			
			// When Obriši is clicked, show confirmation
			$('.deleteTeamLeader').confirm({
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