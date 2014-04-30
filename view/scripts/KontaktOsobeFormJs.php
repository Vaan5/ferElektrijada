<?php

namespace view\scripts;
use app\view\AbstractView;

class KontaktOsobeFormJs extends AbstractView {
    protected function outputHTML() {
?>
	<script type="text/javascript">
		$( document ).ready(function(){
			$('body').tooltip({
				selector: '.btn'
			});
			
			var i;
			var j;
			
			i = $('.lastMob').data("number");
			j = $('.lastMail').data("number");
			
			$('#dodajMobPolje').click(function () {
				i += 1;
				$('.brojeviMobitela').append('<div class="form-group" id="brojMoba' + i + '"><label for="mob" class="col-sm-3 control-label"></label><div class="col-sm-9"><div class="input-group"><input type="text" name="mob' + i + '" class="form-control" placeholder="Upišite broj mobitela" /><div class="input-group-btn"><button type="button" class="btn btn-danger removeMob" data-toggle="tooltip" data-removeId="' + i + '" data-placement="right" data-title="Obriši broj">x</button></div></div></div></div>');
			});
			
			$('#dodajMailPolje').click(function () {
				j += 1;
				$('.mailovi').append('<div class="form-group" id="mailAdresa' + j + '"><label for="mail" class="col-sm-3 control-label"></label><div class="col-sm-9"><div class="input-group"><input type="text" name="mail' + j + '" class="form-control" placeholder="Upišite e-mail adresu" /><div class="input-group-btn"><button type="button" class="btn btn-danger removeMob" data-toggle="tooltip" data-removeId="' + j + '" data-placement="right" data-title="Obriši e-mail adresu">x</button></div></div</div></div>');
			});
			
			var removeMob;
			
			$('.removeMob').on( "click", function(){
				removeMob = $(this).data("removeid");
				$('input[name="mob' + removeMob + '"]').val("");
				$('#brojMoba' + removeMob).hide();

				if(removeMob == 1)
				{
					var k = 1;
					
					while(k <= $('.lastMob').data("number"))
					{
						if($('#brojMoba' + k).is(':visible'))
						{
							$('#brojMobaSpan' + k).show();
							break;
						}
						k++;
					}
				}
			});
				
			var removeMail;
			
			$('.removeMail').on( "click", function(){
				removeMail = $(this).data("removeid");
				$('input[name="mail' + removeMail + '"]').val("");
				$('#mailAdresa' + removeMail).hide();

				if(removeMail == 1)
				{
					var k = 1;
					
					while(k <= $('.lastMail').data("number"))
					{
						if($('#mailAdresa' + k).is(':visible'))
						{
							$('#mailAdresaSpan' + k).show();
							break;
						}
						k++;
					}
				}
			});
		});
	</script>    
<?php
    }
}