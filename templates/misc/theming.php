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

	.rpbui-chessboard-colorset-<?php echo htmlspecialchars( $colorset ); ?> .rpbui-chessboard-darkSquare {
		background-color: <?php echo htmlspecialchars( $model->getDarkSquareColor( $colorset ) ); ?>;
	}

	.rpbui-chessboard-colorset-<?php echo htmlspecialchars( $colorset ); ?> .rpbui-chessboard-lightSquare {
		background-color: <?php echo htmlspecialchars( $model->getLightSquareColor( $colorset ) ); ?>;
	}

<?php endforeach; ?>


<?php foreach ( $model->getCustomPiecesets() as $pieceset ) : ?>

	<?php foreach ( array( 'b', 'w' ) as $color ) : ?>

		<?php foreach ( array( 'p', 'n', 'b', 'r', 'q', 'k' ) as $piece ) : ?>
			.rpbui-chessboard-pieceset-<?php echo htmlspecialchars( $pieceset ); ?>
				.rpbui-chessboard-color-<?php echo $color; ?>.rpbui-chessboard-piece-<?php echo $piece; ?>
				{ background-image: url(<?php echo htmlspecialchars( $model->getCustomPiecesetSpriteURL( $pieceset, $color . $piece ) ); ?>); }
		<?php endforeach; ?>

		.rpbui-chessboard-pieceset-<?php echo htmlspecialchars( $pieceset ); ?>
			.rpbui-chessboard-color-<?php echo $color; ?>.rpbui-chessboard-turnFlag
			{ background-image: url(<?php echo htmlspecialchars( $model->getCustomPiecesetSpriteURL( $pieceset, $color . 'x' ) ); ?>); }

	<?php endforeach; ?>
<?php endforeach; ?>
