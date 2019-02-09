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

<script type="text/javascript">

	(function(rpb, $) {

		<?php // phpcs:disable Generic.Functions.FunctionCallArgumentSpacing.SpaceBeforeComma ?>
		rpb.i18n.EDITOR_BUTTON_LABEL              = <?php echo wp_json_encode( __( 'chessboard'                 , 'rpb-chessboard' ) ); ?>;
		rpb.i18n.EDIT_CHESS_DIAGRAM_DIALOG_TITLE  = <?php echo wp_json_encode( __( 'Insert/edit a chess diagram', 'rpb-chessboard' ) ); ?>;
		rpb.i18n.CANCEL_BUTTON_LABEL              = <?php echo wp_json_encode( __( 'Cancel'                     , 'rpb-chessboard' ) ); ?>;
		rpb.i18n.SUBMIT_BUTTON_ADD_LABEL          = <?php echo wp_json_encode( __( 'Add a new chess diagram'    , 'rpb-chessboard' ) ); ?>;
		rpb.i18n.SUBMIT_BUTTON_EDIT_LABEL         = <?php echo wp_json_encode( __( 'Update the chess diagram'   , 'rpb-chessboard' ) ); ?>;
		rpb.i18n.UNDO_TOOLTIP                     = <?php echo wp_json_encode( __( 'Undo the last modification' , 'rpb-chessboard' ) ); ?>;
		rpb.i18n.REDO_TOOLTIP                     = <?php echo wp_json_encode( __( 'Re-do the last modification', 'rpb-chessboard' ) ); ?>;
		rpb.i18n.FLIP_CHECKBOX_LABEL              = <?php echo wp_json_encode( __( 'Flip the board'             , 'rpb-chessboard' ) ); ?>;
		rpb.i18n.STANDARD_POSITIONS_TAB_LABEL     = <?php echo wp_json_encode( __( 'Standard positions'         , 'rpb-chessboard' ) ); ?>;
		rpb.i18n.START_POSITION_TOOLTIP           = <?php echo wp_json_encode( __( 'Set the initial position'   , 'rpb-chessboard' ) ); ?>;
		rpb.i18n.EMPTY_POSITION_TOOLTIP           = <?php echo wp_json_encode( __( 'Clear the chessboard'       , 'rpb-chessboard' ) ); ?>;
		rpb.i18n.ANNOTATIONS_TAB_LABEL            = <?php echo wp_json_encode( __( 'Annotations'                , 'rpb-chessboard' ) ); ?>;
		rpb.i18n.SQUARE_MARKERS_SECTION_TITLE     = <?php echo wp_json_encode( __( 'Square markers'             , 'rpb-chessboard' ) ); ?>;
		rpb.i18n.DELETE_SQUARE_MARKERS_TOOLTIP    = <?php echo wp_json_encode( __( 'Remove the square markers'  , 'rpb-chessboard' ) ); ?>;
		rpb.i18n.ARROW_MARKERS_SECTION_TITLE      = <?php echo wp_json_encode( __( 'Arrow markers'              , 'rpb-chessboard' ) ); ?>;
		rpb.i18n.DELETE_ARROW_MARKERS_TOOLTIP     = <?php echo wp_json_encode( __( 'Remove the arrow markers'   , 'rpb-chessboard' ) ); ?>;
		rpb.i18n.ADVANCED_TAB_LABEL               = <?php echo wp_json_encode( __( 'Advanced settings'          , 'rpb-chessboard' ) ); ?>;
		rpb.i18n.CASTLING_SECTION_TITLE           = <?php echo wp_json_encode( __( 'Castling rights'            , 'rpb-chessboard' ) ); ?>;
		rpb.i18n.EN_PASSANT_SECTION_TITLE         = <?php echo wp_json_encode( __( 'En passant'                 , 'rpb-chessboard' ) ); ?>;
		rpb.i18n.EN_PASSANT_DISABLED_BUTTON_LABEL = <?php echo wp_json_encode( __( 'Not possible'               , 'rpb-chessboard' ) ); ?>;
		rpb.i18n.EN_PASSANT_ENABLED_BUTTON_LABEL  = <?php echo wp_json_encode( __( 'Possible on column %1$s'    , 'rpb-chessboard' ) ); ?>;
		rpb.i18n.KEYBOARD_SHORTCUTS_TAB_LABEL     = <?php echo wp_json_encode( __( 'Keyboard shortcuts'         , 'rpb-chessboard' ) ); ?>;
		rpb.i18n.UNDO_SHORTCUT_LABEL              = <?php echo wp_json_encode( __( 'undo'                       , 'rpb-chessboard' ) ); ?>;
		rpb.i18n.REDO_SHORTCUT_LABEL              = <?php echo wp_json_encode( __( 'redo'                       , 'rpb-chessboard' ) ); ?>;
		rpb.i18n.ADD_PAWNS_SHORTCUT_LABEL         = <?php echo wp_json_encode( __( 'add pawns'                  , 'rpb-chessboard' ) ); ?>;
		rpb.i18n.ADD_KNIGHTS_SHORTCUT_LABEL       = <?php echo wp_json_encode( __( 'add knights'                , 'rpb-chessboard' ) ); ?>;
		rpb.i18n.ADD_BISHOPS_SHORTCUT_LABEL       = <?php echo wp_json_encode( __( 'add bishops'                , 'rpb-chessboard' ) ); ?>;
		rpb.i18n.ADD_ROOKS_SHORTCUT_LABEL         = <?php echo wp_json_encode( __( 'add rooks'                  , 'rpb-chessboard' ) ); ?>;
		rpb.i18n.ADD_QUEENS_SHORTCUT_LABEL        = <?php echo wp_json_encode( __( 'add queens'                 , 'rpb-chessboard' ) ); ?>;
		rpb.i18n.ADD_KINGS_SHORTCUT_LABEL         = <?php echo wp_json_encode( __( 'add kings'                  , 'rpb-chessboard' ) ); ?>;
		<?php // phpcs:enable Generic.Functions.FunctionCallArgumentSpacing.SpaceBeforeComma ?>

		rpb.config.FEN_SHORTCODE = <?php echo wp_json_encode( $model->getFENShortcode() ); ?>;

	})(RPBChessboard, jQuery);

</script>
