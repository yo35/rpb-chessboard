<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2026  Yoann Le Montagner <yo35 -at- melix.net>       *
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

<td colspan="3" class="rpbchessboard-themingEditor" <?php echo $isNew ? 'id="rpbchessboard-colorsetCreator"' : ''; ?>>
    <form class="rpbchessboard-inlineForm" action="<?php echo esc_url( $model->getSubPageLink( $model->getCurrentSubPage() ) ); ?>" method="post">

        <input type="hidden" name="rpbchessboard_action" value="<?php echo esc_attr( $model->getFormEditColorsetAction( $isNew ) ); ?>" />
        <?php wp_nonce_field( 'rpbchessboard_post_action' ); ?>

        <div class="rpbchessboard-inlineFormTitle">
            <?php echo $isNew ? esc_html__( 'New colorset', 'rpb-chessboard' ) : esc_html__( 'Edit colorset', 'rpb-chessboard' ); ?>
        </div>

        <div>
            <label>
                <span><?php esc_html_e( 'Name', 'rpb-chessboard' ); ?></span>
                <input type="text" name="label" value="<?php echo esc_attr( $isNew ? $model->getLabelProposalForNewColorset() : $model->getColorsetLabel( $colorset ) ); ?>" />
            </label>
        </div>

        <?php if ( $isNew ) : ?>
        <div>
            <label>
                <span><?php esc_html_e( 'Slug', 'rpb-chessboard' ); ?></span>
                <input type="text" name="colorset" value="" />
            </label>
        </div>
        <?php else : ?>
        <input type="hidden" name="colorset" value="<?php echo esc_attr( $colorset ); ?>" />
        <?php endif; ?>

        <div class="rpbchessboard-table rpbchessboard-colorsetEditorComponents">
            <div>
                <div>
                    <label>
                        <span><?php esc_html_e( 'Dark squares', 'rpb-chessboard' ); ?></span>
                        <input type="text" size="7" maxlength="7" class="rpbchessboard-darkSquareColorField" name="darkSquareColor"
                            value="<?php echo esc_attr( $isNew ? $model->getRandomDarkSquareColor() : $model->getDarkSquareColor( $colorset ) ); ?>" />
                    </label>
                    <div class="rpbchessboard-darkSquareColorSelector"></div>
                </div>
                <div>
                    <label>
                        <span><?php esc_html_e( 'Light squares', 'rpb-chessboard' ); ?></span>
                        <input type="text" size="7" maxlength="7" class="rpbchessboard-lightSquareColorField" name="lightSquareColor"
                            value="<?php echo esc_attr( $isNew ? $model->getRandomLightSquareColor() : $model->getLightSquareColor( $colorset ) ); ?>" />
                    </label>
                    <div class="rpbchessboard-lightSquareColorSelector"></div>
                </div>
                <div>
                    <label>
                        <span><?php esc_html_e( 'Blue markers', 'rpb-chessboard' ); ?></span>
                        <input type="text" size="7" maxlength="7" class="rpbchessboard-blueMarkerColorField" name="blueMarkerColor"
                            value="<?php echo esc_attr( $isNew ? $model->getRandomBlueMarkerColor() : $model->getBlueMarkerColor( $colorset ) ); ?>" />
                    </label>
                    <div class="rpbchessboard-blueMarkerColorSelector"></div>
                </div>
            </div>
            <div>
                <div>
                    <label>
                        <span><?php esc_html_e( 'Green markers', 'rpb-chessboard' ); ?></span>
                        <input type="text" size="7" maxlength="7" class="rpbchessboard-greenMarkerColorField" name="greenMarkerColor"
                            value="<?php echo esc_attr( $isNew ? $model->getRandomGreenMarkerColor() : $model->getGreenMarkerColor( $colorset ) ); ?>" />
                    </label>
                    <div class="rpbchessboard-greenMarkerColorSelector"></div>
                </div>
                <div>
                    <label>
                        <span><?php esc_html_e( 'Red markers', 'rpb-chessboard' ); ?></span>
                        <input type="text" size="7" maxlength="7" class="rpbchessboard-redMarkerColorField" name="redMarkerColor"
                            value="<?php echo esc_attr( $isNew ? $model->getRandomRedMarkerColor() : $model->getRedMarkerColor( $colorset ) ); ?>" />
                    </label>
                    <div class="rpbchessboard-redMarkerColorSelector"></div>
                </div>
                <div>
                    <label>
                        <span><?php esc_html_e( 'Yellow markers', 'rpb-chessboard' ); ?></span>
                        <input type="text" size="7" maxlength="7" class="rpbchessboard-yellowMarkerColorField" name="yellowMarkerColor"
                            value="<?php echo esc_attr( $isNew ? $model->getRandomYellowMarkerColor() : $model->getYellowMarkerColor( $colorset ) ); ?>" />
                    </label>
                    <div class="rpbchessboard-yellowMarkerColorSelector"></div>
                </div>
            </div>
        </div>

        <p class="submit rpbchessboard-inlineFormButtons">
            <input type="submit" class="button-primary" value="<?php echo $isNew ? esc_attr__( 'Create colorset', 'rpb-chessboard' ) : esc_attr__( 'Save changes', 'rpb-chessboard' ); ?>" />
            <a class="button" href="<?php echo esc_url( $model->getSubPageLink( $model->getCurrentSubPage() ) ); ?>"><?php esc_html_e( 'Cancel', 'rpb-chessboard' ); ?></a>
        </p>

    </form>
</td>
