<?php

// Register the administration page
add_action('admin_menu', 'rpbchessboard_admin_register_interface');
function rpbchessboard_admin_register_interface()
{
	// Create the menu
	add_menu_page(
		__('Chess games and diagrams', 'rpbchessboard'),
		__('Chess', 'rpbchessboard'),
		'manage_options', 'rpbchessboard', rpbchessboard_admin_page_memo
	);

	// Page "memo" (same slug code that for the menu, to make it the default page).
	add_submenu_page('rpbchessboard',
		__('Chess games and diagrams', 'rpbchessboard') . ' - ' . __('Memo', 'rpbchessboard'),
		__('Memo', 'rpbchessboard'),
		'manage_options', 'rpbchessboard', rpbchessboard_admin_page_memo
	);

	// Page "options"
	add_submenu_page('rpbchessboard',
		__('Chess games and diagrams', 'rpbchessboard') . ' - ' . __('Options', 'rpbchessboard'),
		__('Options', 'rpbchessboard'),
		'manage_options', 'rpbchessboard-options', rpbchessboard_admin_page_options
	);
}


// Page hooks
function rpbchessboard_admin_page_memo   () { rpbchessboard_load_controller('Memo'   ); }
function rpbchessboard_admin_page_options() { rpbchessboard_load_controller('Options'); }


// Load the controller with the corresponding model name, and execute it.
function rpbchessboard_load_controller($modelName)
{
	require_once(RPBCHESSBOARD_ABSPATH.'controllers/admin.php');
	$controller = new RPBChessboardControllerAdmin($modelName);
	$controller->run();
}
