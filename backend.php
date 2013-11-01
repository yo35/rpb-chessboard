<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a Wordpress plugin.                *
 *    Copyright (C) 2013  Yoann Le Montagner <yo35 -at- melix.net>            *
 *                                                                            *
 *    This program is free software: you can redistribute it and/or modify    *
 *    it under the terms of the GNU General Public License as published by    *
 *    the Free Software Foundation, either version 3 of the License, or       *
 *    (at your option) any later version.                                     *
 *                                                                            *
 *    This program is distributed in the hope that it will be useful,         *
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of          *
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the           *
 *    GNU General Public License for more details.                            *
 *                                                                            *
 *    You should have received a copy of the GNU General Public License       *
 *    along with this program.  If not, see <http://www.gnu.org/licenses/>.   *
 *                                                                            *
 ******************************************************************************/


// Register the administration page
add_action('admin_menu', 'rpbchessboard_admin_register_interface');
function rpbchessboard_admin_register_interface()
{
	// Create the menu
	add_menu_page(
		__('Chess games and diagrams', 'rpbchessboard'),
		__('Chess', 'rpbchessboard'),
		'edit_posts', 'rpbchessboard', rpbchessboard_admin_page_memo,
		RPBCHESSBOARD_URL.'/images/admin-small.png'
	);

	// Page "memo" (same slug code that for the menu, to make it the default page).
	add_submenu_page('rpbchessboard',
		__('Chess games and diagrams', 'rpbchessboard') . ' - ' . __('Memo', 'rpbchessboard'),
		__('Memo', 'rpbchessboard'),
		'edit_posts', 'rpbchessboard', rpbchessboard_admin_page_memo
	);

	// Page "options"
	add_submenu_page('rpbchessboard',
		__('Chess games and diagrams', 'rpbchessboard') . ' - ' . __('Options', 'rpbchessboard'),
		__('Options', 'rpbchessboard'),
		'manage_options', 'rpbchessboard-options', rpbchessboard_admin_page_options
	);

	// Page "help"
	add_submenu_page('rpbchessboard',
		__('Chess games and diagrams', 'rpbchessboard') . ' - ' . __('Help', 'rpbchessboard'),
		__('Help', 'rpbchessboard'),
		'edit_posts', 'rpbchessboard-help', rpbchessboard_admin_page_help
	);

	// Page "about"
	add_submenu_page('rpbchessboard',
		__('Chess games and diagrams', 'rpbchessboard') . ' - ' . __('About', 'rpbchessboard'),
		__('About', 'rpbchessboard'),
		'manage_options', 'rpbchessboard-about', rpbchessboard_admin_page_about
	);
}


// Page hooks
function rpbchessboard_admin_page_memo   () { rpbchessboard_load_controller('Memo'   ); }
function rpbchessboard_admin_page_options() { rpbchessboard_load_controller('Options'); }
function rpbchessboard_admin_page_help   () { rpbchessboard_load_controller('Help'   ); }
function rpbchessboard_admin_page_about  () { rpbchessboard_load_controller('About'  ); }


// Load the controller with the corresponding model name, and execute it.
function rpbchessboard_load_controller($modelName)
{
	require_once(RPBCHESSBOARD_ABSPATH.'controllers/admin.php');
	$controller = new RPBChessboardControllerAdmin($modelName);
	$controller->run();
}
