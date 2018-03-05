<?php
add_action("admin_menu", "add_vac_menu_item");
add_action("admin_init", "display_vac_options_fields");

function add_vac_menu_item() {
	add_menu_page("Scan options", "Scan options", "manage_options", "vac_general_options", "vac_settings_page", null, 99);
}

function vac_settings_page(){
		?>
			<div class="wrap">
			<h1>General option panel</h1>
			<form method="post" action="options.php">
	        <?php
	            settings_fields("vacSection");
	            do_settings_sections("vac-options");      
	            submit_button(); 
	        ?>          
			</form>
			</div>
		<?php
}

function display_vac_options_fields()
{
	add_settings_section("vacSection", "Settings", null, "vac-options");
	
	add_settings_field("scanCounter_activate", "Activer le compteur à rebours", "display_scanCounter_checkbox", "vac-options", "vacSection");
	add_settings_field("scanCounter_period", "Période de validation de microtag après le premier scan (jour)", "display_scanCounter_period", "vac-options", "vacSection");
    
	
	register_setting("vacSection", "scanCounter_activate");
	register_setting("vacSection", "scanCounter_period");
}


function display_scanCounter_checkbox() {
	?>
		<input type="checkbox" name="scanCounter_activate" value="1" <?php checked(1, get_option('scanCounter_activate'), true); ?> /> 
	<?php
}

function display_scanCounter_period()
{
	?>
    	<input type="text" name="scanCounter_period" id="scanCounter_period" value="<?php echo get_option('scanCounter_period'); ?>" />
    <?php
}

?>