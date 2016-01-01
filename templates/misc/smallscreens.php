<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2016  Yoann Le Montagner <yo35 -at- melix.net>       *
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

<?php if(!$model->getSmallScreenCompatibility()) { return; } ?>

<?php foreach($model->getSmallScreenModes() as $mode): ?>
	<?php echo $model->getSmallScreenModeMainSelector($mode); ?> {

		<?php if($model->hasSmallScreenSizeSquareSizeSection($mode)): ?>

			<?php echo $model->getSmallScreenModeSquareSizeSelector($mode); ?> {
				min-width: <?php echo htmlspecialchars($mode->squareSize); ?>px;
				width    : <?php echo htmlspecialchars($mode->squareSize); ?>px;
				height   : <?php echo htmlspecialchars($mode->squareSize); ?>px;
				background-position:
					<?php echo htmlspecialchars($model->getBackgroundPositionXForSquareSize($mode->squareSize)); ?>px
					<?php echo htmlspecialchars($model->getBackgroundPositionYForSquareSize($mode->squareSize)); ?>px;
			}

			<?php echo $model->getSmallScreenModeAnnotationLayerSelector($mode); ?> {
				width : <?php echo htmlspecialchars($model->getHeightWidthForAnnotationLayer($mode->squareSize)); ?>px;
				height: <?php echo htmlspecialchars($model->getHeightWidthForAnnotationLayer($mode->squareSize)); ?>px;
				right : <?php echo htmlspecialchars($model->getRightForAnnotationLayer($mode->squareSize)); ?>px;
			}

		<?php endif; ?>

		<?php if($mode->hideCoordinates): ?>
			.uichess-chessboard-cell.uichess-chessboard-rowCoordinate,
			.uichess-chessboard-row.uichess-chessboard-columnCoordinateRow {
				display: none;
			}
		<?php endif; ?>

	}
<?php endforeach; ?>
