<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2023  Yoann Le Montagner <yo35 -at- melix.net>       *
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

<td colspan="3" class="rpbchessboard-themingEditor" <?php echo $isNew ? 'id="rpbchessboard-piecesetCreator"' : ''; ?>>
	<form class="rpbchessboard-inlineForm" action="<?php echo esc_url( $model->getSubPageLink( $model->getCurrentSubPage() ) ); ?>" method="post">

		<input type="hidden" name="rpbchessboard_action" value="<?php echo esc_attr( $model->getFormEditPiecesetAction( $isNew ) ); ?>" />
		<?php wp_nonce_field( 'rpbchessboard_post_action' ); ?>

		<?php foreach ( array( 'bp', 'bn', 'bb', 'br', 'bq', 'bk', 'bx', 'wp', 'wn', 'wb', 'wr', 'wq', 'wk', 'wx' ) as $coloredPiece ) : ?>
		<input type="hidden" class="rpbchessboard-imageIdField" name="imageId-<?php echo esc_attr( $coloredPiece ); ?>"
			value="<?php echo $isNew ? '-1' : esc_attr( $model->getCustomPiecesetImageId( $pieceset, $coloredPiece ) ); ?>"
			data-initial-preview-url="<?php echo esc_url( $isNew ? RPBCHESSBOARD_URL . 'images/piece-empty.png' : $model->getCustomPiecesetImageURL( $pieceset, $coloredPiece ) ); ?>"
		/>
		<?php endforeach; ?>

		<div class="rpbchessboard-inlineFormTitle">
			<?php echo $isNew ? esc_html__( 'New pieceset', 'rpb-chessboard' ) : esc_html__( 'Edit pieceset', 'rpb-chessboard' ); ?>
		</div>

		<div>
			<label>
				<span><?php esc_html_e( 'Name', 'rpb-chessboard' ); ?></span>
				<input type="text" name="label"
					value="<?php echo esc_attr( $isNew ? $model->getLabelProposalForNewPieceset() : $model->getPiecesetLabel( $pieceset ) ); ?>" />
			</label>
		</div>

		<?php if ( $isNew ) : ?>
		<div>
			<label>
				<span><?php esc_html_e( 'Slug', 'rpb-chessboard' ); ?></span>
				<input type="text" name="pieceset" value="" />
			</label>
		</div>
		<?php else : ?>
		<input type="hidden" name="pieceset" value="<?php echo esc_attr( $pieceset ); ?>" />
		<?php endif; ?>

		<div class="rpbchessboard-table rpbchessboard-piecesetEditorComponents">
			<div>
				<?php foreach ( array( 'bk', 'bq', 'br', 'bb', 'bn', 'bp', 'bx' ) as $coloredPiece ) : ?>
				<div>
					<a class="rpbchessboard-coloredPieceButton <?php echo esc_attr( 'rpbchessboard-coloredPieceButton-' . $coloredPiece ); ?>" href="#"
						data-colored-piece="<?php echo esc_attr( $coloredPiece ); ?>" title="<?php echo esc_attr( $model->getPiecesetEditionButtonTitle( $coloredPiece ) ); ?>">
						<img src="<?php echo esc_url( $model->getPiecesetEditionButtonImage( $isNew ? '' : $pieceset, $coloredPiece ) ); ?>" />
					</a>
				</div>
				<?php endforeach; ?>
			</div>
			<div>
				<?php foreach ( array( 'wk', 'wq', 'wr', 'wb', 'wn', 'wp', 'wx' ) as $coloredPiece ) : ?>
				<div>
					<a class="rpbchessboard-coloredPieceButton <?php echo esc_attr( 'rpbchessboard-coloredPieceButton-' . $coloredPiece ); ?>" href="#"
						data-colored-piece="<?php echo esc_attr( $coloredPiece ); ?>" title="<?php echo esc_attr( $model->getPiecesetEditionButtonTitle( $coloredPiece ) ); ?>">
						<img src="<?php echo esc_url( $model->getPiecesetEditionButtonImage( $isNew ? '' : $pieceset, $coloredPiece ) ); ?>" />
					</a>
				</div>
				<?php endforeach; ?>
			</div>
		</div>

		<p class="submit rpbchessboard-inlineFormButtons">
			<input type="submit" class="button-primary" value="<?php echo $isNew ? esc_attr__( 'Create pieceset', 'rpb-chessboard' ) : esc_attr__( 'Save changes', 'rpb-chessboard' ); ?>" />
			<a class="button" href="<?php echo esc_url( $model->getSubPageLink( $model->getCurrentSubPage() ) ); ?>"><?php esc_html_e( 'Cancel', 'rpb-chessboard' ); ?></a>
		</p>

	</form>
</td>
