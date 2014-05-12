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

	<div class="rpbchessboard-admin-floatleft">
		<img src="<?php echo htmlspecialchars($model->getIconURL()); ?>" alt="RPB Chessboard icon" />
	</div>

	<p>
		<?php echo sprintf(
			__(
				'RPB Chessboard allows you to typeset and display chess games and diagrams '.
				'in the posts and pages of your WordPress blog, '.
				'using the standard %1$sFEN%3$s and %2$sPGN%3$s notations.',
			'rpbchessboard'),
			sprintf('<a href="%1$s" target="_blank">', __('http://en.wikipedia.org/wiki/Forsyth-Edwards_Notation', 'rpbchessboard')),
			sprintf('<a href="%1$s" target="_blank">', __('http://en.wikipedia.org/wiki/Portable_Game_Notation', 'rpbchessboard')),
			'</a>');
		?>
	</p>
	<p>
		<a class="button" href="https://github.com/yo35/rpb-chessboard/issues" target="_blank">
			<?php echo sprintf('%1$s / %2$s', __('Ask for help', 'rpbchessboard'), __('Report a problem', 'rpbchessboard')); ?>
		</a>
	</p>
	<p class="description rpbchessboard-admin-clearfix">
		<?php echo sprintf(
			__(
				'If you encounter some bugs with this plugin, or if you wish to get new features in the future versions, '.
				'you can report/propose them in the %1$sGitHub bug tracker%2$s.',
			'rpbchessboard'),
			'<a href="https://github.com/yo35/rpb-chessboard/issues" target="_blank">',
			'</a>');
		?>
	</p>


	<h3><?php _e('Plugin version', 'rpbchessboard'); ?></h3>
	<p><?php echo htmlspecialchars($model->getPluginVersion()); ?></p>


	<h3><?php _e('Links', 'rpbchessboard'); ?></h3>
	<ul>
		<li><strong><a href="http://yo35.org/rpb-chessboard/" target="_blank">http://yo35.org/rpb-chessboard/</a></strong></li>
		<li>
			<a href="https://wordpress.org/plugins/rpb-chessboard/" target="_blank">https://wordpress.org/plugins/rpb-chessboard/</a>
			<?php echo sprintf('(%1$s)', __('plugin page on WordPress.org', 'rpbchessboard')); ?>
		</li>
		<li>
			<a href="https://github.com/yo35/rpb-chessboard" target="_blank">https://github.com/yo35/rpb-chessboard</a>
			<?php echo sprintf('(%1$s)', __('source code on GitHub', 'rpbchessboard')); ?>
		</li>
	</ul>


	<h3><?php _e('Author', 'rpbchessboard'); ?></h3>
	<p><a href="mailto:yo35@melix.net">Yoann Le Montagner</a></p>


	<h3><?php _e('Translation', 'rpbchessboard'); ?></h3>
	<dl id="rpbchessboard-admin-translator-list">
		<div>
			<dt><img src="<?php echo RPBCHESSBOARD_URL.'/images/flags/de.png'; ?>" />Deutsch</dt>
			<dd>Markus Liebelt</dd>
		</div>
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
				'If you are interested in translating this plugin into your language, please %1$scontact the author%2$s.',
			'rpbchessboard'),
			'<a href="mailto:yo35@melix.net">',
			'</a>');
		?>
	</p>


	<h3><?php _e('License', 'rpbchessboard'); ?></h3>
	<p>
		<?php echo sprintf(
			__(
				'This plugin is distributed under the terms of the %1$sGNU General Public License version 3%3$s (GPLv3), '.
				'as published by the %2$sFree Software Foundation%3$s. The full text of this license '.
				'is available at %4$s. A copy of this document is also provided with the plugin source code.',
			'rpbchessboard'),
			'<a href="http://www.gnu.org/licenses/gpl.html" target="_blank">',
			'<a href="http://www.fsf.org/" target="_blank">',
			'</a>',
			'<a href="http://www.gnu.org/licenses/gpl.html" target="_blank">http://www.gnu.org/licenses/gpl.html</a>');
		?>
	</p>
	<p>
		<?php _e(
			'This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; '.
			'without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. '.
			'See the GNU General Public License for more details.',
			'rpbchessboard');
		?>
	</p>

</div>
