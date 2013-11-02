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
?>

<div id="rpbchessboard-admin-help">

	<ul class="subsubsub">

		<li id="rpbchessboard-admin-help-fen-button">
			<a href="javascript: showTab(jQuery,'rpbchessboard-admin-help-fen');"><?php _e('FEN diagram', 'rpbchessboard'); ?></a>
		</li>

		<li id="rpbchessboard-admin-help-pgn-button">
			<a href="javascript: showTab(jQuery,'rpbchessboard-admin-help-pgn');"><?php _e('PGN game', 'rpbchessboard'); ?></a>
		</li>

	</ul>




	<div id="rpbchessboard-admin-help-fen">
		TODO: help FEN
	</div>





	<div id="rpbchessboard-admin-help-pgn">
		TODO: help PGN
	</div>





	<script type="text/javascript">

		// List of tabs
		var tabs = [
			'rpbchessboard-admin-help-fen',
			'rpbchessboard-admin-help-pgn'
		];

		// Function to display a tab
		function showTab($, tabID)
		{
			for(var k=0; k<tabs.length; ++k) {
				if(tabs[k]==tabID) {
					$('#' + tabID).removeClass('rpbchessboard-invisible');
					$('#' + tabID + '-button > a').addClass('current');
				}
				else {
					$('#' + tabs[k]).addClass('rpbchessboard-invisible');
					$('#' + tabs[k] + '-button > a').removeClass('current');
				}
			}
		}

		// Initialization
		jQuery(document).ready(function($)
		{
			showTab($, 'rpbchessboard-admin-help-fen');
		});

	</script>

</div>
