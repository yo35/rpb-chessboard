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

<script type="text/javascript">

	jQuery(document).ready(function($) {

		// Localization for the uichess-chessgame widget.
		$.chessgame.i18n.ANNOTATED_BY     = <?php echo json_encode(__('Annotated by %1$s', 'rpbchessboard')); ?>;
		$.chessgame.i18n.INITIAL_POSITION = <?php echo json_encode(__('Initial position' , 'rpbchessboard')); ?>;

		// Month names.
		$.chessgame.i18n.MONTHS = [
			<?php echo json_encode(__('January'  , 'rpbchessboard')); ?>,
			<?php echo json_encode(__('February' , 'rpbchessboard')); ?>,
			<?php echo json_encode(__('March'    , 'rpbchessboard')); ?>,
			<?php echo json_encode(__('April'    , 'rpbchessboard')); ?>,
			<?php echo json_encode(__('May'      , 'rpbchessboard')); ?>,
			<?php echo json_encode(__('June'     , 'rpbchessboard')); ?>,
			<?php echo json_encode(__('July'     , 'rpbchessboard')); ?>,
			<?php echo json_encode(__('August'   , 'rpbchessboard')); ?>,
			<?php echo json_encode(__('September', 'rpbchessboard')); ?>,
			<?php echo json_encode(__('October'  , 'rpbchessboard')); ?>,
			<?php echo json_encode(__('November' , 'rpbchessboard')); ?>,
			<?php echo json_encode(__('December' , 'rpbchessboard')); ?>
		];

		// Chess piece symbols.
		$.chessgame.i18n.PIECE_SYMBOLS = {
			'K': <?php /*i18n King symbol   */ echo json_encode(__('K', 'rpbchessboard')); ?>,
			'Q': <?php /*i18n Queen symbol  */ echo json_encode(__('Q', 'rpbchessboard')); ?>,
			'R': <?php /*i18n Rook symbol   */ echo json_encode(__('R', 'rpbchessboard')); ?>,
			'B': <?php /*i18n Bishop symbol */ echo json_encode(__('B', 'rpbchessboard')); ?>,
			'N': <?php /*i18n Knight symbol */ echo json_encode(__('N', 'rpbchessboard')); ?>,
			'P': <?php /*i18n Pawn symbol   */ echo json_encode(__('P', 'rpbchessboard')); ?>
		};

	});

</script>
