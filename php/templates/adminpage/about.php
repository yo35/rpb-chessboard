<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2022  Yoann Le Montagner <yo35 -at- melix.net>       *
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


<div id="rpbchessboard-aboutActions">
	<a class="button" href="https://github.com/yo35/rpb-chessboard/issues" target="_blank" title="<?php
		esc_attr_e(
			'If you need help to use this plugin, if you encounter some bugs, or if you wish to get new features in the future versions, ' .
			'please feel free to use the GitHub tracker.',
			'rpb-chessboard'
		);
	?>
	">
		<span class="rpbchessboard-aboutActionIcon dashicons dashicons-editor-help"></span><span class="rpbchessboard-aboutActionLabel">
			<?php esc_html_e( 'Need help', 'rpb-chessboard' ); ?> / <?php esc_html_e( 'Report a bug', 'rpb-chessboard' ); ?>
		</span>
	</a>
	<a class="button" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=YHNERW43QN49E" target="_blank" title="<?php
		esc_attr_e(
			'This plugin is and will remain free. However, if you want to make a donation to support the author\'s work, ' .
			'you can do it through this PayPal link.',
			'rpb-chessboard'
		);
	?>
	">
		<img class="rpbchessboard-aboutActionIcon" src="<?php echo esc_url( RPBCHESSBOARD_URL . 'images/paypal.png' ); ?>" /><span class="rpbchessboard-aboutActionLabel">
			<?php esc_html_e( 'Donate', 'rpb-chessboard' ); ?>
		</span>
	</a>
</div>



<h3><?php esc_html_e( 'Plugin version', 'rpb-chessboard' ); ?></h3>

<p><?php echo esc_html( $model->getPluginVersion() ); ?></p>



<h3><?php esc_html_e( 'Author', 'rpb-chessboard' ); ?></h3>

<p><a href="mailto:yo35@melix.net">Yoann Le Montagner</a></p>



<h3><?php esc_html_e( 'Contributors', 'rpb-chessboard' ); ?></h3>

<ul id="rpbchessboard-contributorList">
	<?php foreach ( $model->getPluginContributors() as $contributor ) : ?>
		<li>
			<?php if ( isset( $contributor->link ) ) : ?>
				<a href="<?php echo esc_url( $contributor->link ); ?>" target="_blank"><?php echo esc_html( $contributor->name ); ?></a>
			<?php else : ?>
				<?php echo esc_html( $contributor->name ); ?>
			<?php endif; ?>
		</li>
	<?php endforeach; ?>
</ul>



<h3><?php esc_html_e( 'Translators', 'rpb-chessboard' ); ?></h3>

<ul id="rpbchessboard-translatorList">
	<?php foreach ( $model->getPluginTranslators() as $translator ) : ?>
		<li>
			<img src="<?php echo esc_url( RPBCHESSBOARD_URL . 'images/flags/' . $translator->code . '.png' ); ?>" />
			<span><?php echo esc_html( $translator->lang );?></span>
			<span>
				<?php if ( isset( $translator->link ) ) : ?>
					<a href="<?php echo esc_url( $translator->link ); ?>" target="_blank"><?php echo esc_html( $translator->name ); ?></a>
				<?php else : ?>
					<?php echo esc_html( $translator->name ); ?>
				<?php endif; ?>
			</span>
		</li>
	<?php endforeach; ?>
</ul>

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



<h3><?php esc_html_e( 'Graphic resource credits', 'rpb-chessboard' ); ?></h3>

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
			'<a href="http://poisson.phc.dm.unipi.it/~monge/" target="_blank">',
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
