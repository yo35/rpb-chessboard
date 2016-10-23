<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2016  Yoann Le Montagner <yo35 -at- melix.net>       *
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
		<label for="rpbchessboard-navigationBoardButton-none" title="<?php _e('No navigation board', 'rpbchessboard'); ?>">
			<img src="<?php echo RPBCHESSBOARD_URL . 'images/navigation-board-none.png'; ?>"
				alt="<?php _e('No navigation board', 'rpbchessboard'); ?>"
			/>
		</label>
	</div>

	<div>
		<input type="radio" id="rpbchessboard-navigationBoardButton-frame" name="navigationBoard" value="frame"
			<?php if($model->getDefaultNavigationBoard()==='frame'): ?>checked="yes"<?php endif; ?>
		/>
		<label for="rpbchessboard-navigationBoardButton-frame" title="<?php _e('In a popup frame', 'rpbchessboard'); ?>">
			<img src="<?php echo RPBCHESSBOARD_URL . 'images/navigation-board-frame.png'; ?>"
				alt="<?php _e('In a popup frame', 'rpbchessboard'); ?>"
			/>
		</label>
	</div>

	<div>
		<input type="radio" id="rpbchessboard-navigationBoardButton-above" name="navigationBoard" value="above"
			<?php if($model->getDefaultNavigationBoard()==='above'): ?>checked="yes"<?php endif; ?>
		/>
		<label for="rpbchessboard-navigationBoardButton-above" title="<?php _e('Above the game headers and the move list', 'rpbchessboard'); ?>">
			<img src="<?php echo RPBCHESSBOARD_URL . 'images/navigation-board-above.png'; ?>"
				alt="<?php _e('Above the game headers and the move list', 'rpbchessboard'); ?>"
			/>
		</label>
	</div>

	<div>
		<input type="radio" id="rpbchessboard-navigationBoardButton-below" name="navigationBoard" value="below"
			<?php if($model->getDefaultNavigationBoard()==='below'): ?>checked="yes"<?php endif; ?>
		/>
		<label for="rpbchessboard-navigationBoardButton-below" title="<?php _e('Below the move list', 'rpbchessboard'); ?>">
			<img src="<?php echo RPBCHESSBOARD_URL . 'images/navigation-board-below.png'; ?>"
				alt="<?php _e('Below the move list', 'rpbchessboard'); ?>"
			/>
		</label>
	</div>

	<div>
		<input type="radio" id="rpbchessboard-navigationBoardButton-floatLeft" name="navigationBoard" value="floatLeft"
			<?php if($model->getDefaultNavigationBoard()==='floatLeft'): ?>checked="yes"<?php endif; ?>
		/>
		<label for="rpbchessboard-navigationBoardButton-floatLeft" title="<?php _e('On the left of the move list', 'rpbchessboard'); ?>">
			<img src="<?php echo RPBCHESSBOARD_URL . 'images/navigation-board-float-left.png'; ?>"
				alt="<?php _e('On the left of the move list', 'rpbchessboard'); ?>"
			/>
		</label>
	</div>

	<div>
		<input type="radio" id="rpbchessboard-navigationBoardButton-floatRight" name="navigationBoard" value="floatRight"
			<?php if($model->getDefaultNavigationBoard()==='floatRight'): ?>checked="yes"<?php endif; ?>
		/>
		<label for="rpbchessboard-navigationBoardButton-floatRight" title="<?php _e('On the right of the move list', 'rpbchessboard'); ?>">
			<img src="<?php echo RPBCHESSBOARD_URL . 'images/navigation-board-float-right.png'; ?>"
				alt="<?php _e('On the right of the move list', 'rpbchessboard'); ?>"
			/>
		</label>
	</div>

	<div>
		<input type="radio" id="rpbchessboard-navigationBoardButton-scrollLeft" name="navigationBoard" value="scrollLeft"
			<?php if($model->getDefaultNavigationBoard()==='scrollLeft'): ?>checked="yes"<?php endif; ?>
		/>
		<label for="rpbchessboard-navigationBoardButton-scrollLeft" title="<?php _e('On the left, with scrollable move list', 'rpbchessboard'); ?>">
			<img src="<?php echo RPBCHESSBOARD_URL . 'images/navigation-board-scroll-left.png'; ?>"
				alt="<?php _e('On the left, with scrollable move list', 'rpbchessboard'); ?>"
			/>
		</label>
	</div>

	<div>
		<input type="radio" id="rpbchessboard-navigationBoardButton-scrollRight" name="navigationBoard" value="scrollRight"
			<?php if($model->getDefaultNavigationBoard()==='scrollRight'): ?>checked="yes"<?php endif; ?>
		/>
		<label for="rpbchessboard-navigationBoardButton-scrollRight" title="<?php _e('On the right, with scrollable move list', 'rpbchessboard'); ?>">
			<img src="<?php echo RPBCHESSBOARD_URL . 'images/navigation-board-scroll-right.png'; ?>"
				alt="<?php _e('On the right, with scrollable move list', 'rpbchessboard'); ?>"
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
