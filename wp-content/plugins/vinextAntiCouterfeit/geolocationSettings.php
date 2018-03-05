<?php

add_action("admin_menu", "add_visitor_menu_item");
add_action("admin_init", "display_visitor_geolocation_fields");

function add_visitor_menu_item() {
	add_menu_page("Geolocation", "Geolocation", "manage_options", "visitor_geolocation", "visitor_settings_page", null, 99);
}



function visitor_settings_page(){
		?>
			<div class="wrap">
			<h1>Geolocation panel</h1>
			<form method="post" action="options.php">
	        <?php
	            settings_fields("visitorSection");
	            do_settings_sections("visitor-options");      
	            submit_button(); 
	        ?>          
			</form>
			</div>
		<?php
}

function display_visitor_geolocation_fields()
{
	add_settings_section("visitorSection", "Settings", null, "visitor-options");
	
	add_settings_field("checkAndSee_activate", "Activer la fonction de scan de test", "display_checkAndSee_checkbox", "visitor-options", "visitorSection");
	add_settings_field("checkAndSee_loc", "Emplacement d'Evaflor", "display_checkAndSee_location", "visitor-options", "visitorSection");
    add_settings_field("checkAndSee_radius", "Rayon de précision (km)", "display_checkAndSee_radius", "visitor-options", "visitorSection");
	
	register_setting("visitorSection", "checkAndSee_loc");
	register_setting("visitorSection", "checkAndSee_radius");
	register_setting("visitorSection", "checkAndSee_activate");
}

function display_checkAndSee_location()
{
	?>
    	<input type="text" name="checkAndSee_loc" id="checkAndSee_loc" value="<?php echo get_option('checkAndSee_loc'); ?>" size="60"/>
    <?php
}

function display_checkAndSee_radius()
{
	?>
    	<input type="text" name="checkAndSee_radius" id="checkAndSee_radius" value="<?php echo get_option('checkAndSee_radius'); ?>" />
    <?php
}

function display_checkAndSee_checkbox() {
	?>
		<input type="checkbox" name="checkAndSee_activate" value="1" <?php checked(1, get_option('checkAndSee_activate'), true); ?> /> 
	<?php
}