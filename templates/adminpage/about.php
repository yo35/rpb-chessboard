<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a Wordpress plugin.                *
 *    Copyright (C) 2013-2014  Yoann Le Montagner <yo35 -at- melix.net>       *
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

	<h3>RPB Chessboard</h3>
	<p>
		<?php _e(
			'The RPB Chessboard plugin allows you to typeset and display chess games and diagrams '.
			'in the posts and pages of your Wordpress blog, '.
			'using the standard FEN and PGN notations.',
			'rpbchessboard');
		?>
	</p>
	<p>
		<a href="http://wordpress.org/plugins/rpb-chessboard/" target="_blank">http://wordpress.org/plugins/rpb-chessboard/</a>
		<br/>
		<a href="https://github.com/yo35/rpb-chessboard" target="_blank">https://github.com/yo35/rpb-chessboard</a>
		<?php echo '('.__('developer link', 'rpbchessboard').')'; ?>
	</p>
	<p class="description">
		<?php echo sprintf(
			__(
				'If you encounter some bugs with this plugin, or if you wish to get new features in the future versions, '.
				'you can report/propose them in the bug tracker at %1$s.',
			'rpbchessboard'),
			'<a href="https://github.com/yo35/rpb-chessboard/issues" target="_blank">https://github.com/yo35/rpb-chessboard/issues</a>');
		?>
	</p>


	<h3><?php _e('Plugin version', 'rpbchessboard'); ?></h3>
	<p><?php echo htmlspecialchars($model->getPluginVersion()); ?></p>


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
			'<a href="http://www.gnu.org/licenses/gpl-3.0.html" target="_blank">http://www.gnu.org/licenses/gpl-3.0.html</a>');
		?>
	</p>

</div>
