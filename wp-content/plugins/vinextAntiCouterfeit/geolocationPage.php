<?php

add_action("admin_menu", "add_geolocation_page_menu_item");
add_action("admin_init", "display_visitor_geolocation_fields");


function add_geolocation_page_menu_item() {
	add_menu_page("Visitor geolocation", "Visitor geolocation", "manage_options", "geolocation_page", "geolocation_settings_page", null, 99);
}

function geolocation_settings_page(){
		?>
			<div class="wrap">
			<h1>Géolocation de visiteurs</h1>
			<form method="post" action="options.php">
	        <?php
	            settings_fields("geoSection");
	            do_settings_sections("geolocation-options");      
				
	        ?>          
			</form>
			</div>
		<?php
}

function display_visitor_geolocation_fields() {
	?>
			<div class="wrap">
				<h1>Géolocation de visiteurs</h1>
				<h1></h1><h1></h1><h1></h1>
				<form>
				  Activer la fonction de scan de test :
				  <input type="date" name="next_lottery" value=<?php echo(get_option( 'checkAndSee_activate' )); ?>>
				</form>
				<h1></h1><h1></h1><h1></h1>
				<form>
				  Numéro de gagnant :
				  <input type="text" name="next_gagnant" value=<?php echo(get_option( 'lottery_number' ));?>>
				</form>
				<h1></h1><h1></h1><h1></h1>
				<form>
				  Lien pour la page de gagnant :
				  <input type="text" name="page_gagnant" value=<?php echo(BASE_URL . "?op=wn&prd=" . get_option('lottery_number' ));?> size="60">
				</form>
				
				<h1></h1><h1></h1><h1></h1>
				<button type="button" id="all_lottery">Tous Lotos</button>
				<h1></h1><h1></h1><h1></h1>
				<div>
					<table class="table-participant" style="width: 100%" id="list-table">
						<thead>
							<tr id="keepId">
								<th>Date de loto</th>
								<th>Nom et prénom</th>
								<th>Email</th>
								<th>Code Postal</th>
								<th>Numéro de Loto</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
						
					</table>
				</div>
			</div>
			
			
			<?php
	
}