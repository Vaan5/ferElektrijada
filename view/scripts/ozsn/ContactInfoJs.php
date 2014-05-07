<?php

namespace view\scripts\ozsn;
use app\view\AbstractView;

class ContactInfoJs extends AbstractView {
    protected function outputHTML() {
?>
	<script src="../assets/js/confirm.js"></script>
	
	<script type="text/javascript">
		$(function(){
			// When Prikaži detalje is clicked, generate form and submit it
			var idKontakta;
			$('.prikaziDetalje').click(function () {
				idKontakta = $(this).data("id");
				$('<form action="<?php echo \route\Route::get('d3')->generate(array(
							"controller" => 'ozsn',
							"action" => 'displayContactInfo'
						)); ?>" method="POST">' + '<input type="hidden" name="idKontakta" value="' + idKontakta + '">' + '</form>').submit();
			});
		});
	</script>    
<?php
    }
}