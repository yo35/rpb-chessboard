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

<h3><?php _e('Diagram alignment', 'rpb-chessboard'); ?></h3>


<div class="rpbchessboard-graphicRadioButtonFields">

	<div>
		<input type="radio" id="rpbchessboard-diagramAlignmentButton-center" name="diagramAlignment" value="center"
			<?php if($model->getDefaultDiagramAlignment()==='center'): ?>checked="yes"<?php endif; ?>
		/>
		<label for="rpbchessboard-diagramAlignmentButton-center" title="<?php _e('Centered', 'rpb-chessboard'); ?>">
			<img src="<?php echo RPBCHESSBOARD_URL . 'images/alignment-center.png'; ?>"
				alt="<?php _e('Centered', 'rpb-chessboard'); ?>"
			/>
		</label>
	</div>

	<div>
		<input type="radio" id="rpbchessboard-diagramAlignmentButton-floatLeft" name="diagramAlignment" value="floatLeft"
			<?php if($model->getDefaultDiagramAlignment()==='floatLeft'): ?>checked="yes"<?php endif; ?>
		/>
		<label for="rpbchessboard-diagramAlignmentButton-floatLeft" title="<?php _e('On the left of the text', 'rpb-chessboard'); ?>">
			<img src="<?php echo RPBCHESSBOARD_URL . 'images/alignment-float-left.png'; ?>"
				alt="<?php _e('On the left of the text', 'rpb-chessboard'); ?>"
			/>
		</label>
	</div>

	<div>
		<input type="radio" id="rpbchessboard-diagramAlignmentButton-floatRight" name="diagramAlignment" value="floatRight"
			<?php if($model->getDefaultDiagramAlignment()==='floatRight'): ?>checked="yes"<?php endif; ?>
		/>
		<label for="rpbchessboard-diagramAlignmentButton-floatRight" title="<?php _e('On the right of the text', 'rpb-chessboard'); ?>">
			<img src="<?php echo RPBCHESSBOARD_URL . 'images/alignment-float-right.png'; ?>"
				alt="<?php _e('On the right of the text', 'rpb-chessboard'); ?>"
			/>
		</label>
	</div>

</div>


<p class="description">
	<?php _e('This setting affects how diagrams are inserted and placed within the rest of the text (by default).', 'rpb-chessboard'); ?>
</p>
