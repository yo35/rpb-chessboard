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

<div id="rpbchessboard-admin-about">

	<h3><?php _e('Author', 'rpbchessboard'); ?></h3>
	<p>Yoann Le Montagner</p>


	<h3><?php _e('Translation', 'rpbchessboard'); ?></h3>
	<dl id="rpbchessboard-admin-translator-list">
		<div>
			<dt><img src="<?php echo RPBCHESSBOARD_URL.'/images/flags/gb.png'; ?>" />English</dt>
			<dd>Yoann Le Montagner</dd>
		</div>
		<div>
			<dt><img src="<?php echo RPBCHESSBOARD_URL.'/images/flags/fr.png'; ?>" />Français</dt>
			<dd>Yoann Le Montagner</dd>
		</div>
	</dl>
	<p class="description">
		<?php echo sprintf(
			__(
				'If you are interested in translating this plugin into your language, please contact the author %1$s.',
			'rpbchessboard'),
			'Yoann Le Montagner <a href="mailto:yo35@melix.net">yo35@melix.net</a>');
		?>
	</p>


	<h3><?php _e('License', 'rpbchessboard'); ?></h3>
	<p>
		<?php echo sprintf(
			__(
				'This plugin is distributed under the GNU General Public License version 3 (GPLv3). '.
				'The full text of this license is available at %1$s. A copy of this document '.
				'is also provided with the plugin source code.',
			'rpbchessboard'),
			'<a href="http://www.gnu.org/licenses/">http://www.gnu.org/licenses/</a>');
		?>
	</p>

</div>
