<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2015  Yoann Le Montagner <yo35 -at- melix.net>       *
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

<div id="rpbchessboard-optionPage" class="rpbchessboard-jQuery-enableSmoothness">

	<form action="<?php echo htmlspecialchars($model->getFormActionURL()); ?>" method="post">

		<input type="hidden" name="rpbchessboard_action" value="<?php echo htmlspecialchars($model->getFormAction()); ?>" />



		<p>
			<?php echo sprintf(
				__(
					'By default, the RPB Chessboard plugin use the %1$s[fen][/fen]%2$s and %1$s[pgn][/pgn]%2$s tags '.
					'for FEN diagrams and PGN games. However, this behavior causes conflicts with other WordPress plugins '.
					'(typically chess plugins) that use the same tags. The compatibility modes are provided to avoid those conflicts.',
				'rpbchessboard'),
				'<span class="rpbchessboard-sourceCode">',
				'</span>'
			); ?>
		</p>



		<p>
			<input type="hidden" name="fenCompatibilityMode" value="0" />
			<input type="checkbox" id="rpbchessboard-fenCompatibilityModeField" name="fenCompatibilityMode" value="1"
				<?php if($model->getFENCompatibilityMode()): ?>checked="yes"<?php endif; ?>
			/>
			<label for="rpbchessboard-fenCompatibilityModeField">
				<?php _e('Compatibility mode for the FEN diagram tag', 'rpbchessboard'); ?>
			</label>
		</p>

		<p class="description">
			<?php echo sprintf(
				__(
					'Activating this option makes RPB Chessboard use %1$s[fen_compat][/fen_compat]%2$s ' .
					'instead of %1$s[fen][/fen]%2$s for FEN diagrams.',
				'rpbchessboard'),
				'<span class="rpbchessboard-sourceCode">',
				'</span>'
			); ?>
		</p>

		<p>
			<input type="hidden" name="pgnCompatibilityMode" value="0" />
			<input type="checkbox" id="rpbchessboard-pgnCompatibilityModeField" name="pgnCompatibilityMode" value="1"
				<?php if($model->getPGNCompatibilityMode()): ?>checked="yes"<?php endif; ?>
			/>
			<label for="rpbchessboard-pgnCompatibilityModeField">
				<?php _e('Compatibility mode for the PGN game tag', 'rpbchessboard'); ?>
			</label>
		</p>

		<p class="description">
			<?php echo sprintf(
				__(
					'Activating this option makes RPB Chessboard use %1$s[pgn_compat][/pgn_compat]%2$s ' .
					'instead of %1$s[pgn][/pgn]%2$s for PGN games.',
				'rpbchessboard'),
				'<span class="rpbchessboard-sourceCode">',
				'</span>'
			); ?>
		</p>



		<?php include(RPBCHESSBOARD_ABSPATH . 'templates/adminpage/optionsfooter.php'); ?>

	</form>

</div>
