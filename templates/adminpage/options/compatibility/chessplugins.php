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

<h3><?php esc_html_e( 'Compatibility with other chess plugins', 'rpb-chessboard' ); ?></h3>

<p>
	<?php
		printf(
			esc_html__(
				'By default, the RPB Chessboard plugin use the %1$s[fen][/fen]%2$s and %1$s[pgn][/pgn]%2$s tags ' .
				'for FEN diagrams and PGN games. However, this behavior causes conflicts with other WordPress plugins ' .
				'(typically chess plugins) that use the same tags. These compatibility settings are provided to avoid those conflicts.',
				'rpb-chessboard'
			),
			'<span class="rpbchessboard-sourceCode">',
			'</span>'
		);
	?>
</p>



<p>
	<input type="hidden" name="fenCompatibilityMode" value="0" />
	<input type="checkbox" id="rpbchessboard-fenCompatibilityModeField" name="fenCompatibilityMode" value="1"
		<?php echo $model->getFENCompatibilityMode() ? 'checked="yes"' : ''; ?>
	/>
	<label for="rpbchessboard-fenCompatibilityModeField">
		<?php esc_html_e( 'Compatibility mode for the FEN diagram tag', 'rpb-chessboard' ); ?>
	</label>
</p>

<p class="description">
	<?php
		printf(
			esc_html__(
				'Activating this option makes RPB Chessboard use %1$s[fen_compat][/fen_compat]%2$s ' .
				'instead of %1$s[fen][/fen]%2$s for FEN diagrams.',
				'rpb-chessboard'
			),
			'<span class="rpbchessboard-sourceCode">',
			'</span>'
		);
	?>
</p>



<p>
	<input type="hidden" name="pgnCompatibilityMode" value="0" />
	<input type="checkbox" id="rpbchessboard-pgnCompatibilityModeField" name="pgnCompatibilityMode" value="1"
		<?php echo $model->getPGNCompatibilityMode() ? 'checked="yes"' : ''; ?>
	/>
	<label for="rpbchessboard-pgnCompatibilityModeField">
		<?php esc_html_e( 'Compatibility mode for the PGN game tag', 'rpb-chessboard' ); ?>
	</label>
</p>

<p class="description">
	<?php
		printf(
			esc_html__(
				'Activating this option makes RPB Chessboard use %1$s[pgn_compat][/pgn_compat]%2$s ' .
				'instead of %1$s[pgn][/pgn]%2$s for PGN games.',
				'rpb-chessboard'
			),
			'<span class="rpbchessboard-sourceCode">',
			'</span>'
		);
	?>
</p>
