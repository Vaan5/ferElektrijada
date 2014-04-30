<?php

namespace view\scripts;
use app\view\AbstractView;

class KontaktOsobeFormJs extends AbstractView {
    protected function outputHTML() {
?>
	<script type="text/javascript">
		$( document ).ready(function(){
			var i;
			var j;
			
			i = $('.lastMob').data("number");
			j = $('.lastMail').data("number");
			
			$('#dodajMobPolje').click(function () {
				i += 1;
				$('.brojeviMobitela').append('<div class="form-group"><label for="mob" class="col-sm-3 control-label"></label><div class="col-sm-9"><input type="text" name="mob' + i + '" class="form-control" placeholder="Upišite broj mobitela" /></div></div>');
			});
			
			$('#dodajMailPolje').click(function () {
				j += 1;
				$('.mailovi').append('<div class="form-group"><label for="mail" class="col-sm-3 control-label"></label><div class="col-sm-9"><input type="text" name="mail' + j + '" class="form-control" placeholder="Upišite e-mail adresu" /></div></div>');
			});
		});
	</script>    
<?php
    }
}