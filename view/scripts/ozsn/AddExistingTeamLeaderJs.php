<?php

namespace view\scripts\ozsn;
use app\view\AbstractView;

class AddExistingTeamLeaderJs extends AbstractView {
    protected function outputHTML() {
?>	
	<script type="text/javascript">
		$(function(){
			/* When Proglasi voditeljem is clicked, this code gets idOsobe and idPodrucja and generates a virtual form with two hidden inputs
			   Then, this form is submitet via POST			 
			 */
			var idOsobe;
			var idPodrucja;
			$('.proglasiVoditeljem').click(function () {
				idOsobe = $(this).data("id");
				idPodrucja = $(this).data("idpodrucja");
				var dataObj = {};
				dataObj["idPodrucja"] = idPodrucja;
				dataObj[idOsobe] = "on";

				$.ajax({
					type: "POST",
					url: "<?php echo \route\Route::get('d3')->generate(array(
							"controller" => "ozsn",
							"action" => "addExistingTeamLeader"
						))?>",
					data: dataObj,
					cache: false,
					success: function(data, textStatus) {
						window.location.href = "<?php echo \route\Route::get('d3')->generate(array(
							"controller" => "ozsn",
							"action" => "displayTeamLeaders"
						))?>";
					}
				});
			});
		});
	</script>    
<?php
    }
}