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

<script type="text/javascript">

	(function(rpb, $) {

		$.chessgame.i18n.ANNOTATED_BY     = <?php echo json_encode(__('Annotated by %1$s', 'rpbchessboard')); ?>;
		$.chessgame.i18n.INITIAL_POSITION = <?php echo json_encode(__('Initial position' , 'rpbchessboard')); ?>;

		$.chessgame.i18n.PIECE_SYMBOLS = {
			'K': <?php /*i18n King symbol   */ echo json_encode(__('K', 'rpbchessboard')); ?>,
			'Q': <?php /*i18n Queen symbol  */ echo json_encode(__('Q', 'rpbchessboard')); ?>,
			'R': <?php /*i18n Rook symbol   */ echo json_encode(__('R', 'rpbchessboard')); ?>,
			'B': <?php /*i18n Bishop symbol */ echo json_encode(__('B', 'rpbchessboard')); ?>,
			'N': <?php /*i18n Knight symbol */ echo json_encode(__('N', 'rpbchessboard')); ?>,
			'P': <?php /*i18n Pawn symbol   */ echo json_encode(__('P', 'rpbchessboard')); ?>
		};

		<?php if(is_admin()): ?>

			rpb.i18n.EDITOR_BUTTON_LABEL              = <?php echo json_encode(__('chessboard'                 , 'rpbchessboard')); ?>;
			rpb.i18n.EDIT_CHESS_DIAGRAM_DIALOG_TITLE  = <?php echo json_encode(__('Insert/edit a chess diagram', 'rpbchessboard')); ?>;
			rpb.i18n.CANCEL_BUTTON_LABEL              = <?php echo json_encode(__('Cancel'                     , 'rpbchessboard')); ?>;
			rpb.i18n.SUBMIT_BUTTON_ADD_LABEL          = <?php echo json_encode(__('Add a new chess diagram'    , 'rpbchessboard')); ?>;
			rpb.i18n.SUBMIT_BUTTON_EDIT_LABEL         = <?php echo json_encode(__('Update the chess diagram'   , 'rpbchessboard')); ?>;
			rpb.i18n.BASIC_TAB_LABEL                  = <?php echo json_encode(__('Basic'                      , 'rpbchessboard')); ?>;
			rpb.i18n.TURN_SECTION_TITLE               = <?php echo json_encode(__('Turn'                       , 'rpbchessboard')); ?>;
			rpb.i18n.ORIENTATION_SECTION_TITLE        = <?php echo json_encode(__('Orientation'                , 'rpbchessboard')); ?>;
			rpb.i18n.FLIP_CHECKBOX_LABEL              = <?php echo json_encode(__('Flip board'                 , 'rpbchessboard')); ?>;
			rpb.i18n.SPECIAL_POSITIONS_SECTION_TITLE  = <?php echo json_encode(__('Special positions'          , 'rpbchessboard')); ?>;
			rpb.i18n.START_POSITION_TOOLTIP           = <?php echo json_encode(__('Set the initial position'   , 'rpbchessboard')); ?>;
			rpb.i18n.EMPTY_POSITION_TOOLTIP           = <?php echo json_encode(__('Clear the chessboard'       , 'rpbchessboard')); ?>;
			rpb.i18n.ADVANCED_TAB_LABEL               = <?php echo json_encode(__('Advanced'                   , 'rpbchessboard')); ?>;
			rpb.i18n.CASTLING_SECTION_TITLE           = <?php echo json_encode(__('Castling rights'            , 'rpbchessboard')); ?>;
			rpb.i18n.EN_PASSANT_SECTION_TITLE         = <?php echo json_encode(__('En passant'                 , 'rpbchessboard')); ?>;
			rpb.i18n.EN_PASSANT_DISABLED_BUTTON_LABEL = <?php echo json_encode(__('Not possible'               , 'rpbchessboard')); ?>;
			rpb.i18n.EN_PASSANT_ENABLED_BUTTON_LABEL  = <?php echo json_encode(__('Possible on column %1$s'    , 'rpbchessboard')); ?>;

			rpb.config.FEN_SHORTCODE = <?php echo json_encode($compatibility->getFENShortcode()); ?>;

		<?php endif; ?>

	})(<?php echo is_admin() ? 'RPBChessboard' : '{}'; ?>, jQuery);

</script>
