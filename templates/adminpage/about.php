<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2019  Yoann Le Montagner <yo35 -at- melix.net>       *
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

<div id="rpbchessboard-aboutPage">

	<div class="rpbchessboard-floatLeft">
		<img src="<?php echo esc_url( RPBCHESSBOARD_URL . 'images/rpb-chessboard-icon.png' ); ?>" alt="RPB Chessboard icon" />
	</div>

	<p>
		<?php
			printf(
				esc_html__(
					'RPB Chessboard allows you to typeset and display chess games and diagrams ' .
					'in the posts and pages of your WordPress blog, ' .
					'using the standard %1$sFEN%3$s and %2$sPGN%3$s notations.',
					'rpb-chessboard'
				),
				sprintf( '<a href="%s" target="_blank">', esc_url( __( 'http://en.wikipedia.org/wiki/Forsyth-Edwards_Notation', 'rpb-chessboard' ) ) ),
				sprintf( '<a href="%s" target="_blank">', esc_url( __( 'http://en.wikipedia.org/wiki/Portable_Game_Notation', 'rpb-chessboard' ) ) ),
				'</a>'
			);
		?>
	</p>
	<p>
		<strong><a href="https://wordpress.org/plugins/rpb-chessboard/" target="_blank">https://wordpress.org/plugins/rpb-chessboard/</a></strong><br/>
		<a href="https://github.com/yo35/rpb-chessboard" target="_blank">https://github.com/yo35/rpb-chessboard</a>
		(<?php esc_html_e( 'source code on GitHub', 'rpb-chessboard' ); ?>)
	</p>
	<div class="description rpbchessboard-clearfix">
		<a class="button" href="https://github.com/yo35/rpb-chessboard/issues" target="_blank" title="<?php
			esc_attr_e(
				'If you need help to use this plugin, if you encounter some bugs, or if you wish to get new features in the future versions, ' .
				'please feel free to use the GitHub tracker.', 'rpb-chessboard'
			);
		?>
		">
			<img src="<?php echo esc_url( RPBCHESSBOARD_URL . 'images/help.png' ); ?>" />
			<?php esc_html_e( 'Need help', 'rpb-chessboard' ); ?> / <?php esc_html_e( 'Report a bug', 'rpb-chessboard' ); ?>
		</a>
		<a class="button" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=YHNERW43QN49E" target="_blank" title="<?php
			esc_attr_e(
				'This plugin is and will remain free. However, if you want to make a donation to support the author\'s work, ' .
				'you can do it through this PayPal link.', 'rpb-chessboard'
			);
		?>
		">
			<img src="<?php echo esc_url( RPBCHESSBOARD_URL . 'images/paypal.png' ); ?>" /><?php esc_html_e( 'Donate', 'rpb-chessboard' ); ?>
		</a>
	</div>


	<h3><?php esc_html_e( 'Plugin version', 'rpb-chessboard' ); ?></h3>
	<p><?php echo esc_html( $model->getPluginVersion() ); ?></p>


	<h3><?php esc_html_e( 'Credits', 'rpb-chessboard' ); ?></h3>

	<h4><?php esc_html_e( 'Author', 'rpb-chessboard' ); ?></h4>
	<p><a href="mailto:yo35@melix.net">Yoann Le Montagner</a></p>

	<h4><?php esc_html_e( 'Contributors', 'rpb-chessboard' ); ?></h4>
	<p>
		Marek Śmigielski,
		<a href="https://paulschreiber.com/" target="_blank">Paul Schreiber</a>,
		<a href="http://www.10up.com/" target="_blank">Adam Silverstein</a>
	</p>

	<h4><?php esc_html_e( 'Translators', 'rpb-chessboard' ); ?></h4>
	<dl class="rpbchessboard-translatorList">
		<div>
			<div>
				<dt><img src="<?php echo esc_url( RPBCHESSBOARD_URL . 'images/flags/de.png' ); ?>" alt="German flag" />Deutsch</dt>
				<dd>Markus Liebelt</dd>
			</div>
			<div>
				<dt><img src="<?php echo esc_url( RPBCHESSBOARD_URL . 'images/flags/fr.png' ); ?>" alt="French flag" />Français</dt>
				<dd>Yoann Le Montagner</dd>
			</div>
			<div>
				<dt><img src="<?php echo esc_url( RPBCHESSBOARD_URL . 'images/flags/gb.png' ); ?>" alt="British flag" />English</dt>
				<dd>Yoann Le Montagner</dd>
			</div>
		</div>
		<div>
			<div>
				<dt><img src="<?php echo esc_url( RPBCHESSBOARD_URL . 'images/flags/nl.png' ); ?>" alt="Dutch flag" />Dutch</dt>
				<dd>Ivan Deceuninck</dd>
			</div>
			<div>
				<dt><img src="<?php echo esc_url( RPBCHESSBOARD_URL . 'images/flags/pl.png' ); ?>" alt="Polish flag" />Polski</dt>
				<dd><a href="http://dawidziolkowski.com/" target="_blank">Dawid Ziółkowski</a></dd>
			</div>
			<div>
				<dt><img src="<?php echo esc_url( RPBCHESSBOARD_URL . 'images/flags/ru.png' ); ?>" alt="Russian flag" />Russian</dt>
				<dd><a href="http://safoyeth.com/" target="_blank">Sergey Baravicov</a></dd>
			</div>
		</div>
		<div>
			<div>
				<dt><img src="<?php echo esc_url( RPBCHESSBOARD_URL . 'images/flags/tr.png' ); ?>" alt="Turkish flag" />Turkish</dt>
				<dd>Ali Nihat Yazıcı</dd>
			</div>
		</div>
	</dl>
	<p class="description">
		<?php
			printf(
				esc_html__(
					'If you are interested in translating this plugin into your language, please %1$scontact the author%2$s.',
					'rpb-chessboard'
				),
				'<a href="mailto:yo35@melix.net">',
				'</a>'
			);
		?>
	</p>

	<h4><?php esc_html_e( 'Graphic resources', 'rpb-chessboard' ); ?></h4>
	<p>
		<?php
			printf(
				esc_html__(
					'Pieceset %1$sCBurnett%2$s has been created by %3$sColin M.L. Burnett%6$s, ' .
					'who shares it under the [CC-BY-SA] license on %4$sWikimedia Commons%6$s; ' .
					'user %5$sAntonsusi%6$s has also contributed to this work.',
					'rpb-chessboard'
				),
				'<em>',
				'</em>',
				'<a href="https://en.wikipedia.org/wiki/User:Cburnett" target="_blank">',
				'<a href="https://commons.wikimedia.org/wiki/Category:SVG_chess_pieces" target="_blank">',
				'<a href="https://commons.wikimedia.org/wiki/User:Antonsusi" target="_blank">',
				'</a>'
			);
		?>
		<?php
			printf(
				esc_html__(
					'Piecesets %1$sCeltic%2$s, %1$sEyes%2$s, %1$sFantasy%2$s, %1$sSkulls%2$s and %1$sSpatial%2$s ' .
					'have been created by %3$sMaurizio Monge%4$s, who makes them freely available for chess programs.',
					'rpb-chessboard'
				),
				'<em>',
				'</em>',
				'<a href="http://poisson.phc.unipi.it/~monge/" target="_blank">',
				'</a>'
			);
		?>
		<?php
			printf(
				esc_html__(
					'Colorsets %1$sCoral%2$s, %1$sDusk%2$s, %1$sEmerald%2$s, %1$sMarine%2$s, %1$sSandcastle%2$s and %1$sWheat%2$s ' .
					'have been proposed in this %3$sblog post%5$s by %4$sGorgonian%5$s.',
					'rpb-chessboard'
				),
				'<em>',
				'</em>',
				'<a href="http://omgchess.blogspot.fr/2015/09/chess-board-color-schemes.html" target="_blank">',
				'<a href="http://omgchess.blogspot.fr/" target="_blank">',
				'</a>'
			);
		?>
		<?php
			printf(
				esc_html__(
					'Icons %1$sUndo%2$s, %1$sRedo%2$s, %1$sDelete%2$s and %1$sTick%2$s have been created by %3$sMomentum Design Lab%5$s, ' .
					'who shares them under the [CC-BY] license on %4$sFind Icons%5$s.',
					'rpb-chessboard'
				),
				'<em>',
				'</em>',
				'<a href="http://momentumdesignlab.com/" target="_blank">',
				'<a href="http://findicons.com/pack/2226/matte_basic" target="_blank">',
				'</a>'
			);
		?>
		<?php
			printf(
				esc_html__(
					'Icon %1$sNot-Found%2$s has been created by %3$sgakuseiSean%5$s, ' .
					'who makes it freely available for non-commercial use on %4$sFind Icons%5$s.',
					'rpb-chessboard'
				),
				'<em>',
				'</em>',
				'<a href="http://gakuseisean.deviantart.com/" target="_blank">',
				'<a href="http://findicons.com/icon/89623/error" target="_blank">',
				'</a>'
			);
		?>
		<?php
			printf(
				esc_html__(
					'Icon %1$sHelp%2$s has been created by %3$sRuby Software%5$s, who shares it as a freeware on %4$sFind Icons%5$s.',
					'rpb-chessboard'
				),
				'<em>',
				'</em>',
				'<a href="http://www.rubysoftware.nl/" target="_blank">',
				'<a href="http://findicons.com/icon/26233/help" target="_blank">',
				'</a>'
			);
		?>
	</p>
	<p>
		<?php esc_html_e( 'The author would like to thank all these people for their highly valuable work.', 'rpb-chessboard' ); ?>
	</p>


	<h3><?php esc_html_e( 'License', 'rpb-chessboard' ); ?></h3>
	<p>
		<?php
			printf(
				esc_html__(
					'This plugin is distributed under the terms of the %1$sGNU General Public License version 3%3$s (GPLv3), ' .
					'as published by the %2$sFree Software Foundation%3$s. The full text of this license ' .
					'is available at %4$s. A copy of this document is also provided with the plugin source code.',
					'rpb-chessboard'
				),
				'<a href="http://www.gnu.org/licenses/gpl.html" target="_blank">',
				'<a href="http://www.fsf.org/" target="_blank">',
				'</a>',
				'<a href="http://www.gnu.org/licenses/gpl.html" target="_blank">http://www.gnu.org/licenses/gpl.html</a>'
			);
		?>
	</p>
	<p>
		<?php
			esc_html_e(
				'This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; ' .
				'without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. ' .
				'See the GNU General Public License for more details.',
				'rpb-chessboard'
			);
		?>
	</p>

</div>
