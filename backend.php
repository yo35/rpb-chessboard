<?php

// Register the administration page
add_action('admin_menu', 'rpbchessboard_admin_register_interface');
function rpbchessboard_admin_register_interface()
{
	add_options_page(
		__('Chess games and diagrams', 'rpbchessboard'),
		__('Chess games and diagrams', 'rpbchessboard'),
		'manage_options', 'rpbchessboard-admin', 'rpbchessboard_admin_build_interface'
	);
}


// Build the administration page
function rpbchessboard_admin_build_interface()
{
	echo '<div class="wrap">';
	include(RPBCHESSBOARD_ABSPATH.'templates/admin/header.php');
	include(RPBCHESSBOARD_ABSPATH.'templates/admin/memo.php');
	echo '</div>';
}
