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

<?php foreach ( $model->getCustomColorsets() as $colorset ) : ?>

.<?php echo sanitize_html_class( 'rpbui-chessboard-colorset-' . $colorset ); ?> .rpbui-chessboard-darkSquare {
	background-color: <?php echo sanitize_hex_color( $model->getDarkSquareColor( $colorset ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>;
}

.<?php echo sanitize_html_class( 'rpbui-chessboard-colorset-' . $colorset ); ?> .rpbui-chessboard-lightSquare {
	background-color: <?php echo sanitize_hex_color( $model->getLightSquareColor( $colorset ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>;
}

<?php endforeach; ?>


<?php foreach ( $model->getCustomPiecesets() as $pieceset ) : ?>

<?php foreach ( array( 'bp', 'bn', 'bb', 'br', 'bq', 'bk', 'wp', 'wn', 'wb', 'wr', 'wq', 'wk' ) as $coloredPiece ) : ?>
.<?php echo sanitize_html_class( 'rpbui-chessboard-pieceset-' . $pieceset ); ?>
	.<?php echo sanitize_html_class( 'rpbui-chessboard-piece-' . $coloredPiece ); ?>
	{ background-image: url(<?php echo esc_url( $model->getCustomPiecesetImageURL( $pieceset, $coloredPiece ) ); ?>); }
<?php endforeach; ?>

<?php foreach ( array( 'b', 'w' ) as $color ) : ?>
.<?php echo sanitize_html_class( 'rpbui-chessboard-pieceset-' . $pieceset ); ?>
	.<?php echo sanitize_html_class( 'rpbui-chessboard-color-' . $color ); ?>.rpbui-chessboard-turnFlag
	{ background-image: url(<?php echo esc_url( $model->getCustomPiecesetImageURL( $pieceset, $color . 'x' ) ); ?>); }
<?php endforeach; ?>

<?php endforeach; ?>
