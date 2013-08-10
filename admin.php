<?php

function rpbchessboard_admin_menu()
{
	//echo '<p>TODO</p>';
	//ob_start();
	echo '<div class="wrap">';
	echo '<h2>'.__('Chess games and diagrams', 'rpbchessboard').'</h2>';
	include(RPBCHESSBOARD_ABSPATH.'templates/admin/memo.php');
	echo '</div>';
	//return ob_get_clean();
}
