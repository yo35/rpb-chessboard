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

<td colspan="3" <?php echo $isNew ? 'id="rpbchessboard-setCodeCreator"' : 'class="rpbchessboard-setCodeEditor"'; ?> >
	<form class="rpbchessboard-inlineForm" action="<?php echo esc_attr( $model->getFormActionURL() ); ?>" method="post">

		<input type="hidden" name="rpbchessboard_action" value="<?php echo esc_attr( $model->getFormAction( $isNew ) ); ?>" />
		<?php wp_nonce_field( 'rpbchessboard_post_action' ); ?>

		<div class="rpbchessboard-inlineFormTitle">
			<?php $isNew ? _e( 'New colorset', 'rpb-chessboard' ) : _e( 'Edit colorset', 'rpb-chessboard' ); ?>
		</div>

		<div>
			<label>
				<span><?php _e( 'Name', 'rpb-chessboard' ); ?></span>
				<input type="text" name="label"
					value="<?php echo esc_attr( $isNew ? $model->getLabelProposalForNewSetCode() : $model->getCustomColorsetLabel( $colorset ) ); ?>" />
			</label>
		</div>

		<?php if ( $isNew ) : ?>
		<div>
			<label>
				<span><?php _e( 'Slug', 'rpb-chessboard' ); ?></span>
				<input type="text" name="colorset" value="" />
			</label>
		</div>
		<?php else : ?>
		<input type="hidden" name="colorset" value="<?php echo esc_attr( $colorset ); ?>" />
		<?php endif; ?>

		<div class="rpbchessboard-columns">

			<div class="rpbchessboard-stretchable rpbchessboard-colorFieldAndSelector">
				<label>
					<span><?php _e( 'Dark squares', 'rpb-chessboard' ); ?></span>
					<input type="text" size="7" maxlength="7" class="rpbchessboard-darkSquareColorField" name="darkSquareColor"
						value="<?php echo esc_attr( $isNew ? $model->getRandomDarkSquareColor() : $model->getDarkSquareColor( $colorset ) ); ?>" />
				</label>
				<div>
					<div class="rpbchessboard-darkSquareColorSelector"></div>
				</div>
			</div>

			<div class="rpbchessboard-stretchable rpbchessboard-colorFieldAndSelector">
				<label>
					<span><?php _e( 'Light squares', 'rpb-chessboard' ); ?></span>
					<input type="text" size="7" maxlength="7" class="rpbchessboard-lightSquareColorField" name="lightSquareColor"
						value="<?php echo esc_attr( $isNew ? $model->getRandomLightSquareColor() : $model->getLightSquareColor( $colorset ) ); ?>" />
				</label>
				<div>
					<div class="rpbchessboard-lightSquareColorSelector"></div>
				</div>
			</div>

		</div>

		<p class="submit rpbchessboard-inlineFormButtons">
			<input type="submit" class="button-primary" value="<?php $isNew ? _e( 'Create colorset', 'rpb-chessboard' ) : _e( 'Save changes', 'rpb-chessboard' ); ?>" />
			<a class="button" href="<?php echo esc_attr( $model->getFormActionURL() ); ?>"><?php _e( 'Cancel', 'rpb-chessboard' ); ?></a>
		</p>

	</form>
</td>
