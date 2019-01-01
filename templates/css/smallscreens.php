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

<?php
if ( ! $model->getSmallScreenCompatibility() ) {
	return;
}
?>

<?php foreach ( $model->getSmallScreenModes() as $mode ) : ?>
<?php echo esc_html( $model->getSanitizedMainSelector( $mode ) ); ?> {

	<?php if ( $model->hasSquareSizeSection( $mode ) ) : ?>

	<?php echo esc_html( $model->getSanitizedSquareSizeSelector( $mode ) ); ?> {
		min-width: <?php echo esc_html( $mode->squareSize ); ?>px;
		width    : <?php echo esc_html( $mode->squareSize ); ?>px;
		height   : <?php echo esc_html( $mode->squareSize ); ?>px;
		background-size: <?php echo esc_html( $mode->squareSize ); ?>px <?php echo esc_html( $mode->squareSize ); ?>px;
		-webkit-background-size: <?php echo esc_html( $mode->squareSize ); ?>px <?php echo esc_html( $mode->squareSize ); ?>px;
	}

	<?php echo esc_html( $model->getSanitizedAnnotationLayerSelector( $mode ) ); ?> {
		width : <?php echo esc_html( $model->getHeightWidthForAnnotationLayer( $mode->squareSize ) ); ?>px;
		height: <?php echo esc_html( $model->getHeightWidthForAnnotationLayer( $mode->squareSize ) ); ?>px;
		right : <?php echo esc_html( $model->getRightForAnnotationLayer( $mode->squareSize ) ); ?>px;
	}

	<?php endif; ?>

	<?php if ( $mode->hideCoordinates ) : ?>

	.rpbui-chessboard-cell.rpbui-chessboard-rowCoordinate, .rpbui-chessboard-row.rpbui-chessboard-columnCoordinateRow {
		display: none;
	}

	<?php endif; ?>

}
<?php endforeach; ?>
