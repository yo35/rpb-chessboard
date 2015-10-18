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

<h3><?php _e('Position of the navigation board', 'rpbchessboard'); ?></h3>


<div id="rpbchessboard-navigationBoardFields">

	<div>
		<input type="radio" id="rpbchessboard-navigationBoardButton-none" name="navigationBoard" value="none"
			<?php if($model->getDefaultNavigationBoard()==='none'): ?>checked="yes"<?php endif; ?>
		/>
		<label for="rpbchessboard-navigationBoardButton-none">
			<img src="<?php echo RPBCHESSBOARD_URL . 'images/navigation-board-none.png'; ?>"
				title="<?php _e('No navigation board', 'rpbchessboard'); ?>"
				alt="<?php _e('No navigation board', 'rpbchessboard'); ?>"
			/>
		</label>
	</div>

	<div>
		<input type="radio" id="rpbchessboard-navigationBoardButton-frame" name="navigationBoard" value="frame"
			<?php if($model->getDefaultNavigationBoard()==='frame'): ?>checked="yes"<?php endif; ?>
		/>
		<label for="rpbchessboard-navigationBoardButton-frame">
			<img src="<?php echo RPBCHESSBOARD_URL . 'images/navigation-board-frame.png'; ?>"
				title="<?php _e('In a popup frame', 'rpbchessboard'); ?>"
				alt="<?php _e('In a popup frame', 'rpbchessboard'); ?>"
			/>
		</label>
	</div>

	<div>
		<input type="radio" id="rpbchessboard-navigationBoardButton-above" name="navigationBoard" value="above"
			<?php if($model->getDefaultNavigationBoard()==='above'): ?>checked="yes"<?php endif; ?>
		/>
		<label for="rpbchessboard-navigationBoardButton-above">
			<img src="<?php echo RPBCHESSBOARD_URL . 'images/navigation-board-above.png'; ?>"
				title="<?php _e('Above the game headers and the move list', 'rpbchessboard'); ?>"
				alt="<?php _e('Above', 'rpbchessboard'); ?>"
			/>
		</label>
	</div>

	<div>
		<input type="radio" id="rpbchessboard-navigationBoardButton-below" name="navigationBoard" value="below"
			<?php if($model->getDefaultNavigationBoard()==='below'): ?>checked="yes"<?php endif; ?>
		/>
		<label for="rpbchessboard-navigationBoardButton-below">
			<img src="<?php echo RPBCHESSBOARD_URL . 'images/navigation-board-below.png'; ?>"
				title="<?php _e('Below the move list', 'rpbchessboard'); ?>"
				alt="<?php _e('Below', 'rpbchessboard'); ?>"
			/>
		</label>
	</div>

	<div>
		<input type="radio" id="rpbchessboard-navigationBoardButton-floatLeft" name="navigationBoard" value="floatLeft"
			<?php if($model->getDefaultNavigationBoard()==='floatLeft'): ?>checked="yes"<?php endif; ?>
		/>
		<label for="rpbchessboard-navigationBoardButton-floatLeft">
			<img src="<?php echo RPBCHESSBOARD_URL . 'images/navigation-board-floatleft.png'; ?>"
				title="<?php _e('On the left of the move list', 'rpbchessboard'); ?>"
				alt="<?php _e('On the left', 'rpbchessboard'); ?>"
			/>
		</label>
	</div>

	<div>
		<input type="radio" id="rpbchessboard-navigationBoardButton-floatRight" name="navigationBoard" value="floatRight"
			<?php if($model->getDefaultNavigationBoard()==='floatRight'): ?>checked="yes"<?php endif; ?>
		/>
		<label for="rpbchessboard-navigationBoardButton-floatRight">
			<img src="<?php echo RPBCHESSBOARD_URL . 'images/navigation-board-floatright.png'; ?>"
				title="<?php _e('On the right of the move list', 'rpbchessboard'); ?>"
				alt="<?php _e('On the right', 'rpbchessboard'); ?>"
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
		'rpbchessboard');
	?>
</p>
