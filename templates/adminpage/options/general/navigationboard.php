<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2018  Yoann Le Montagner <yo35 -at- melix.net>       *
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

<h3><?php _e( 'Position of the navigation board', 'rpb-chessboard' ); ?></h3>


<div class="rpbchessboard-graphicRadioButtonFields">

	<div>
		<input type="radio" id="rpbchessboard-navigationBoardButton-none" name="navigationBoard" value="none"
			<?php echo $model->getDefaultNavigationBoard() === 'none' ? 'checked="yes"' : ''; ?>
		/>
		<label for="rpbchessboard-navigationBoardButton-none" title="<?php _e( 'No navigation board', 'rpb-chessboard' ); ?>">
			<img src="<?php echo RPBCHESSBOARD_URL . 'images/alignment-none.png'; ?>"
				alt="<?php _e( 'No navigation board', 'rpb-chessboard' ); ?>"
			/>
		</label>
	</div>

	<div>
		<input type="radio" id="rpbchessboard-navigationBoardButton-frame" name="navigationBoard" value="frame"
			<?php echo $model->getDefaultNavigationBoard() === 'frame' ? 'checked="yes"' : ''; ?>
		/>
		<label for="rpbchessboard-navigationBoardButton-frame" title="<?php _e( 'In a popup frame', 'rpb-chessboard' ); ?>">
			<img src="<?php echo RPBCHESSBOARD_URL . 'images/alignment-popup.png'; ?>"
				alt="<?php _e( 'In a popup frame', 'rpb-chessboard' ); ?>"
			/>
		</label>
	</div>

	<div>
		<input type="radio" id="rpbchessboard-navigationBoardButton-above" name="navigationBoard" value="above"
			<?php echo $model->getDefaultNavigationBoard() === 'above' ? 'checked="yes"' : ''; ?>
		/>
		<label for="rpbchessboard-navigationBoardButton-above" title="<?php _e( 'Above the game headers and the move list', 'rpb-chessboard' ); ?>">
			<img src="<?php echo RPBCHESSBOARD_URL . 'images/alignment-above.png'; ?>"
				alt="<?php _e( 'Above the game headers and the move list', 'rpb-chessboard' ); ?>"
			/>
		</label>
	</div>

	<div>
		<input type="radio" id="rpbchessboard-navigationBoardButton-below" name="navigationBoard" value="below"
			<?php echo $model->getDefaultNavigationBoard() === 'below' ? 'checked="yes"' : ''; ?>
		/>
		<label for="rpbchessboard-navigationBoardButton-below" title="<?php _e( 'Below the move list', 'rpb-chessboard' ); ?>">
			<img src="<?php echo RPBCHESSBOARD_URL . 'images/alignment-below.png'; ?>"
				alt="<?php _e( 'Below the move list', 'rpb-chessboard' ); ?>"
			/>
		</label>
	</div>

	<div>
		<input type="radio" id="rpbchessboard-navigationBoardButton-floatLeft" name="navigationBoard" value="floatLeft"
			<?php echo $model->getDefaultNavigationBoard() === 'floatLeft' ? 'checked="yes"' : ''; ?>
		/>
		<label for="rpbchessboard-navigationBoardButton-floatLeft" title="<?php _e( 'On the left of the move list', 'rpb-chessboard' ); ?>">
			<img src="<?php echo RPBCHESSBOARD_URL . 'images/alignment-float-left.png'; ?>"
				alt="<?php _e( 'On the left of the move list', 'rpb-chessboard' ); ?>"
			/>
		</label>
	</div>

	<div>
		<input type="radio" id="rpbchessboard-navigationBoardButton-floatRight" name="navigationBoard" value="floatRight"
			<?php echo $model->getDefaultNavigationBoard() === 'floatRight' ? 'checked="yes"' : ''; ?>
		/>
		<label for="rpbchessboard-navigationBoardButton-floatRight" title="<?php _e( 'On the right of the move list', 'rpb-chessboard' ); ?>">
			<img src="<?php echo RPBCHESSBOARD_URL . 'images/alignment-float-right.png'; ?>"
				alt="<?php _e( 'On the right of the move list', 'rpb-chessboard' ); ?>"
			/>
		</label>
	</div>

	<div>
		<input type="radio" id="rpbchessboard-navigationBoardButton-scrollLeft" name="navigationBoard" value="scrollLeft"
			<?php echo $model->getDefaultNavigationBoard() === 'scrollLeft' ? 'checked="yes"' : ''; ?>
		/>
		<label for="rpbchessboard-navigationBoardButton-scrollLeft" title="<?php _e( 'On the left, with scrollable move list', 'rpb-chessboard' ); ?>">
			<img src="<?php echo RPBCHESSBOARD_URL . 'images/alignment-scroll-left.png'; ?>"
				alt="<?php _e( 'On the left, with scrollable move list', 'rpb-chessboard' ); ?>"
			/>
		</label>
	</div>

	<div>
		<input type="radio" id="rpbchessboard-navigationBoardButton-scrollRight" name="navigationBoard" value="scrollRight"
			<?php echo $model->getDefaultNavigationBoard() === 'scrollRight' ? 'checked="yes"' : ''; ?>
		/>
		<label for="rpbchessboard-navigationBoardButton-scrollRight" title="<?php _e( 'On the right, with scrollable move list', 'rpb-chessboard' ); ?>">
			<img src="<?php echo RPBCHESSBOARD_URL . 'images/alignment-scroll-right.png'; ?>"
				alt="<?php _e( 'On the right, with scrollable move list', 'rpb-chessboard' ); ?>"
			/>
		</label>
	</div>

</div>


<p class="description">
	<?php
		_e(
			'A navigation board may be added to each PGN game to help post/page readers to follow the progress of the game. ' .
			'This navigation board is displayed either in a popup frame (in this case, it becomes visible only when the reader ' .
			'clicks on a move) or next to the move list (then it is visible as soon as the page is loaded).',
			'rpb-chessboard'
		);
	?>
</p>
